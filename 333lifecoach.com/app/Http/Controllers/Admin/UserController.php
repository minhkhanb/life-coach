<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Model\User;
use Illuminate\Support\Facades\Auth;
use App\Model\ManageAffiliate;
use Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index');
    }

    // show info user
    public function profile($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.profile', compact('user'));
    }

    // update info user
    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $rules = [
            'name' => 'required|min:6',
            'identity_card' => 'required|min:8|numeric',
            'email' => 'email:rfc,dns',
        ];
        if (!empty($request->get('email_fb'))) {
            $rules = array_merge($rules, ['email_fb' => 'email']);
        }
        if (!empty($request->get('nick_fb'))) {
            $rules = array_merge($rules, ['nick_fb' => 'url']);
        }

        $message = [
            'name.required' => 'Họ tên không được trống!',
            'identity_card.required' => 'Số chứng minh nhân dân không được trống!',
            'name.min' => 'Họ tên ít nhất 6 ký tự!',
            'identity_card.min' => 'Số chứng minh nhân dân ít nhất 8 ký tự!',
            'identity_card.numeric' => 'Số chứng minh nhân dân phải để định dạng số!',
            'email.email' => 'Email không đúng định dạng!',
            'email_fb.email' => 'Email facebook không đúng định dạng!',
            'nick_fb.url' => 'Link facebook không đúng định dạng!',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $duplicate_identify = User::query()
            ->where('identity_card', '<>', Auth::user()->identity_card)
            ->where('identity_card', $request->get('identity_card'))->first();
            if (isset($duplicate_identify)) {
                return redirect()->back()->withErrors(['identity_card' => 'Số chứng minh thư đã tồn tại']);
            }

            $duplicate_email = User::query()
            ->where('email', '<>', Auth::user()->email)
            ->where('email', $request->get('email'))->first();
            if (isset($duplicate_email)) {
                return redirect()->back()->withErrors(['email' => 'Email đã tồn tại']);
            }


            if ($request->hasFile('image')) {
                $file = $request->file('image');
                //Move Uploaded File
                $destinationPath = 'uploads/avatar';
                $file->move($destinationPath, $file->getClientOriginalName());
                $user->image = $file->getClientOriginalName();
            }
            $user->name = $request->name;
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->nick_fb = $request->nick_fb;
            $user->email_fb = $request->email_fb;
            $user->identity_card = $request->get('identity_card');
            $user->email = $request->get('email');
            $user->save();
            return redirect()->back()->with('success', 'Cập nhật thông tin thành công!');
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['error' => $exception->getMessage()]);
        }
    }

    // update password
    public function updatePassword(Request $request, $id)
    {
        $user = Auth::user();
        $rules = [
            'new_password' => 'required|min:6',
            'confirm_new_password' => 'required|min:6|same:new_password'
        ];
        $message = [
            'new_password.required' => 'Mật khẩu mới không được trống!',
            'new_password.min' => 'Mật khẩu mới không được ít hơn 6 ký tự!',
            'confirm_new_password.required' => 'Xác nhận mật khẩu mới không được trống!',
            'confirm_new_password.min' => 'Xác nhận mật khẩu mới không được ít hơn 6 ký tự!',
            'confirm_new_password.same' => 'Xác nhận mật khẩu phải khớp với mật khẩu!',
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            Auth::user()->update(['password' => bcrypt($request->new_password)]);
            return redirect()->back()->with('success', 'Thay đổi mật khẩu thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    // student register to do coach
    public function registerCoach(Request $request, $id)
    {
        return view('admin.user.register-coach');
    }

    // verify student to coach
    public function verifyCoach(Request $request, $id)
    {
        $user = User::query()->where('id', '=', $id)->first();
        return view('admin.user.verify-student-coach', compact('user'));
    }

    // confirm update student to coach
    public function confirmVerifyCoach(Request $request, $id)
    {
        $user = User::query()->where('id', '=', $id)->first();
        $user->type = User::TYPE_COACHING;
        $user->user_owner = null;
        $user->save();

        $manager_affiliate = new ManageAffiliate();
        $manager_affiliate->admin_id = Auth::user()->id;
        $manager_affiliate->coach_id = $user->id;
        $manager_affiliate->save();
        return redirect()->back()->with('success', 'Cập nhập học viên thành trợ giảng thành công!');
    }

    // delete role user to coach
    public function deleteVerifyCoach(Request $request, $id)
    {
        $user = User::query()->where('id', '=', $id)->first();
        $user->image_identify = null;
        $user->image_identify_2 = null;
        $user->save();
        
        return redirect()->route('student.index')->with('success', 'Xóa yêu cầu làm trợ giảng của học viên thành công!');
    }
}
