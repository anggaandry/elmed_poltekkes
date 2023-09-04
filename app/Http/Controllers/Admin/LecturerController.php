<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Lecturer;
use App\Models\LecturerStudyProgram;
use App\Models\Log;
use App\Models\Religion;
use App\Models\Schedule;
use App\Models\ScheduleLecturer;
use App\Models\SKS;
use App\Models\StudyProgramFull;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Datatables;

class LecturerController extends Controller
{
    private $menu_id;
    private $menu_account_id;
    private $key_;

    public function __construct()
    {
        $this->menu_id = 12;
        $this->menu_account_id = 22;
        $this->key_ = 'Dosen';
    }

    public function index(Request $request)
    {
        $data = [];
        return view('admin/lecturer', $data);
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $data=Lecturer::orderBy('name','asc')->get();
            if(can_prodi()){
                $data=Lecturer::whereHas('lecturer_study_program', function($q){
                    $q->where(['prodi_id'=> can_prodi(),'status'=>1]);
                })->orderBy('name','asc')->get();
            }
                
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('status_view', function($row){
                    return $row->status?'<span class="badge bg-success">active</span>':'<span class="badge bg-danger">unactive</span>';
                })
                ->addColumn('avatar', function($row){
                    $img=$row->avatar ? asset(AVATAR_PATH . $row->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $row->name);
                    $ava = '<div class="cropcircle"
                                style="
                                background-image: url(\''.$img.'\');
                            ">';
                    return $ava;
                })
                ->addColumn('prodi', function($row){
                    $lsp_data=LecturerStudyProgram::where('lecturer_id',$row->id)->where('status',1)->get();
                    $prodi="";

                    $first=true;
                    foreach($lsp_data as $item){
                        if(!$first){
                            $prodi.='<br>';
                        }
                        $prodi.= $item->prodi->program->name.' - '.$item->prodi->study_program->name.' '.$item->prodi->category->name;
                        
                        $first=false;
                    }
                    return $prodi;
                })
                ->addColumn('name_view', function($row){
                    return title_lecturer($row);;
                })
                ->addColumn('identity_view', function($row){
                    return $row->identity_number." (".$row->identity.")";
                })
                ->addColumn('action', function($row){
                    $action=$action='<a class="btn btn-outline-primary btn-rounded btn-xs" href="'.url('4dm1n/dosen/detail?id='.$row->id).'"><i class="fa fa-eye"></i></a>';
                    if(can($this->key_,'edit')){
                        $action.='<br> <a class="btn btn-outline-info btn-rounded btn-xs mt-1" href="'.url('4dm1n/dosen/form/edit?id='.$row->id).'"><i class="fa fa-edit"></i></a>';
                    }

                    if(can($this->key_,'delete')){
                        $action.='<br> <button class="btn btn-outline-danger btn-rounded btn-xs mt-1" onclick="show_delete('.$row->id.',\''.$row->name.'\')"><i class="fa fa-trash"></i></button>';
                    }

                    return $action;
                })
                ->rawColumns(['avatar','status_view','action','prodi'])
                ->make(true);
        }
    }

    public function add_view(Request $request)
    {
        
        $religion_data=Religion::orderBy('id','ASC')->get();

        $data = [
            "religion_data"=>$religion_data
        ];

        return view('admin/lecturer_add', $data);
    }

    public function edit_view(Request $request)
    {
        $id = $request->input("id");
        $route = $request->input("route")?1:0;
        
        $lecturer_data=Lecturer::where('id',$id)->first();
        $religion_data=Religion::orderBy('id','ASC')->get();

        $data = [
            "route"=>$route,
            "lecturer_data"=>$lecturer_data,
            "religion_data"=>$religion_data
        ];

        return view('admin/lecturer_edit', $data);
    }

    public function add(Request $request)
    {
        $identity = $request->input("identity");
        $identity_number = $request->input("identity_number");
        $name = $request->input("name");
        $gender = $request->input("gender");
        $status = $request->input("status");
        $religion_id = $request->input("religion_id");
        $front_title= $request->input("front_title");
        $back_title= $request->input("back_title");
        $birthdate= $request->input("birthdate");
        $password= Hash::make(date("dmY",strtotime($birthdate)));

        $data=[
            "name"=>$name,
            "identity"=>$identity,
            "identity_number"=>$identity_number,
            "birthdate"=>$birthdate,
            "gender"=>$gender,
            "status"=>$status,
            "religion_id"=>$religion_id,
            "front_title"=>$front_title,
            "back_title"=>$back_title,
            "password"=>$password
        ];

        $check_identity=Lecturer::where('identity_number',$identity_number)->first();

        if(!$check_identity){
            $avatar=null;
            if ($request->avatar) {
                $avatar = time() . '-dosen.' . $request->avatar->extension();
                $request->avatar->move(public_path(AVATAR_PATH), $avatar);
            }
    
            if($request->avatar){
                $data['avatar']=$avatar;
            }
    
            $status_data = Lecturer::create($data);
    
            if ($status_data) {
                addLog(0,$this->menu_id,'Menambah dosen '.$name);
                return redirect('4dm1n/dosen/detail?id='.$status_data->id)->with('success', 'berhasil menambah dosen');
            } else {
                return redirect()->back()->with('failed', 'gagal menambah dosen');
            }
        }else{
            return redirect()->back()->with('failed', 'nomor identitas sudah ada');
        }

    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $old_data = Lecturer::where(['id'=>$id])->first();

        $route = $request->input("route");
        $identity = $request->input("identity");
        $identity_number = $request->input("identity_number");
        $name = $request->input("name");
        $gender = $request->input("gender");
        $status = $request->input("status");
        $religion_id = $request->input("religion_id");
        $front_title= $request->input("front_title");
        $back_title= $request->input("back_title");
        $birthdate= $request->input("birthdate");

        $old_identity=Lecturer::where('id',$id)->first();
        $check_identity=Lecturer::where('identity_number',$identity_number)->first();
        if($old_identity->identity_number!=$identity_number){
            if($check_identity){
                return redirect()->back()->with('failed', 'nomor identitas sudah ada');
            }
        }

        $data=[
            "name"=>$name,
            "identity"=>$identity,
            "identity_number"=>$identity_number,
            "birthdate"=>$birthdate,
            "gender"=>$gender,
            "status"=>$status,
            "religion_id"=>$religion_id,
            "front_title"=>$front_title,
            "back_title"=>$back_title,
        ];

        $avatar=null;
        if ($request->avatar) {
            $avatar = time() . '-dosen.' . $request->avatar->extension();
            $request->avatar->move(public_path(AVATAR_PATH), $avatar);
        }

        if($request->avatar){
            $data['avatar']=$avatar;
        }
     
        $status_data = Lecturer::where(['id'=>$id])->update($data);


        if ($status_data) {
            if($request->avatar && $old_data->avatar){
                $file_path=AVATAR_PATH.$old_data->avatar;
                unlink($file_path); 
            }

            addLog(0,$this->menu_id,'Mengedit dosen '.$name);
            if($route==1){
                return redirect('4dm1n/dosen/detail?id='.$id)->with('success', 'sukses mengedit dosen');
            }else{
                return redirect('4dm1n/dosen')->with('success', 'sukses mengedit dosen');
            }
           
        } else {
            return redirect()->back()->with('failed', 'gagal mengedit dosen');
        }
    }

    public function delete(Request $request)
    {
        $id = $request->input("id");
        $route = $request->input("route")?1:0;
        $old_data = Lecturer::where(["id" => $id])->first();
        $status_data = Lecturer::where(["id" => $id])->delete();

        if ($status_data) {
            if($old_data->avatar){
                $file_path=AVATAR_PATH.$old_data->avatar;
                unlink($file_path); 
            }
            addLog(0,$this->menu_id,'Menghapus dosen '.$old_data->name);
            if($route==1){
                return redirect('4dm1n/dosen')->with('success', 'Dosen berhasil di hapus');
            }else{
                return redirect()->back()->with('success', 'Dosen berhasil di hapus');
            }
            
        } else {
            return redirect()->back()->with('failed', 'Dosen gagal di hapus');
        }
    }

    public function detail(Request $request)
    {
        $id = $request->input("id");
        $tab = $request->input("tab")?$request->input("tab"):0;
        $lecturer_data=Lecturer::where('id',$id)->first();
        $last_activity=Log::where('lecturer_id',$id)->orderBy('created_at','DESC')->first();
        $prodi_data=[];
        $sp_data=StudyProgramFull::orderBy('program_id','ASC')->get();
        foreach($sp_data as $item){
            $check_lsp=LecturerStudyProgram::where(['lecturer_id'=>$id,'prodi_id'=>$item->id])->first();
            if(!$check_lsp){
                array_push($prodi_data,$item);
            }
        }

        $lsp_data=LecturerStudyProgram::where('lecturer_id',$id)->get();
        $in_prodi=[];
        foreach($lsp_data as $item){
            array_push($in_prodi,$item->prodi_id);
        }

        $subject_data=[];
        $sks_data=SKS::whereIn('prodi_id',$in_prodi)->where('status',1)->orderBy('semester','ASC')->get();
        foreach($sks_data as $item){
            $check_schedule=Schedule::where(['sks_id'=>$item->id])->whereHas('schedule_lecturer',function ($q) use($id) {
                                    $q->where('lecturer_id', '=', $id);
                                })->first();
            if($check_schedule){
                array_push($subject_data,$item);
            }
        }
        

        $data = [
            "tab"=>$tab,
            "prodi_data"=>$prodi_data,
            "prodi_dosen"=>$lsp_data,
            "lecturer_data"=>$lecturer_data,
            "last_activity"=>$last_activity,
            "subject_data"=>$subject_data
        ];
        
        return view('admin/lecturer_detail', $data);
    }

    public function ajax_class(Request $request)
    {
        if ($request->ajax()) {
            $year = $request->input("_year");
            $odd = $request->input("_odd");
            $lecturer_id = $request->input("_lecturer");

            $data=[];
            $class_da=Classes::where(['year'=>$year,'odd'=>$odd])->orderBy('name','asc')->get();
            foreach($class_da as $item){
                $check_schedule=Schedule::where(['class_id'=>$item->id])->whereHas('schedule_lecturer',function ($q) use($lecturer_id) {
                    $q->where('lecturer_id', '=', $lecturer_id);
                })->first();
                
                if($check_schedule){
                    array_push($data,$item);
                }
            }
                
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('prodi', function($row){

                return $row->prodi->program->name.' - '.$row->prodi->study_program->name.' '.$row->prodi->category->name;
              })->make(true);   
        }
    }

    public function ajax_schedule(Request $request)
    {
        if ($request->ajax()) {
            $year = $request->input("_year");
            $odd = $request->input("_odd");
            $lecturer_id = $request->input("_lecturer");

            $data=Schedule::with('sks','sks.subject','class','room')->whereHas('schedule_lecturer',function ($q) use($lecturer_id) {
                                    $q->where('lecturer_id', '=', $lecturer_id);
                                })
                                ->whereHas('class', function($q) use ($year,$odd){
                                    $q->where(['year'=> $year,'odd'=>$odd]);
                                })->orderBy('day','asc')->orderBy('start','asc')->get();
           
            return DataTables::of($data)->addColumn('days', function($row){
                return DAY[$row->day];
              })->addColumn('time', function($row){
                return date('H:i',strtotime($row->start)).' - '.date('H:i',strtotime($row->end));
              })->addColumn('lecturer', function($row){
                $txt="";
                $dosen = ScheduleLecturer::with('lecturer','sls')->where(["schedule_id" => $row->id])->get();
                $txt.="<ul class='text-center'>";
                $i=0;
                foreach ($dosen as $obj) {
                    $i++;
                    
                    $txt.='<li>
                        <span class="mt-5"> '.title_lecturer($obj->lecturer).'</span>
                        <span class="badge badge-xs bg-'.$obj->sls->bg.'" >'.$obj->sls->name.'</span> </li>';

                }

                $txt.="</ul>";

                return $txt;
              })->rawColumns(['lecturer'])->make(true);   
        }
    }

    public function add_prodi(Request $request)
    {
        $prodi_id = $request->input("prodi_id");
        $lecturer_id = $request->input("lecturer_id");

        $data=[
            "status"=>1,
            "prodi_id"=>$prodi_id,
            "lecturer_id"=>$lecturer_id,
        ];

        
        $status_data = LecturerStudyProgram::create($data);

        if ($status_data) {
            $prodi_data=StudyProgramFull::where('id',$prodi_id)->first();
            $lecturer_data=Lecturer::where('id',$lecturer_id)->first();
            addLog(0,$this->menu_id,'Menambah prodi '.$prodi_data->name.' untuk dosen '.$lecturer_data->name);
            return redirect('4dm1n/dosen/detail?tab=1&id='.$lecturer_id)->with('success', 'berhasil menambah dosen');
        } else {
            return redirect('4dm1n/dosen/detail?tab=1&id='.$lecturer_id)->with('failed', 'gagal menambah dosen');
        }
      

    }

    public function status_prodi(Request $request)
    {
        $id = $request->input("id");
        $status = $request->input("status");
        $old_data=LecturerStudyProgram::where(['id'=>$id])->first();

        $data=[
            "status"=>$status,
        ];

        
        $status_data = LecturerStudyProgram::where(['id'=>$id])->update($data);

        if ($status_data) {
            
            addLog(0,$this->menu_id,($status==1?'Mengaktifkan prodi ':'Menon-aktifkan prodi ').$old_data->prodi->name.' untuk dosen '.$old_data->lecturer->name);
            return redirect('4dm1n/dosen/detail?tab=1&id='.$id)->with('success', 'berhasil mengubah status prodi dosen');
        } else {
            return redirect('4dm1n/dosen/detail?tab=1&id='.$id)->with('failed', 'gagal mengubah status prodi dosen');
        }
    }

    public function delete_prodi($id)
    {
       
        $old_data = LecturerStudyProgram::where(["id" => $id])->first();
        $status_data = LecturerStudyProgram::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(0,$this->menu_id,'menghapus prodi'.$old_data->prodi->name.' untuk dosen '.$old_data->lecturer->name);
            return redirect('4dm1n/dosen/detail?tab=1&id='.$old_data->lecturer->id)->with('success', 'Prodi dosen berhasil di hapus');
        } else {
            return redirect('4dm1n/dosen/detail?tab=1&id='.$old_data->lecturer->id)->with('failed', 'Prodi dosen gagal di hapus');
        }
    }


    public function account(Request $request)
    {
        $prodi_id = $request->input("prodi_id");
        if(can_prodi()){$prodi_id=can_prodi();}
        $prodi_data=StudyProgramFull::orderBy('program_id','ASC')->get();
        $data = [
            "prodi_data"=>$prodi_data,
            "prodi_id"=>$prodi_id
        ];
        return view('admin/account_lecturer', $data);
    }

    public function ajax_table_account(Request $request)
    {
        if ($request->ajax()) {
            $prodi_id = $request->input("prodi_id");

            $data=Lecturer::orderBy('name','asc')->get();
            if($prodi_id){
                $data=Lecturer::whereHas('lecturer_study_program', function($q) use ($prodi_id){
                    $q->where(['prodi_id'=> $prodi_id,'status'=>1]);
                })->orderBy('name','asc')->get();
            }
                
            return DataTables::of($data)->addIndexColumn()
                ->addColumn('name_view', function($row){
                    return title_lecturer($row);;
                })
                ->editColumn('online', function($row){
                    return $row->online?date_id($row->online,4):"-";
                })
                ->addColumn('status_view', function($row){
                    return $row->status?'<span class="badge bg-success">active</span>':'<span class="badge bg-danger">unactive</span>';
                })
                ->addColumn('avatar', function($row){
                    $img=$row->avatar ? asset(AVATAR_PATH . $row->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $row->name);
                    $ava = '<div class="cropcircle"
                                style="
                                background-image: url('.$img.');
                            ">';
                    return $ava;
                })
                ->addColumn('action', function($row){
                    $action='<button class="btn btn-outline-success btn-rounded btn-xs" onclick="show_active('.$row->id.',\''.$row->name.'\')"><i class="fa fa-check"></i></button>';
                    if($row->status==1){
                        $action='<button class="btn btn-outline-danger btn-rounded btn-xs" onclick="show_disactive('.$row->id.',\''.$row->name.'\')"><i class="fa fa-times"></i></button>';
                    }

                    $action.=' <button class="btn btn-outline-info btn-rounded btn-xs" onclick="show_respass('.$row->id.',\''.$row->name.'\')"><i class="fa fa-lock"></i></button>';


                    return $action;
                })
                ->rawColumns(['avatar','status_view','action'])
                ->make(true);
        }
    }


    public function password_reset(Request $request)
    {
        $id = $request->input("id");
        $route = $request->input("route")?1:0;
        $prodi_id = $request->input("prodi_id");
        $account_data = Lecturer::where(["id" => $id])->first();
        
        if ($account_data) {
            $password= Hash::make(date("Ymd",strtotime($account_data->birthdate)));
            $status_data = Lecturer::where(['id'=>$id])->update(['password'=>$password]);
            if ($status_data) {
                addLog(0,$this->menu_account_id,'Mereset password akun dosen '.$account_data->name);
                if($route==1){
                    return redirect()->back()->with('success', 'password akun dosen berhasil di reset');
                }else{
                    return redirect('4dm1n/akun/dosen?prodi_id='.$prodi_id)->with('success', 'password akun dosen berhasil di reset');
                }
                
            } else {
                return redirect('4dm1n/akun/dosen?prodi_id='.$prodi_id)->with('failed', 'password akun dosen gagal di reset');
            }
        }else{
            return redirect('4dm1n/akun/dosen?prodi_id='.$prodi_id)->with('failed', 'ID akun tidak ditemukan');
        }
    }

    public function status(Request $request)
    {
        $id = $request->input("id");
        $prodi_id = $request->input("prodi_id");
        $status=$request->input("status");
        $account_data = Lecturer::where(["id" => $id])->first();
        
        if ($account_data) {
            $status_data = Lecturer::where(['id'=>$id])->update(['status'=>$status]);
            if ($status_data) {
                addLog(0,$this->menu_account_id,($status==1?'Mengaktifkan akun dosen ':'Menon-aktifkan akun dosen').$account_data->name);
                return redirect('4dm1n/akun/dosen?prodi_id='.$prodi_id)->with('success', 'Akun dosen berhasil di '.($status==1?' aktifkan':' non-aktifkan'));
            } else {
                return redirect('4dm1n/akun/dosen?prodi_id='.$prodi_id)->with('failed', 'Akun dosen gagal di '.($status==1?' aktifkan':' non-aktifkan'));
            }
        }else{
            return redirect('4dm1n/akun/dosen?prodi_id='.$prodi_id)->with('failed', 'ID akun tidak ditemukan');
        }
    }

   

}