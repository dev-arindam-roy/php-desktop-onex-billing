<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Cart;
use Auth;
use File;
use DB;

class Helper {

    public static function userInfo($userId) 
    {
        return User::where('id', $userId)->first();
    }

    public static function userId($hashId)
    {
        $user = User::where('hash_id', $hashId)->first();
        return !empty($user) ? $user->id : null;
    }

    public static function userRoles($userId) 
    {
        $userRoleKeys = [];
        $user = User::with(['userRoles'])->where('id', $userId)->first();
        if (!empty($user) && !empty($user->userRoles) && count($user->userRoles)) {
            foreach ($user->userRoles as $eachRole) {
                if (!empty($eachRole->role) && !empty($eachRole->role->key_name)) {
                    array_push($userRoleKeys, $eachRole->role->key_name);    
                }
            }
        }
        return $userRoleKeys;
    }

    public static function authRoles()
    {
        if (!Auth::check()) {
            return array();
        }
        $userRoles = [];
        if (!empty(Auth::user()->userRoles)) {
            foreach (Auth::user()->userRoles as $eachRole) {
                if (!empty($eachRole->role) && !empty($eachRole->role->key_name)) {
                    array_push($userRoles, $eachRole->role->key_name);    
                }
            }
        }
        return $userRoles;
    }

    public static function canAccess($rolesArr = [])
    {
        if (!is_array($rolesArr) || empty($rolesArr)) {
            return false;
        }
        $loggedInUserRoles = [];
        if (!empty(Auth::user()->userRoles)) {
            foreach (Auth::user()->userRoles as $eachRole) {
                if (!empty($eachRole->role) && !empty($eachRole->role->key_name)) {
                    array_push($loggedInUserRoles, $eachRole->role->key_name);    
                }
            }
        }
        $isAccessGranted = false;
        foreach ($loggedInUserRoles as $v) {
            if (in_array($v, $rolesArr)) {
                $isAccessGranted = true;
            }
        }
        return ($isAccessGranted) ? true : false;
    }

    public static function userUniqueId()
    {
        $maxId = DB::table('users')->max('id');
        $maxId = $maxId + rand(111, 999);
        $str = str_pad($maxId, 8, 0, STR_PAD_LEFT);
        return rand(11, 99) . $str; 
    }

    public function generateRandomString($length = 10) 
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return strtoupper(rand(11, 99) . $randomString . rand(1111, 9999));
    }

    public static function unlinkFiles($tabName, $imageName)
    {
        $productAssets = array('product_master', 'product_variants', 'product_images', 'product_category', 'product_subcategory');
        if (in_array($tabName, $productAssets)) {
            if (File::exists(public_path('uploads/images/products/' . $imageName))) {
                File::delete(public_path('uploads/images/products/' . $imageName));
            }
            if (File::exists(public_path('uploads/images/products/thumbnail/' . $imageName))) {
                File::delete(public_path('uploads/images/products/thumbnail/' . $imageName));
            }
        }
        $userAssets = array('users', 'users_profile');
        if (in_array($tabName, $userAssets)) {
            if (File::exists(public_path('uploads/images/users/' . $imageName))) {
                File::delete(public_path('uploads/images/users/' . $imageName));
            }
            if (File::exists(public_path('uploads/images/users/thumbnail/' . $imageName))) {
                File::delete(public_path('uploads/images/users/thumbnail/' . $imageName));
            }
        }
    }

    public static function spellNumber($number = 0)
    {
        if (empty($number)) {
            return null;
        }
        if ($number <= 0) {
            return 'Zero';
        }
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array(
            '0' => '', 
            '1' => 'one', 
            '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety'
        );
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } 
            else 
                $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ? "." . $words[$point / 10] . " " . $words[$point = $point % 10] : '';
        $result = $result . "Rupees "; 
        $points = ((!empty($points) && $points > 0)) ? "and " . $points . " Paise" : '';
        return $result . $points;
    }

    public static function createProductBarcodeNo()
    {
        $maxId = DB::table('product_variants')->max('id');
        $maxId = $maxId + rand(111, 999);
        return 'PU' . str_pad($maxId, 10, 0, STR_PAD_LEFT);
    }

    public static function createBatchNo()
    {
        $maxId = DB::table('batches')->max('id');
        $maxId = $maxId + rand(11, 99);
        return 'BC' . date('Ym') . '-' . str_pad($maxId, 6, 0, STR_PAD_LEFT);
    }

}