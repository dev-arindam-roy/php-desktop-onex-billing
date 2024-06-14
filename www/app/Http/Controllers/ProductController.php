<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Products;
use App\Models\User;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\ProductCategories;
use App\Models\ProductSubCategories;
use App\Models\ProductVariants;
use App\Models\ProductBundleFree;
use Session;
use Helper;
use Image;
use Hash;
use Auth;
use DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'products';
        
        $dataBag['all_products'] = Products::with(['productCategory', 'productSubCategory'])
            ->where('status', '!=', 3)
            ->orderBy('id', 'desc')
            ->get();
        
        return view('backend.product.index', $dataBag);
    }

    public function addProduct(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'products';
        $dataBag['all_categories'] = ProductCategories::where('status', '!=', 3)->orderBy('name', 'asc')->get();
        return view('backend.product.add', $dataBag);
    }

    public function saveProduct(Request $request)
    {
        $name = $request->input('name');
        $checkName = Products::where('name', $name)
            ->where('status', '!=', 3)
            ->exists();

        if ($checkName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Product name is already exist');
        }

        $products = new Products();
        $products->name = $request->input('name');
        $products->status = $request->input('status');
        $products->category_id = $request->input('category_id');
        $products->subcategory_id = $request->input('subcategory_id');
        $products->description = !empty($request->input('description')) ? htmlentities(trim($request->input('description')), ENT_QUOTES ) : NULL;
        if ($request->hasFile('image') && !empty($request->file('image'))) {
            $image = $request->file('image');
            $realPath = $image->getRealPath();
            $orgName = $image->getClientOriginalName();
            $size = $image->getSize();
            $ext = strtolower($image->getClientOriginalExtension());
            $newName = 'product_image_' . '_' . time() . '.' . $ext;
            $destinationPath = public_path('/uploads/images/products/');
            $thumbPath = $destinationPath . 'thumbnail'; 
            
            $imgObj = Image::make($realPath);
            $imgObj->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbPath . '/' . $newName);

            $image->move($destinationPath, $newName);
            $products->image = $newName;
        }
        if ($products->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'New product has been created successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function deleteProduct(Request $request, $id)
    {
        $products = Products::findOrFail($id);
        $products->status = 3;
        $products->save();
        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'Product has been deleted successfully');
    }

    public function editProduct(Request $request, $id)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'products';
        $product = Products::findOrFail($id);
        $dataBag['product'] = $product;
        $dataBag['all_categories'] = ProductCategories::where('status', '!=', 3)->orderBy('name', 'asc')->get();
        $dataBag['selected_subcategories'] = ProductSubCategories::where('status', '!=', 3)
            ->where('category_id', $product->category_id)
            ->orderBy('name', 'asc')
            ->get();
        return view('backend.product.edit', $dataBag);
    }

    public function updateProduct(Request $request, $id)
    {
        $name = $request->input('name');
        $checkName = Products::where('name', $name)
            ->where('status', '!=', 3)
            ->where('id', '!=', $id)
            ->exists();

        if ($checkName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Product name is already exist');
        }

        $products = Products::findOrFail($id);
        $products->name = $request->input('name');
        $products->status = $request->input('status');
        $products->category_id = $request->input('category_id');
        $products->subcategory_id = $request->input('subcategory_id');
        $products->description = !empty($request->input('description')) ? htmlentities(trim($request->input('description')), ENT_QUOTES ) : NULL;
        if ($request->hasFile('image') && !empty($request->file('image'))) {
            $image = $request->file('image');
            $realPath = $image->getRealPath();
            $orgName = $image->getClientOriginalName();
            $size = $image->getSize();
            $ext = strtolower($image->getClientOriginalExtension());
            $newName = 'product_image_' . '_' . time() . '.' . $ext;
            $destinationPath = public_path('/uploads/images/products/');
            $thumbPath = $destinationPath . 'thumbnail'; 
            
            $imgObj = Image::make($realPath);
            $imgObj->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumbPath . '/' . $newName);

            $image->move($destinationPath, $newName);
            $products->image = $newName;
        }
        if ($products->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Product has been updated successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    /** Categories */

    public function allCategories(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'categories';
        $dataBag['all_data'] = ProductCategories::where('status', '!=', 3)->orderBy('id', 'desc')->get();
        return view('backend.product.categories', $dataBag);
    }

    public function addCategory(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'categories';
        return view('backend.product.add-category', $dataBag);
    }

    public function saveCategory(Request $request)
    {
        $name = $request->input('name');
        $checkName = ProductCategories::where('name', $name)->where('status', '!=', 3)->exists();

        if ($checkName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Product category name is already exist');
        }

        $obj = new ProductCategories();
        $obj->name = $request->input('name');
        $obj->description = $request->input('description');
        $obj->status = $request->input('status');
        $obj->primary_visibility = 0;
        $obj->menu_visibility = 0;
        $obj->display_order = NULL;
        if ($request->has('primary_visibility') && !empty($request->input('primary_visibility'))) {
            $obj->primary_visibility = $request->input('primary_visibility');
        }
        if ($request->has('menu_visibility') && !empty($request->input('menu_visibility'))) {
            $obj->menu_visibility = $request->input('menu_visibility');
        }
        if ($request->has('display_order')) {
            $obj->display_order = $request->input('display_order');
        }
        if ($obj->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'New product category has been created successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function deleteCategory(Request $request, $id)
    {
        $obj = ProductCategories::findOrFail($id);
        $obj->status = 3;
        $obj->save();
        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'Product category has been deleted successfully');
    }

    public function editCategory(Request $request, $id)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'categories';
        $obj = ProductCategories::findOrFail($id);
        $dataBag['data'] = $obj;
        return view('backend.product.edit-category', $dataBag);
    }

    public function updateCategory(Request $request, $id)
    {
        $name = $request->input('name');
        $checkName = ProductCategories::where('name', $name)->where('id', '!=', $id)->where('status', '!=', 3)->exists();

        if ($checkName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Product category name is already exist');
        }

        $obj = ProductCategories::findOrFail($id);
        $obj->name = $request->input('name');
        $obj->description = $request->input('description');
        $obj->status = $request->input('status');
        $obj->primary_visibility = 0;
        $obj->menu_visibility = 0;
        $obj->display_order = NULL;
        if ($request->has('primary_visibility') && !empty($request->input('primary_visibility'))) {
            $obj->primary_visibility = $request->input('primary_visibility');
        }
        if ($request->has('menu_visibility') && !empty($request->input('menu_visibility'))) {
            $obj->menu_visibility = $request->input('menu_visibility');
        }
        if ($request->has('display_order')) {
            $obj->display_order = $request->input('display_order');
        }
        if ($obj->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Product category has been updated successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    /** Sub Categories */

    public function allSubCategories(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'subcategories';
        $dataBag['all_data'] = ProductSubCategories::with(['category'])->where('status', '!=', 3)->orderBy('id', 'desc')->get();
        return view('backend.product.subcategories', $dataBag);
    }

    public function addSubCategory(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'subcategories';
        $dataBag['all_categories'] = ProductCategories::where('status', '!=', 3)->orderBy('id', 'desc')->get();
        return view('backend.product.add-subcategory', $dataBag);
    }

    public function saveSubCategory(Request $request)
    {
        $name = $request->input('name');
        $checkName = ProductSubCategories::where('name', $name)->where('status', '!=', 3)->exists();

        if ($checkName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Product sub-category name is already exist');
        }

        $obj = new ProductSubCategories();
        $obj->category_id = $request->input('category_id');
        $obj->name = $request->input('name');
        $obj->description = $request->input('description');
        $obj->status = $request->input('status');
        if ($obj->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'New product sub-category has been created successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function deleteSubCategory(Request $request, $id)
    {
        $obj = ProductSubCategories::findOrFail($id);
        $obj->status = 3;
        $obj->save();
        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'Product sub-category has been deleted successfully');
    }

    public function editSubCategory(Request $request, $id)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'subcategories';
        $obj = ProductSubCategories::findOrFail($id);
        $dataBag['data'] = $obj;
        $dataBag['all_categories'] = ProductCategories::where('status', '!=', 3)->orderBy('id', 'desc')->get();
        return view('backend.product.edit-subcategory', $dataBag);
    }

    public function updateSubCategory(Request $request, $id)
    {
        $name = $request->input('name');
        $checkName = ProductSubCategories::where('name', $name)->where('id', '!=', $id)->where('status', '!=', 3)->exists();

        if ($checkName) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Product sub-category name is already exist');
        }

        $obj = ProductSubCategories::findOrFail($id);
        $obj->category_id = $request->input('category_id');
        $obj->name = $request->input('name');
        $obj->description = $request->input('description');
        $obj->status = $request->input('status');
        if ($obj->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Product sub-category has been updated successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    /** Ajax Call */

    public function getAllCategories(Request $request)
    {
        $dataBag = [];
        $categoryId = $request->input('category_id');
        
        $dataBag['all_subcategories'] = ProductSubCategories::where('category_id', $categoryId)
            ->where('status', '!=', 3)
            ->orderBy('name', 'asc')
            ->get();

        $dataBag['is_success'] = true;
        return response()->json($dataBag);
    }

    /** Product Variants */

    public function allVariants(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'productvariants';
        $pagination = !empty($request->get('pagination')) ? $request->get('pagination') : 25; 
        
        $searchProductName = ($request->has('variant_name') && !empty($request->get('variant_name'))) ? $request->get('variant_name') : null; 
        $dataBag['data'] = ProductVariants::with(['baseProduct', 'productUnit', 'productBrand'])
            ->when(!empty($searchProductName), function ($query) use ($searchProductName) {
                return $query->where('name', 'like', '%' . $searchProductName . '%');
            })
            ->where('status', '!=', 3)
            ->orderBy('id', 'desc')
            ->paginate($pagination);
        
        if ($request->ajax()) {
            return view('backend.product.all-variants-render', $dataBag);
        }
        
        return view('backend.product.all-variants', $dataBag);
    }

    public function addVariants(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'productvariants';
        $dataBag['all_products'] = Products::where('status', '!=', 3)->orderBy('name', 'asc')->get();
        $dataBag['all_units'] = Unit::where('status', '!=', 3)->orderBy('name', 'asc')->get();
        $dataBag['all_brands'] = Brand::where('status', '!=', 3)->orderBy('name', 'asc')->get();
        return view('backend.product.add-variant', $dataBag);
    }

    public function saveVariants(Request $request)
    {
        $sku = strtoupper(str_replace(' ', '-', $request->input('sku')));
        $checkSku = ProductVariants::where('sku', $sku)->where('status', '!=', 3)->exists();

        if ($checkSku) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Product sku is already exist');
        }

        $obj = new ProductVariants();
        $obj->sku = $sku;
        $obj->barcode_no = Helper::createProductBarcodeNo(); 
        $obj->product_id = $request->input('product_id');
        $obj->name = $request->input('name');
        $obj->description = !empty($request->input('description')) ? htmlentities(trim($request->input('description')), ENT_QUOTES ) : NULL;
        $obj->status = $request->input('status');
        $obj->unit_id = $request->input('unit_id');
        $obj->brand_id = $request->input('brand_id');
        $obj->price = $request->input('price') ?? 0;
        $obj->old_price = $request->input('old_price') ?? 0;
        $obj->percentage_discount = $request->input('percentage_discount') ?? 0;
        $obj->flat_discount = $request->input('flat_discount') ?? 0;
        $obj->offer_text = $request->input('offer_text');
        $obj->hsn_code = $request->input('hsn_code') ?? NULL;
        $obj->gst_rate = $request->input('gst_rate') ?? 0;
        if ($request->hasFile('image') && !empty($request->file('image'))) {
            $image = $request->file('image');
            $realPath = $image->getRealPath();
            $orgName = $image->getClientOriginalName();
            $size = $image->getSize();
            $ext = strtolower($image->getClientOriginalExtension());
            $newName = 'product_image_' . '_' . time() . '.' . $ext;
            $destinationPath = public_path('/uploads/images/products/');
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
                ->with('message_text', 'Product variant has been created successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function deleteVariants(Request $request, $id)
    {
        $obj = ProductVariants::findOrFail($id);
        $obj->status = 3;
        $obj->save();
        return redirect()->back()
            ->with('message_type', 'success')
            ->with('message_title', 'Done!')
            ->with('message_text', 'Product variant has been deleted successfully');
    }

    public function editVariants(Request $request, $id)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'productvariants';
        $product = ProductVariants::findOrFail($id);
        $dataBag['product'] = $product;
        $dataBag['all_products'] = Products::where('status', '!=', 3)->orderBy('name', 'asc')->get();
        $dataBag['all_units'] = Unit::where('status', '!=', 3)->orderBy('name', 'asc')->get();
        $dataBag['all_brands'] = Brand::where('status', '!=', 3)->orderBy('name', 'asc')->get();
        return view('backend.product.edit-variant', $dataBag);
    }

    public function updateVariants(Request $request, $id)
    {
        $sku = strtoupper(str_replace(' ', '-', $request->input('sku')));
        $checkSku = ProductVariants::where('sku', $sku)->where('id', '!=', $id)->where('status', '!=', 3)->exists();

        if ($checkSku) {
            return back()
                ->with('message_type', 'error')
                ->with('message_title', 'Sorry!')
                ->with('message_text', 'Product sku is already exist');
        }

        $obj = ProductVariants::findOrFail($id);
        $obj->sku = $sku;
        $obj->product_id = $request->input('product_id');
        $obj->name = $request->input('name');
        $obj->description = !empty($request->input('description')) ? htmlentities(trim($request->input('description')), ENT_QUOTES ) : NULL;
        $obj->status = $request->input('status');
        $obj->unit_id = $request->input('unit_id');
        $obj->brand_id = $request->input('brand_id');
        $obj->price = $request->input('price') ?? 0;
        $obj->old_price = $request->input('old_price') ?? 0;
        $obj->percentage_discount = $request->input('percentage_discount') ?? 0;
        $obj->flat_discount = $request->input('flat_discount') ?? 0;
        $obj->offer_text = $request->input('offer_text');
        $obj->hsn_code = $request->input('hsn_code') ?? NULL;
        $obj->gst_rate = $request->input('gst_rate') ?? 0;
        if ($request->hasFile('image') && !empty($request->file('image'))) {
            $image = $request->file('image');
            $realPath = $image->getRealPath();
            $orgName = $image->getClientOriginalName();
            $size = $image->getSize();
            $ext = strtolower($image->getClientOriginalExtension());
            $newName = 'product_image_' . '_' . time() . '.' . $ext;
            $destinationPath = public_path('/uploads/images/products/');
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
                ->with('message_text', 'Product variant has been updated successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    /** Bundle */

    public function allVariantBundleProducts(Request $request, $id)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'productvariants';
        return view('backend.product.variant-bundle', $dataBag);
    }

    public function allVariantFreeProducts(Request $request, $id)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'product_management';
        $dataBag['sidebar_child'] = 'productvariants';
        return view('backend.product.variant-free', $dataBag);
    }
}
