<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\UserRole;
use Auth;
use Hash;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Image;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'user_management';
        $dataBag['sidebar_child'] = 'all-users';
        $authId = Auth::user()->id;
        $pagination = !empty($request->get('pagination')) ? $request->get('pagination') : 25;
        $dataBag['data'] = User::with(['userRoles'])
            ->where('id', '!=', $authId)
            ->where('status', '!=', 3)
            ->where('user_category', 1)
            ->orderBy('id', 'desc')
            ->paginate($pagination);

        if ($request->ajax()) {
            return view('backend.user.all-users-render', $dataBag);
        }

        return view('backend.user.index', $dataBag);
    }

    public function addUser(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'user_management';
        $dataBag['sidebar_child'] = 'add-user';
        $dataBag['roles'] = Roles::where('status', 1)
            ->orderBy('display_order', 'asc')
            ->get();

        return view('backend.user.add', $dataBag);
    }

    public function saveUser(Request $request)
    {
        $emailId = $request->input('email_id');
        $userName = $request->input('user_name') ?? null;
        $phoneNumber = $request->input('phone_number');
        $loginId = $request->input('login_id') ?? null;

        $checkEmail = User::where('email_id', $emailId)->whereNotNull('email_id')->exists();
        $checkPhone = User::where('phone_number', $phoneNumber)->whereNotNull('phone_number')->exists();
        $checkUserName = User::where('user_name', $userName)->whereNotNull('user_name')->exists();
        $checkUserLoginId = User::where('login_id', $loginId)->whereNotNull('login_id')->exists();

        if ($checkEmail) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Email id already exist');
        }
        if ($checkPhone) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Phone number already exist');
        }
        if ($checkUserName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Username already exist');
        }
        if ($checkUserLoginId) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Login ID already exist');
        }

        $user = new User();
        $user->hash_id = Str::uuid(36)->toString();
        $user->unique_id = Helper::userUniqueId();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email_id = $request->input('email_id');
        $user->phone_number = $request->input('phone_number');
        $user->whatsapp_number = $request->input('whatsapp_number');
        $user->is_crm_access = $request->input('crm_access_value') ?? 0;
        $user->user_name = $request->input('user_name') ?? null;
        $user->password = !empty($request->input('password')) ? Hash::make($request->input('password')) : null;
        $user->agent_id = ($request->has('agent_id')) ? $request->input('agent_id') : null;
        $user->login_id = ($request->has('login_id')) ? $request->input('login_id') : null;
        $user->user_category = $request->input('user_category');
        $user->status = 1;
        if ($user->save()) {
            UserRole::insert(['user_id' => $user->id, 'role_id' => $request->input('role_id')]);
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'New user has been created successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function deleteUser(Request $request, $id)
    {
        $id = Helper::userId($id);
        if (!$id) {
            abort(404);
        }
        $user = User::findOrFail($id);
        $user->status = 3;
        $user->save();
        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'User has been deleted successfully');
    }

    public function editUser(Request $request, $id)
    {
        $id = Helper::userId($id);
        if (!$id) {
            abort(404);
        }

        $dataBag = [];
        $dataBag['sidebar_parent'] = 'user_management';
        $dataBag['sidebar_child'] = 'add-user';
        $dataBag['user'] = User::with(['userRoles'])->findOrFail($id);
        $dataBag['roles'] = Roles::where('status', 1)->orderBy('display_order', 'asc')->get();

        return view('backend.user.edit', $dataBag);
    }

    public function updateUser(Request $request, $id)
    {
        $emailId = $request->input('email_id');
        $phoneNumber = $request->input('phone_number');
        $loginId = $request->input('login_id') ?? null;

        $checkEmail = User::where('email_id', $emailId)->whereNotNull('email_id')->where('id', '!=', $id)->exists();
        $checkPhone = User::where('phone_number', $phoneNumber)->whereNotNull('phone_number')->where('id', '!=', $id)->exists();
        $checkUserLoginId = User::where('login_id', $loginId)->whereNotNull('login_id')->where('id', '!=', $id)->exists();

        if ($checkEmail) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Email id already exist');
        }
        if ($checkPhone) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Phone number already exist');
        }
        if ($checkUserLoginId) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Login ID already exist');
        }

        $user = User::findOrFail($id);
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email_id = $request->input('email_id');
        $user->phone_number = $request->input('phone_number');
        $user->whatsapp_number = $request->input('whatsapp_number');
        $user->is_crm_access = $request->input('crm_access_value') ?? 0;

        if ($user->save()) {
            UserRole::where('user_id', $user->id)->update(['role_id' => $request->input('role_id')]);
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'User has been updated successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function lockUnlockUser(Request $request, $id, $statusId)
    {
        $id = Helper::userId($id);
        if (!$id) {
            abort(404);
        }

        $user = User::find($id);
        if (empty($user)) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Server Error!')
                ->with('message_text', 'Something Went Wrong!');
        }
        $user->status = $statusId;
        if ($user->save()) {
            $msg = ($statusId == 1) ? 'User has been activated successfully' : 'User has been deactivated successfully';
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', $msg);
        }
        return back();
    }

    public function profileInformation(Request $request, $id)
    {
        $id = Helper::userId($id);
        if (!$id) {
            abort(404);
        }

        $dataBag = [];
        $dataBag['sidebar_parent'] = 'user_management';
        $dataBag['sidebar_child'] = 'users';
        $dataBag['user'] = User::with(['userRoles', 'userProfile'])->findOrFail($id);
        $dataBag['roles'] = Roles::where('status', 1)->orderBy('display_order', 'asc')->get();

        //dd(Route::current()->getName());
        if (Route::currentRouteName() == 'user.resetPassword') {
            return view('backend.user.reset_user_password', $dataBag);
        }

        if (Route::currentRouteName() == 'user.resetUsername') {
            return view('backend.user.reset_username', $dataBag);
        }

        return view('backend.user.additional_information', $dataBag);
    }

    public function saveProfileInformation(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $isDataExist = UserProfile::where('user_id', $id)->first();

        if (!empty($isDataExist)) {
            $obj = UserProfile::findOrFail($isDataExist->id);
        } else {
            $obj = new UserProfile();
        }

        $obj->user_id = $id;
        $obj->full_address = $request->input('full_address') ?? null;
        $obj->geo_address = $request->input('geo_address') ?? null;
        $obj->longitude = $request->input('longitude') ?? null;
        $obj->latitude = $request->input('latitude') ?? null;
        $obj->city = $request->input('city') ?? null;
        $obj->pincode = $request->input('pincode') ?? null;
        $obj->state = $request->input('state') ?? null;
        $obj->country = $request->input('country') ?? null;
        $obj->land_mark = $request->input('land_mark') ?? null;

        if ($request->hasFile('image') && !empty($request->file('image'))) {
            $image = $request->file('image');
            $realPath = $image->getRealPath();
            $orgName = $image->getClientOriginalName();
            $size = $image->getSize();
            $ext = strtolower($image->getClientOriginalExtension());
            $newName = 'profile_image_' . '_' . time() . '.' . $ext;
            $destinationPath = public_path('/uploads/images/users/');
            $thumbPath = $destinationPath . 'thumbnail';

            $imgObj = Image::make($realPath);
            $imgObj->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbPath . '/' . $newName);

            $image->move($destinationPath, $newName);
            $obj->image = $newName;
        }

        if ($obj->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'User profile has been saved successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function resetPassword(Request $request, $id)
    {
        $id = Helper::userId($id);
        if (!$id) {
            abort(404);
        }

        $user = User::findOrFail($id);
        $user->password = Hash::make($request->input('password')) ?? null;
        if ($user->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'User password has been reset successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function resetUsername(Request $request, $id)
    {
        $id = Helper::userId($id);
        if (!$id) {
            abort(404);
        }

        $user = User::findOrFail($id);
        $user->user_name = $request->input('user_name') ?? null;
        if ($user->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Username has been updated successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function myProfile(Request $request)
    {
        $dataBag = [];
        $dataBag['user'] = Auth::user();
        return view('backend.user.profile', $dataBag);
    }

    public function myProfileUpdate(Request $request)
    {
        $id = Auth::user()->id;
        $emailId = $request->input('email_id');
        $phoneNumber = $request->input('phone_number');
        $userName = $request->input('user_name');

        $checkEmail = User::where('email_id', $emailId)->whereNotNull('email_id')->where('id', '!=', $id)->exists();
        $checkPhone = User::where('phone_number', $phoneNumber)->whereNotNull('phone_number')->where('id', '!=', $id)->exists();
        $checkUserName = User::where('user_name', $userName)->whereNotNull('user_name')->where('id', '!=', $id)->exists();

        if ($checkEmail) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Email id already exist');
        }
        if ($checkPhone) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Phone number already exist');
        }
        if ($checkUserName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Username already exist');
        }

        $user = User::findOrFail($id);
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email_id = $request->input('email_id');
        $user->phone_number = $request->input('phone_number');
        $user->whatsapp_number = $request->input('whatsapp_number');
        $user->user_name = $request->input('user_name');
        if ($user->save()) {
            $isDataExist = UserProfile::where('user_id', $user->id)->first();
            if (!empty($isDataExist)) {
                $obj = UserProfile::findOrFail($isDataExist->id);
            } else {
                $obj = new UserProfile();
            }
            $obj->user_id = $user->id;
            $obj->full_address = $request->input('full_address');
            $obj->geo_address = $request->input('geo_address');
            $obj->longitude = $request->input('longitude');
            $obj->latitude = $request->input('latitude');
            $obj->city = $request->input('city');
            $obj->pincode = $request->input('pincode');
            $obj->state = $request->input('state');
            $obj->country = $request->input('country');
            $obj->land_mark = $request->input('land_mark');

            if ($request->hasFile('image') && !empty($request->file('image'))) {
                $image = $request->file('image');
                $realPath = $image->getRealPath();
                $orgName = $image->getClientOriginalName();
                $size = $image->getSize();
                $ext = strtolower($image->getClientOriginalExtension());
                $newName = 'profile_image_' . '_' . time() . '.' . $ext;
                $destinationPath = public_path('/uploads/images/users/');
                $thumbPath = $destinationPath . 'thumbnail';

                $imgObj = Image::make($realPath);
                $imgObj->resize(150, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumbPath . '/' . $newName);

                $image->move($destinationPath, $newName);
                $obj->image = $newName;
            }
            if ($obj->save()) {
                return redirect()->back()
                    ->with('message_type', 'success')
                    ->with('message_title', 'Done!')
                    ->with('message_text', 'Your profile has been updated successfully');
            }
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function changePassword(Request $request)
    {
        $dataBag = [];
        $dataBag['user'] = Auth::user();
        return view('backend.user.profile_password', $dataBag);
    }

    public function changePasswordUpdate(Request $request)
    {
        $id = Auth::user()->id;
        $user = User::findOrFail($id);
        $user->password = Hash::make($request->input('password')) ?? null;
        if ($user->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Your password has been updated successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }
}
