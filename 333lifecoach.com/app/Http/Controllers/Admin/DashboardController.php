<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\User;
use App\Model\ManageAffiliate;
use App\Notifications\InfoAccountNotification;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Mail;

class DashboardController extends Controller
{
    public function showRegisterCoach()
    {
        return view('admin.dashboard.register_coach');
    }

    public function registerCoach(Request $request)
    {
        $rules = [
        	'name' => 'required|min:6',
            'identity_card' => 'required|unique:users|numeric',
            'email' => 'required|unique:users',
            'phone' => 'required|numeric|unique:users'
        ];
        $message = [
            'name.required' => 'Tên trợ giảng không được trống!',
            'name.min' => 'Tên trợ giảng ít nhất 6 kí tự!',
            'identity_card.required' => 'Số chứng minh nhân dân không được trống!',
            'identity_card.unique' => 'Số chứng minh nhân dân đã tồn tại!',
            'identity_card.numeric' => 'Số chứng minh nhân dân phải để định dạng số!',
            'email.required' => 'Email không được trống!',
            'email.unique' => 'Email đã tồn tại!',
            'phone.required' => 'Số điện thoại không được trống!',
            'phone.numeric' => 'Số điện thoại phải để định dạng số!',
            'phone.unique' => 'Số điện thoại đã tồn tại!',
        ];
        $validator = Validator::make($request->all(), $rules, $message);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $passwordRandom = rand();

        	$timeNow = new DateTime();
	    	$user = new User();
			$user->name = $request->input('name');
			$user->password = Hash::make($passwordRandom);
			$user->email = trim($request->input('email'));
			$user->phone = $request->input('phone');
            $user->username = $request->input('phone');
			$user->identity_card = $request->input('identity_card');
			$user->active = User::ACTIVE;
			$user->type = User::TYPE_COACHING;
			$user->date_join_sys = Carbon::now('Asia/Ho_Chi_Minh');
			$user->save();

            $data = array(
                'email' => trim($user->email),
                'name' => $user->name,
                'phone' => $user->phone,
                'password' => $passwordRandom
            );
            // send info to email
            Notification::send($user, new InfoAccountNotification($data));

        } catch (\Exception $e) {
        	return redirect()->back()->withErrors(["error" => $e->getMessage()]);
        }
        return redirect()->route('coach.index')->with('success', 'Tạo tài khoản trợ giảng thành công!');
    }

    public function showListCoach(Request $request){
        $key_search = $request->search ? $request->search : "";

    	$users = User::query()
            ->where('type', '=', User::TYPE_COACHING)
            ->where('name', 'like', '%'.$request->search.'%')
            ->when($request->get('status'), function ($query, $status) {
                $query->where('active', '<>', $status);
            })
            ->paginate(10);

        $user_wait_browsing = User::query()->where('active', '=', User::ACTIVE)->where('type', '=', User::TYPE_COACHING)->whereNotNull('image_identify')->get();
    	return view('admin.dashboard.list_coach', compact('users', 'user_wait_browsing', 'key_search'));
    }

    public function getCoach($id){
    	$user = User::findOrFail($id);
    	return view('admin.dashboard.getCoach', compact('user'));
    }

    public function updateCoach($id){
    	$user = User::findOrFail($id);
        if ($user->image_identify && $user->image_identify_2) {
            $user->active='2';
            $user->save();

            $manager_affiliate = new ManageAffiliate();
            $manager_affiliate->admin_id = Auth::user()->id;
            $manager_affiliate->coach_id = $user->id;
            $manager_affiliate->save();
            return redirect()->back()->with('success', 'Xác thực tài khoản thành công');
        }else {
            return redirect()->back()->with('warning', 'Đợi trợ giảng upload đầy đủ chứng minh nhân dân 2 mặt');
        }

    }

    public function deleteCoach($id){
    	$user = User::findOrFail($id);
        $user->delete();

        $manager_affiliate = ManageAffiliate::query()->where('admin_id', Auth::user()->id)->where('coach_id', $user->id)->first();
        if (isset($manager_affiliate)) {
            $manager_affiliate->delete();
        }
        return redirect()->back()->with('success', 'Xóa thành công');
    }

    public function getAffiliate($id){
    	$manager_affiliate = ManageAffiliate::query()->where('coach_id', $id)->first();
    	if ($manager_affiliate) {
    		$link_affiliate = $manager_affiliate->link_affiliate;
    	}else {
    		$link_affiliate = '';
    	}
    	return view('admin.dashboard.coach_affiliate', compact('link_affiliate'));
    }

    public function createAffiliate(Request $request){
    	$rules = [
        	'name' => 'required|min:6'
        ];
        $message = [
            'name.required' => 'Tên trợ giảng không được trống!'
        ];
        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
        	$manager_affiliate = ManageAffiliate::query()->where('coach_id', Auth::user()->id)->first();
	    	$manager_affiliate->link_affiliate = request()->getHttpHost() . '/register?ref=' . $request->input('name') . '&uid=' . Auth::user()->identity_card;
	    	$manager_affiliate->save();
	    	return redirect()->back()->with('success', 'Lấy link affiliate thành công');
        } catch (\Exception $e) {
        	return redirect()->back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    public function showUploadIdentify(){
        if (Auth::user()->active == '1') {
            return view('admin.user.coach_upload_indentify');
        }else {
            return redirect()->route('dashboard');
        }
    }

    public function UploadIdentify(Request $request){
        $rules = [
            'image_1' => 'required',
            'image_2' => 'required'
        ];
        $message = [
            'image_1.required' => 'Ảnh chứng minh thư mặt trước không được trống!',
            'image_2.required' => 'Ảnh chứng minh thư mặt sau không được trống!'
        ];
        $validator = Validator::make($request->all(), $rules, $message);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $user = User::findOrFail(Auth::user()->id);
            $file_1 = $request->file('image_1');
            $file_2 = $request->file('image_2');
            $destinationPath = 'uploads/identify';
            $file_1->move($destinationPath, $file_1->getClientOriginalName());
            $file_2->move($destinationPath, $file_2->getClientOriginalName());
            $user->image_identify = $file_1->getClientOriginalName();
            $user->image_identify_2 = $file_2->getClientOriginalName();
            $user->save();
            return redirect()->back()->with(['success', 'Gửi chứng minh thư thành công']);

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    // delete request coach
    public function deleteRequestCoach(Request $request, $id)
    {
        $user = User::query()->where('id', '=', $id)->first();
        $user->image_identify = null;
        $user->image_identify_2 = null;
        $user->save();
        
        return redirect()->route('coach.index')->with('success', 'Xóa yêu cầu làm trợ giảng!');
    }

}
