<?php

namespace App\Http\Controllers\settingController\company_dataController;

use App\Http\Controllers\Controller;
use App\Models\BusinessData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class Company_DataController extends Controller
{
    //
    public function create(){
        $buss=BusinessData::first();
        return view('settings.company_data.create',compact('buss'));
    }
    public function store(Request $request){
        $businessData=BusinessData::first();
        if ($request->hasFile('com_photo')) {
            $imageName = time().'.'. $request->com_photo->extension();
            $request->com_photo->move(public_path('images'),$imageName);
        } else if(isset($businessData->Company_Logo)) {
            $imageName = $businessData->Company_Logo;

        }
        else{
            $imageName = null;
        }

BusinessData::updateOrCreate(
    ['business_data_id' => 1], // condition to update or create
    [
        'Company_Logo' => $imageName,
        'Company_Name' => $request->com_name,
        'Company_NameE' => $request->com_nameE,
        'Phone_Number' => $request->com_phones,
        'Services' => $request->com_for,
        'ServicesE' => $request->com_forE,
        'Company_Address' => $request->com_address,
        'Company_AddressE' => $request->com_addressE,
    ]
);
    return Redirect::back()->withInput();    }
}
