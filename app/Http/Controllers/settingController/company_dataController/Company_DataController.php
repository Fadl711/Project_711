<?php

namespace App\Http\Controllers\settingController\company_dataController;

use App\Http\Controllers\Controller;
use App\Models\BusinessData;
use Illuminate\Http\Request;

class Company_DataController extends Controller
{
    //
    public function create(){
        return view('settings.company_data.create');
    }
    public function store(Request $request){

        $request->validate(
            [
                'com_photo'=>'required|image|mimes:jpeg,png,jpg|
                max:2048',
            ]);
        $imageName = time().'.'. $request->com_photo->extension();
        $request->com_photo->move(public_path('images'),$imageName);


        BusinessData::create([
            'Company_Logo'=>$imageName,
            'Company_Name'=>$request->com_name,
            'Company_NameE'=>$request->com_nameE,
            'Phone_Number'=>$request->com_phones,
            'Services'=>$request->com_for,
            'ServicesE'=>$request->com_forE,
            'Company_Address'=>$request->com_address,
            'Company_AddressE'=>$request->com_addressE,
        ]);
        return back()->with(['scuccss'=>"تمت العملية بنجاح"]);
    }
}
