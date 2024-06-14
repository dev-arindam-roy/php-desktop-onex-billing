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

class CustomerController extends Controller
{

    protected $userCategory;

    public function __construct()
    {
        $this->userCategory = 5;
    }

    public function index(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'customer_management';
        $dataBag['sidebar_child'] = 'all-customers';
        $authId = Auth::user()->id;
        $pagination = !empty($request->get('pagination')) ? $request->get('pagination') : 25; 
        $dataBag['data'] = User::with(['userRoles', 'userProfile'])
            ->where('id', '!=', $authId)
            ->where('status', '!=', 3)
            ->where('user_category', $this->userCategory)
            ->orderBy('id', 'desc')
            ->paginate($pagination);

        if ($request->ajax()) {
            return view('backend.customer.all-customers-render', $dataBag);
        }

        return view('backend.customer.index', $dataBag);
    }

    public function add(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'customer_management';
        $dataBag['sidebar_child'] = 'add-customer';

        return view('backend.customer.add', $dataBag);
    }

    public function save(Request $request)
    {
        $emailId = $request->input('email_id');
        $phoneNumber = $request->input('phone_number');

        $checkEmail = User::where('email_id', $emailId)->whereNotNull('email_id')->exists();
        $checkPhone = User::where('phone_number', $phoneNumber)->whereNotNull('phone_number')->exists();

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

        $user = new User();
        $user->hash_id = Str::uuid(36)->toString();
        $user->unique_id = Helper::userUniqueId();
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email_id = $request->input('email_id');
        $user->phone_number = $request->input('phone_number');
        $user->whatsapp_number = $request->input('whatsapp_number');
        $user->is_crm_access = $request->input('crm_access_value') ?? 0;
        $user->user_category = $this->userCategory;
        $user->status = 1;
        if ($user->save()) {
            self::saveOtherInformation($request, $user->id);
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'New customer has been created successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function delete(Request $request, $id)
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
            ->with('message_text', 'Customer has been deleted successfully');
    }

    public function edit(Request $request, $id)
    {
        $id = Helper::userId($id);
        if (!$id) {
            abort(404);
        }

        $dataBag = [];
        $dataBag['sidebar_parent'] = 'customer_management';
        $dataBag['sidebar_child'] = 'add-customer';
        $dataBag['user'] = User::with(['userRoles', 'userProfile'])->findOrFail($id);

        return view('backend.customer.edit', $dataBag);
    }

    public function update(Request $request, $id)
    {
        $emailId = $request->input('email_id');
        $phoneNumber = $request->input('phone_number');

        $checkEmail = User::where('email_id', $emailId)->whereNotNull('email_id')->where('id', '!=', $id)->exists();
        $checkPhone = User::where('phone_number', $phoneNumber)->whereNotNull('phone_number')->where('id', '!=', $id)->exists();

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

        $user = User::findOrFail($id);
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email_id = $request->input('email_id');
        $user->phone_number = $request->input('phone_number');
        $user->whatsapp_number = $request->input('whatsapp_number');
        $user->is_crm_access = $request->input('crm_access_value') ?? 0;

        if ($user->save()) {
            self::saveOtherInformation($request, $user->id);
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Customer has been updated successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public static function saveOtherInformation($request, $userId)
    {
        $isDataExist = UserProfile::where('user_id', $userId)->first();

        if (!empty($isDataExist)) {
            $obj = UserProfile::findOrFail($isDataExist->id);
        } else {
            $obj = new UserProfile();
        }

        $obj->user_id = $userId;
        $obj->full_address = $request->input('full_address') ?? null;
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
        $obj->save();
    }
}
