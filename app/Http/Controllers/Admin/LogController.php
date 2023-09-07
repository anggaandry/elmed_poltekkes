<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Log;
use App\Models\Menu;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class LogController extends Controller
{
    private $menu_id;

    public function __construct()
    {
        $this->menu_id = 24;
    }

    public function index()
    {
        $menu_data=Menu::orderBy('sort','ASC')->get();
        $data=[
            "menu_data"=>$menu_data
        ];

        return view('admin/log', $data);
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $start= $request->input("start_date");
            $end= $request->input("end_date");
            $menu_id=$request->input("menu_id")?$request->input("menu_id"):"";
            $type=$request->input("type")?$request->input("type"):"";

            $where=[];
            if($request->input("menu_id")){
                $where=["menu_id"=>$menu_id];
            }

            if($request->input("type")){
                $where=["type"=>$type];
            }

            $data=Log::with('menu','admin','lecturer','colleger')->whereBetween('created_at', [$start, $end])
                    ->where($where)->orderBy('created_at','desc')->get();
                    
            if(can_prodi()){
                $has_prodi=Admin::with('role')->whereHas('role', function($q){
                    $q->where(['prodi_id'=> can_prodi()]);
                })->orderBy('created_at','desc')->pluck('id')->toArray();

                 $data=Log::with('menu','admin','lecturer','colleger')->whereIn('admin_id',$has_prodi)
                        ->whereBetween('created_at', [$start, $end])
                        ->where($where)->orderBy('created_at','desc')->get();
            }
           
                
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('time', function($row){
                    return date_id($row->created_at,5);
                })
                ->addColumn('user_type', function($row){
                    $type="";

                    switch ($row->type) {
                        case 0:
                            $type='<span class="badge bg-primary">'.tr('admin').'</span>';
                            break;
                        case 1:
                            $type='<span class="badge bg-info">'.tr('dosen').'</span>';
                            break;
                        case 2:
                            $type='<span class="badge bg-danger">'.tr('mahasiswa').'</span>';
                            break;
                    }

                    return $type;
                   
                })
                ->addColumn('user_log', function($row){
                    $user="";

                    switch ($row->type) {
                        case 0:
                            $user=$row->admin->name;
                            break;
                        case 1:
                            $user=$row->lecturer->name;
                            break;
                        case 2:
                            $user=$row->colleger->name;
                            break;
                    }

                    return $user;
                   
                })
                ->addColumn('avatar', function($row){
                    $img="";
                    
                    switch ($row->type) {
                        case 0:
                            $img= $row->admin->avatar ? asset(AVATAR_PATH . $row->admin->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $row->admin->name);
                            break;
                        case 1:
                            $img= $row->lecturer->avatar ? asset(AVATAR_PATH . $row->lecturer->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $row->lecturer->name);
                            break;
                        case 2:
                            $img= $row->colleger->avatar ? asset(AVATAR_PATH . $row->colleger->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $row->colleger->name);
                            break;
                    }
                    $ava = '<div class="cropcircle"
                    style="
                    background-image: url(\''.$img.'\');
                ">';
                    return $ava;
                })
                ->rawColumns(['avatar','user_type'])
                ->make(true);
        }
    }



}