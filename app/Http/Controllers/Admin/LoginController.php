<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Model\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Model\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
    }

    /**
     * regex:/^(?=.*[A-Za-z0-9])(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{6,}$/
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
        $rules = [
            $this->username() => 'required|string|max:255',
            'password' => 'required',
        ];
        $message = [
            'username.required' => 'Tên đăng nhập hoặc số điện thoại không được trống!',
            'password.required' => 'Mật khẩu không được trống!',
        ];
        $validator = Validator::make($credentials, $rules, $message);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $user = \App\Model\User::query()->where('username', '=', trim($request->get('username')))->first();
            if ($user && $user->active === \App\Model\User::UN_ACTIVE) {
                return redirect()->back()->withErrors(["error" => "Tài khoản chưa được kích hoạt!"]);
            }
            $login = $this->attemptLogin($request);
            
            if (!$login) {
                return redirect()->back()->withErrors(["error" => "Thông tin tài khoản không tồn tại!"]);
            }
            return redirect()->intended('/');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login')->withErrors(["error" => "Bạn đã đăng xuất, mời đăng nhập lại !"]);
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function username()
    {
        return 'username';
    }

    public function forgotPassword()
    {
        return view('admin.forgot_password');
    }

    public function sendMailPassword(Request $request)
    {
        //Tạo token và gửi đường link reset vào email nếu email tồn tại
        $user = \App\Model\User::query()->where('email', $request->email)->first();
        if($user){
            $email = $request->email;
            $token = Str::random(60);

            $resetPassword = PasswordReset::firstOrCreate([ 'email'=>$email, 'token'=>$token ]);

            $data = array(
                'email' => $email,
                'token' => $token
            );

            Notification::send($user, new ResetPasswordNotification($data));

            $token = PasswordReset::where('email', $request->email)->first();
            return redirect()->back()->withErrors(["error" => "Email cập nhật mật khẩu đã được gửi !"]);
        } else {
            return redirect()->back()->withErrors(["error" => "Email không có trong hệ thống, vui lòng kiểm tra lại"]);
        }
    }

    public function formResetPassword(Request $request, $code)
    {
        $pass_reset = PasswordReset::query()->where('token', '=', $code)->first();
        if ($pass_reset) {
            $user = User::query()->where('email', $pass_reset->email)->first();
            return view('admin.reset_password', compact('user', 'code'));
        } else {
            return redirect()->route('forgotPassword')->withErrors(["error" => "Hết hạn cập nhật mật khẩu mới"]);
        }
        
    }

    public function resetPass(Request $request)
    {
        $credentials = $request->only('password', 'confirm_password', 'uid');
        $rules = [
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password|min:6'
        ];
        $message = [
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
            $user = User::query()->where('id', $request->uid)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            $uid = $request->input('uid');
            $pass_reset = PasswordReset::query()->where('token', $request->code)->delete();
            return redirect(route('login'))->withErrors(["error" => 'Cập nhật mật khẩu mới thành công, yêu cầu đăng nhập lại !']);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(["error" => $e->getMessage()]);
        }
    }
}
