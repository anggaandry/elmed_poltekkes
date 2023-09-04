<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\Menu;
use App\Models\University;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class ConfigController extends Controller
{
    private $menu_id;

    public function __construct()
    {
        $this->menu_id = 25;
    }

    public function index()
    {
        $config_data=University::where('id',UNIVERSITY_ID)->first();
        $data=[
            "config_data"=>$config_data
        ];

        return view('admin/config', $data);
    }

    public function update(Request $request)
    {
        $id = $request->input("id");
        $name = $request->input("name");
        $address=$request->input("address");
        $lon=$request->input("lon");
        $lat=$request->input("lat");
        $type=$request->input("type");
        $email=$request->input("email");
        $phone=$request->input("phone");
       

        $update=[
            "name" => $name,
            "address"=>$address,
            "lon"=>$lon,
            "lat"=>$lat,
            "type"=>$type,
            "email"=>$email,
            "phone"=>$phone,
        ];


        $logo = null;
        if ($request->logo) {
            $logo = time() . '-logo.' . $request->logo->extension();
            $request->logo->move(public_path(LOGO_PATH), $logo);
        }
        if($request->logo){
            $update['logo']=$logo;
        }

       
        $status_data = University::where('id',$id)->update($update);
        
        if ($status_data) {
            addLog(0,$this->menu_id,'Mengupdate data konfigurasi kampus');
            return redirect()->back()->with('success', 'sukses mengedit data kampus');
        } else {
            return redirect()->back()->with('failed', 'gagal mengedit data kampus');
        }
    }



}