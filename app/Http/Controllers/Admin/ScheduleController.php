<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Lecturer;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\ScheduleLecturer;
use App\Models\ScheduleLecturerStatus;
use App\Models\Semester;
use App\Models\SKS;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;


class ScheduleController extends Controller
{
    private $menu_id;
    private $key_;

    public function __construct()
    {
        $this->menu_id = 10;
        $this->key_ = 'Jadwal';
    }

    public function index(Request $request)
    {
       
        
        $semester_id = $request->input("semester");
        if(!$semester_id){
            $semester_id=semester_now()->id;
        }

        $semester_select=Semester::where('id',$semester_id)->first();

        $where=["year"=>$semester_select->year,"odd"=>$semester_select->odd];
        if(can_prodi()){
            $where['prodi_id']=can_prodi();
        }

        $class_data=Classes::where($where)->get();
        $sks_data=[];
    
        $room_data=Room::orderBy('name','ASC')->get();
        $sls_data=ScheduleLecturerStatus::orderBy('id','ASC')->get();
        $semester_data=Semester::orderBy('start','ASC')->get();
        
        $data = [
            "room_data"=>$room_data,
            "sks_data" => $sks_data,
            "class_data" => $class_data,
            "semester_id"=>$semester_id,
            "semester_data"=>$semester_data,
            "sls_data"=>$sls_data,
        ];
        return view('admin/schedule', $data);
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $class_id = $request->input("class_id");
            $data=Schedule::with('sks','sks.subject','class','room')->where('class_id',$class_id)->orderBy('day','ASC')->orderBy('start','ASC')->get();
           
            return DataTables::of($data)->addColumn('days', function($row){
                return DAY[$row->day];
              })->addColumn('day_colors', function($row){
                return DAY_COLOR[$row->day];
              })->addColumn('time', function($row){
                return date('H:i',strtotime($row->start)).' - '.date('H:i',strtotime($row->end));
              })->addColumn('lecturer', function($row){
                $txt="";
                $dosen = ScheduleLecturer::with('lecturer','sls')->where(["schedule_id" => $row->id])->get();
                $txt.="<table class='table table-bordered align-middle'>";
                $i=0;
                foreach ($dosen as $obj) {
                    $i++;
                    $action="";
                    if(can($this->key_,'edit')){
                        $action.='<a class="text-info" onclick="show_lecture_edit('.$obj->id.','.$obj->sls_id.','.$obj->lecturer_id.')" href="javascript::void()"><i class="fa fa-edit"></i></a>';
                    }

                    if(can($this->key_,'delete')){
                        $action.='<br><a class="text-danger mt-1" href="javascript::void()" onclick="show_lecture_delete('.$obj->id.',\''.title_lecturer($obj->lecturer).'\')"><i class="fa fa-trash"></i></a>';
                    }
                    $txt.='<tr>
                        <th class="align-middle"><span class="badge badge-primary badge-sm bg-'.$obj->sls->bg.'">'.$obj->sls->name.'</span></th>
                        <th class="align-middle">'.title_lecturer($obj->lecturer).'</th>
                        <th class="align-middle">'.$action.'</th>
                    </tr>';

                }
                if(can($this->key_,'add')){
                    $txt.='<tr>
                    <th colspan="3" class="text-center"><a class="text-success" href="javascript::void()" onclick="show_lecture_add('.$row->id.')"><i class="fa fa-plus-circle"></i>'.tr('tambah').'</button></th>
                </tr>';
                }

                $txt.="</table>";

                return $txt;
              })->addColumn('action', function($row){
                $action="";
                if(can($this->key_,'edit')){
                    $action.='<button class="btn btn-outline-info btn-rounded btn-xs" onclick="show_edit('.$row->id.')"><i class="fa fa-edit"></i></button>';
                }

                if(can($this->key_,'delete')){
                    $action.=' <button class="btn btn-outline-danger btn-rounded btn-xs" onclick="show_delete('.$row->id.',\''.$row->sks->subject->name.'\')"><i class="fa fa-trash"></i></button>';
                }
    
                return $action;
            })->rawColumns(['lecturer','action'])->make(true);   
        }
    }

    public function ajax_id(Request $request)
    {
        $id = $request->input("id");
        $data = Schedule::where(["id" => $id])->first();

        if (!$data) {
            $out = [
                "message" => "ID not found",
                "result"=>[],
            ];
        }else{
            $out = [
                "message" => "success",
                "result"=>$data
            ];
        }
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function ajax_sks(Request $request)
    {
        $class_id = $request->input("class_id");
        $class_select=Classes::where('id',$class_id)->first();
        $data=[];
        
        if($class_select){
            $sks_data=SKS::where(['prodi_id'=>$class_select->prodi_id,'status'=>1,'semester'=>$class_select->semester])->where('status',1)->orderBy('code','ASC')->get();
          
            foreach ($sks_data as $item) {
                array_push($data,[
                    "id"=>$item->id,
                    "name"=>$item->code." - ".$item->subject->name
                ]);
            }
        }
        
        $out = [
            "message" => "success",
            "result"=>$data
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function ajax_lecturer(Request $request)
    {
        $class_id = $request->input("class_id");
        $class_select=Classes::where('id',$class_id)->first();
        if($class_select){
            $lecturer_data=Lecturer::whereHas('lecturer_study_program', function($q) use ($class_select){
                $q->where(['prodi_id'=> $class_select->prodi_id,'status'=>1]);
            })->orderBy('name','asc')->get();

            $data=[];
            foreach ($lecturer_data as $item) {
                array_push($data,[
                    "id"=>$item->id,
                    "name"=>title_lecturer($item)
                ]);
            }
        }
    
        $out = [
            "message" => "success",
            "result"=>$data
        ];
        
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function add(Request $request)
    {
        $class_id = $request->input("class_id");
        $sks_id = $request->input("sks_id");
        $day = $request->input("day");
        $start = $request->input("start");
        $end = $request->input("end");
        $room_id = $request->input("room_id");
        
        $status_data = Schedule::create([
            "class_id" => $class_id,
            "sks_id" => $sks_id,
            "day" => $day,
            "start" => $start,
            "end" => $end,
            "room_id"=>$room_id
        ]);

        $class_select = Classes::where('id',$class_id)->first();
        $message=tr("menambah jadwal gagal");
        $code=0;

        if ($status_data) {
            addLog(0,$this->menu_id,'Menambah data jadwal '.$class_select->name);
            $message=tr("menambah jadwal sukses");
            $code=1;
        } 

        $out = [
            "message" => $message,
            "code"=>$code,
        ];
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $class_id = $request->input("class_id");
        $sks_id = $request->input("sks_id");
        $day = $request->input("day");
        $start = $request->input("start");
        $end = $request->input("end");
        $room_id = $request->input("room_id");
        
        $update=[
            "sks_id" => $sks_id,
            "day" => $day,
            "start" => $start,
            "end" => $end,
            "room_id"=>$room_id
           
        ];

        $status_data = Schedule::where(['id'=>$id])->update($update);
        $message=tr("mengedit jadwal gagal");
        $code=0;
        if ($status_data) {
            $class_select = Classes::where('id',$class_id)->first();
            addLog(0,$this->menu_id,'Mengedit data jadwal '.$class_select->name);
            $message=tr("mengedit jadwal sukses");
            $code=1;
        }


        $out = [
            "message" => $message,
            "code"=>$code,
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function delete(Request $request)
    {
        $id = $request->input("id");
        $old_data = Schedule::where(["id" => $id])->first();
        $status_data = Schedule::where(["id" => $id])->delete();
        $message=tr("menghapus jadwal gagal");
        $code=0;

        if ($status_data) {
            addLog(0,$this->menu_id,'Menghapus data jadwal'.$old_data->class->name);
            $message=tr("menghapus jadwal sukses");
            $code=1;
        } 

        $out = [
            "message" => $message,
            "code"=>$code,
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);

    }


    public function lecturer_add(Request $request)
    {
        $schedule_id = $request->input("schedule_id");
        $lecturer_id = $request->input("lecturer_id");
        $sls_id = $request->input("sls_id");
        
        $status_data = ScheduleLecturer::create([
            "schedule_id" => $schedule_id,
            "lecturer_id" => $lecturer_id,
            "sls_id" => $sls_id,
        ]);

        
        $message=tr("menambah dosen untuk jadwal gagal");
        $code=0;

        if ($status_data) {
           
            $lecturer=Lecturer::where('id',$lecturer_id)->first();
            addLog(0,$this->menu_id,'Menambah dosen '.$lecturer->name.' untuk jadwal ');
            $message=tr("menambah dosen untuk jadwal sukses");
            $code=1;
        } 

        $out = [
            "message" => $message,
            "code"=>$code,
        ];
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }


    public function lecturer_edit(Request $request)
    {
        $id = $request->input("id");
        $sls_id = $request->input("sls_id");
        
        $status_data = ScheduleLecturer::where('id',$id)->update([
            "sls_id" => $sls_id,
        ]);
        
        $message=tr("mengubah dosen untuk jadwal gagal");
        $code=0;

        if ($status_data) {
            $old_data=ScheduleLecturer::where('id',$id)->first();
           
            addLog(0,$this->menu_id,'Megubah status dosen '.$old_data->lecturer->name.' untuk jadwal ');
            $message=tr("mengubah dosen untuk jadwal sukses");
            $code=1;
        } 

        $out = [
            "message" => $message,
            "code"=>$code,
        ];
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function lecturer_delete(Request $request)
    {
        $id = $request->input("id");
        $old_data = ScheduleLecturer::where(["id" => $id])->first();
        $status_data = ScheduleLecturer::where(["id" => $id])->delete();
        $message=tr("menghapus dosen untuk jadwal gagal");
        $code=0;

        if ($status_data) {
            addLog(0,$this->menu_id,'Meghapus status dosen '.$old_data->lecturer->name.' untuk jadwal ');
            $message=tr("menghapus dosen untuk jadwal sukses");
            $code=1;
        } 

        $out = [
            "message" => $message,
            "code"=>$code,
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

   


}