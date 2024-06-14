<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyCategory;
use App\Models\CompanyInformation;
use DB;

class CompanyController extends Controller
{

    public function __construct()
    {

    }

    public function index(Request $request)
    {
        $dataBag = [];
        $dataBag['sidebar_parent'] = 'settings';
        $dataBag['sidebar_child'] = 'company';
        $dataBag['company_categories'] = CompanyCategory::all();
        $dataBag['company_information'] = CompanyInformation::first();
        return view('backend.company.company-registration', $dataBag);
    }

    public function saveChanges(Request $request)
    {
        $companyInformation = CompanyInformation::first();
        if (empty($companyInformation)) {
            $companyInformation = new CompanyInformation();
        } else {
            $companyInformation = CompanyInformation::find($companyInformation->id);
        }
        $companyInformation->company_name = $request->input('company_name');
        $companyInformation->company_type = $request->input('company_type') ?? null;
        $companyInformation->brand_name = $request->input('brand_name') ?? null;
        $companyInformation->gst_no = $request->input('gst_no') ?? null;
        $companyInformation->vat_no = $request->input('vat_no') ?? null;
        $companyInformation->cin_no = $request->input('cin_no') ?? null;
        $companyInformation->tan_no = $request->input('tan_no') ?? null;
        $companyInformation->pan_no = $request->input('pan_no') ?? null;
        $companyInformation->contact_number = $request->input('contact_number') ?? null;
        $companyInformation->contact_email = $request->input('contact_email') ?? null;
        $companyInformation->whatsapp_number = $request->input('whatsapp_number') ?? null;
        $companyInformation->website_url = $request->input('website_url') ?? null;
        $companyInformation->full_address = $request->input('full_address') ?? null;
        $companyInformation->state = $request->input('state') ?? null;
        $companyInformation->city = $request->input('city') ?? null;
        $companyInformation->pincode = $request->input('pincode') ?? null;
        $companyInformation->land_mark = $request->input('land_mark') ?? null;
        $companyInformation->country = $request->input('country') ?? 'IND';
        if ($companyInformation->save()) {
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Company information has been saved successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }

    public function deleteInformation(Request $request)
    {
        $companyInformation = CompanyInformation::findOrFail($request->input('company_id'));
        if (!empty($companyInformation)) {
            $companyInformation->delete();
            return redirect()->back()
                ->with('message_type', 'success')
                ->with('message_title', 'Done!')
                ->with('message_text', 'Company information has been deleted successfully');
        }
        return back()
            ->with('message_type', 'error')
            ->with('message_title', 'Server Error!')
            ->with('message_text', 'Something Went Wrong!');
    }
}
