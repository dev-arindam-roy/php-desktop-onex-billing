<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductVariants;
use DNS1D;

class BarcodeController extends Controller
{
    protected static $defaultType = 'C39+';
    protected static $defaultColor = 'black';
    protected static $defaultSize = 2;

    public function __construct()
    {
        
    }

    public function productIndex(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'barcode_management';
        $dataBag['sidebar_child'] = 'product-barcode';
        $dataBag['barcode'] = null;

        if (!empty($request->get('code'))) {
            $code = $request->get('code');
            $type = !empty($request->get('type')) ? $request->get('type') : self::$defaultType;
            $color = !empty($request->get('color')) ? $request->get('color') : self::$defaultColor;
            $size = !empty($request->get('size')) ? $request->get('size') : self::$defaultSize;
            $dataBag['barcode'] = self::createBarcodeSVG($code, $type, $color, $size);
        }

        $allProducts = ProductVariants::select('id','name','barcode_no', 'sku')
            ->where('status', '!=', 3)
            ->orderBy('name', 'asc')
            ->get();
        
        //$dataBag['types'] = self::$defaultType;
        $dataBag['colors'] = $this->colors();
        $dataBag['sizes'] = $this->sizes();
        $dataBag['products'] = $allProducts;

        return view('backend.barcode.product-barcode', $dataBag);
    }

    public static function createBarcodeSVG($code, $type, $color, $size)
    {
        $type = !empty($type) ? $type : self::$defaultType;
        $color = !empty($color) ? $color : self::$defaultColor;
        $size = !empty($size) ? $size : self::$defaultSize;
        return DNS1D::getBarcodeSVG($code, $type, $size, 45, $color, true);
    }

    public static function createBarcodeSHTML($code, $type, $color, $size)
    {
        $type = !empty($type) ? $type : self::$defaultType;
        $color = !empty($color) ? $color : self::$defaultColor;
        $size = !empty($size) ? $size : self::$defaultSize;
        return DNS1D::getBarcodeHTML($code, $type, $size, 45, $color, true);
    }

    public function downloadBarcodeSV(Request $request)
    {
        if (!empty($request->get('code'))) {
            $code = $request->get('code');
            $type = !empty($request->get('type')) ? $request->get('type') : self::$defaultType;
            $color = !empty($request->get('color')) ? $request->get('color') : self::$defaultColor;
            $size = !empty($request->get('size')) ? $request->get('size') : self::$defaultSize;
            $svg = self::createBarcodeSVG($code, $type, $color, $size);
            $fileName = $code . '.svg';
            $saveInStorage = file_put_contents(storage_path($fileName), $svg);
            if (file_exists(storage_path($fileName))) {
                return response()->download(storage_path($fileName), $fileName, array('Content-type' => 'image/svg+xml'));
            }
            return back();
        }
        return back();
    }

    public function barcodeSvgRender(Request $request)
    {
        if (!empty($request->get('code'))) {
            $code = $request->get('code');
            $type = !empty($request->get('type')) ? $request->get('type') : self::$defaultType;
            $color = !empty($request->get('color')) ? $request->get('color') : self::$defaultColor;
            $size = !empty($request->get('size')) ? $request->get('size') : self::$defaultSize;
            $svg = self::createBarcodeSVG($code, $type, $color, $size);
            return response($svg, 200, array('Content-type' => 'image/svg+xml'));
        }
        return back();
    }

    public function categories() {
        return [
            'C39',
            'C39+',
            'C39E',
            'C39E+',
            'C93',
            'S25',
            'S25+',
            'I25',
            'I25+',
            'C128',
            'C128A',
            'C128B',
            'C128C',
            'UPCA',
            'UPCE',
            'MSI',
            'MSI+',
            'CODABAR',
            'CODE11'
        ];
    }

    public function colors() {
        return [
            'black',
            'blue',
            'red',
            'orange',
            'green',
            'yellow'
        ];
    }

    public function sizes() {
        return array(
            2 => 'Normal',
            3 => 'Medium',
            4 => 'Large',
            5 => 'Extra Large'
        );
    }
}
