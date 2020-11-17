<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Notifications\ActiveAccountNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use App\Model\User;
use App\Model\VerifyUser;
use Mail;
use Carbon\Carbon;

class RegisterController extends Controller
{
    use RegistersUsers;

    public function __construct()
    {
    }

    protected $redirectTo = '/login';

    /**
     * regex:/^(?=.*[A-Za-z0-9])(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,}$/
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $credentials = $request->only('name', 'email', 'phone', 'password', 'confirm_password', 'identity_card');
        $rules = [
            'name' => 'required|min:6',
            'email' => 'required|unique:users',
            'phone' => 'required|numeric|unique:users',
            'identity_card' => 'required|unique:users|numeric',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password|min:6'
        ];
        $message = [
            'name.required' => 'Họ tên không được trống!',
            'name.min' => 'Tên coach ít nhất 6 kí tự!',
            'email.required' => 'Email không được trống!',
            'email.unique' => 'Email đã tồn tại!',
            'phone.required' => 'Số điện thoại không được trống!',
            'phone.numeric' => 'Số điện thoại phải định dạng số!',
            'phone.unique' => 'Số điện thoại đã tồn tại!',
            'identity_card.required' => 'Số chứng minh nhân dân không được trống!',
            'identity_card.unique' => 'Số chứng minh nhân dân đã tồn tại!',
            'identity_card.numeric' => 'Số chứng minh nhân dân phải để định dạng số!',
            'password.required' => 'Mật khẩu không được trống!',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự!',
            'confirm_password.required' => 'Xác nhận mật khẩu không được trống!',
            'confirm_password.same' => 'Xác nhận mật khẩu phải khớp với mật khẩu!',
            'confirm_password.min' => 'Xác nhận mật khẩu ít nhất 6 ký tự!',
        ];
        $validator = Validator::make($credentials, $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = trim($request->email);
        	$user->phone = $request->phone;
        	$user->username = $request->phone;
            $user->identity_card = $request->identity_card;
            $user->password = Hash::make($request->password);
            $user->active = User::UN_ACTIVE;
            $user->type = User::TYPE_STUDENT;
        	$user->save();

            $uid = $request->input('uid');
            $user_owner = User::query()->where('identity_card', $uid)->first();
            if (isset($user_owner)) {
                $user->user_owner = $user_owner->id;
            }
            $user->save();
            if (!$user) {
                return redirect()->back()->withErrors(["error" => "Tài khoản không tồn tại!"]);
            }

            $confirmation_code = time().uniqid(true);

            VerifyUser::create([
                'email' => $user->email,
                'confirmation_code' => $confirmation_code,
                'time_start' => Carbon::now('Asia/Ho_Chi_Minh'),
                'time_end' => '2'
            ]);

            $data = array(
                'confirmation_code' => $confirmation_code,
                'name' => $user->name
                );
            Notification::send($user, new ActiveAccountNotification($data));
            return redirect(route('login'))->withErrors(["error" => 'Yêu cầu xác nhận tài khoản trong email!']);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    public function showRegistrationForm(Request $request)
    {
        $uid = $request->uid;
        return view('admin.register', compact('uid'));
    }

    public function verify($code)
    {
        $verify_user = VerifyUser::where('confirmation_code', '=', $code)->first();
        $user = User::where('email', '=', $verify_user['email']);
        $time_now = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d H:i:s');

        $carbon_timestart = Carbon::parse($verify_user->time_start)->addDays($verify_user->time_end)->format('Y-m-d H:i:s');

        if ($time_now > $carbon_timestart) {
            $notification_status ='Quá hạn xác thực, mời bạn đăng ký tài khoản mới!';
        } else {
            if ($user->count() > 0) {
                $user->update([
                    'active' => '2'
                ]);
                $notification_status = 'Bạn đã xác thực thành công, mời đăng nhập!';
            } else {
                $notification_status ='Mã xác thực không chính xác!';
            }
        }

        return redirect('/login')->withErrors(["error" => $notification_status]);
    }
}
