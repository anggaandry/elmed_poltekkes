<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\AbsenceStart;
use App\Models\Admin;
use App\Models\Calendar;
use App\Models\Colleger;
use App\Models\CollegerClass;
use App\Models\ElearningClass;
use App\Models\ExamClass;
use App\Models\Log;
use App\Models\QuizClass;
use App\Models\Schedule;
use App\Models\ScheduleLecturer;
use App\Models\Semester;
use App\Models\University;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class DashboardController extends Controller
{
    private $now_hour;
    private $now_date;
    
    public function __construct()
    {
        $this->now_hour = date('Y-m-d H:i:s');
        $this->now_date = date('Y-m-d');
    }

    public function index()
    {
        $ac_=active_class();
        $schedule_data=[];

        $schedule=Schedule::where(['day'=>date('w')])->where("class_id",$ac_->id)->orderBy('start','asc')->get();
        foreach ($schedule as $item) {
            $lecturer=ScheduleLecturer::where('schedule_id',$item->id)->where('sls_id',1)->first();
            $item->moved=false;
            $item->lecturer="";
            
            if($lecturer){
                $item->lecturer=title_lecturer($lecturer->lecturer);
            }
            array_push($schedule_data,$item);
        }

        $schedule_move=AbsenceStart::where("date",date('Y-m-d'))->where('moved_from',"!=",null)->whereHas('schedule', function($q) use($ac_) {
            $q->where('class_id',$ac_->id);
        })->get();

        foreach($schedule_move as $obj){
            $item=$obj->schedule;
            $item->moved=true;
            $item->start=$obj->start;
            $item->end=$obj->end;
            $lecturer=ScheduleLecturer::where('schedule_id',$item->id)->where('sls_id',1)->first();
            
            $item->lecturer="";
            if($lecturer){
                $item->lecturer=title_lecturer($lecturer->lecturer);
            }
            array_push($schedule_data,$item);
        }

        usort($schedule_data, function($a, $b)
        {
            return strcmp($a->start, $b->start);
        });


        $elearning=ElearningClass::where('start','<=',$this->now_hour)->where('end','>=',$this->now_hour)
                                    ->where('class_id',$ac_->id)->get();

        $quiz=QuizClass::where('start','<=',$this->now_hour)->where('end','>=',$this->now_hour)
                            ->where('class_id',$ac_->id)->get();
       
        
        $exam=ExamClass::where('start','<=',$this->now_hour)->where('end','>=',$this->now_hour)
                            ->where('class_id',$ac_->id)->get();

        $event=Calendar::where('date',date('Y-m-d'))->first();
                            
        if($event){
            $holiday=$event->name;
            $schedule_data=[];
        }else{
            $holiday="Libur jadwal kosong";
            if(date('w')==0){
                $holiday="Libur hari minggu";
            }
        }

        $data=[
            "schedule"=>$schedule_data,
            "elearning"=>$elearning,
            "quiz"=>$quiz,
            "exam"=>$exam,
            "holiday_name"=>$holiday,
        ];
        return view('mahasiswa/dashboard',$data);
    }

    public function profile(Request $request)
    {
        $colleger_data = akun('mahasiswa');
        $tab = $request->input("tab")?$request->input("tab"):0;
       
        $last_activity=Log::where('colleger_id',$colleger_data->id)->orderBy('created_at','DESC')->first();

        $data = [
            "tab"=>$tab,
            "colleger_data"=>$colleger_data,
            "last_activity"=>$last_activity,
        ];
        
        return view('mahasiswa/profile', $data);
    }

    public function absence()
    {
        $colleger_id = akun('mahasiswa')->id;
        $ac_=active_class();

        $str=AbsenceStart::where("date",date('Y-m-d'))->where('start','<=',date('H:i:s'))->where('end','>=',date('H:i:s'))->whereHas('schedule', function($q) use($ac_) {
            $q->where('class_id',$ac_->id);
        })->first();

        $absence=null;
        if($str){
            $str->sks_name=$str->schedule->sks->subject->name." (".$str->schedule->sks->value." SKS)";
            $str->room_name=$str->schedule->room->name;
            $str->schedule_name=date_id($str->date." ".$str->start,2).' - '.date('H:i',strtotime($str->end));
            if($str->moved==1){
                $str->schedule_name.="<br><small class='text-danger mt-1'>Dipindahkan dari ".date_id($str->moved_from." ".$str->schedule->start,2).' - '.date('H:i',strtotime($str->schedule->end))."</small>";
            }

           
            $str->timer=date('M d, Y H:i:s', strtotime($str->date." ".$str->end));
            $lectxt="";
            $dosen = ScheduleLecturer::with('lecturer','sls')->where(["schedule_id" => $str->schedule->id])->get();
            $lectxt.="<ul class='text-start'>";
            $i=0;
            foreach ($dosen as $obj) {
                $i++;
                $lectxt.='<li>
                    <span class="mt-5">1. '.title_lecturer($obj->lecturer).'</span>
                    <span class="badge badge-xs bg-'.$obj->sls->bg.'" >'.$obj->sls->name.'</span> </li>';
            }
            $lectxt.="</ul>";
            $str->lecturer=$lectxt;
            $str->class_name=$str->schedule->class->name;

            $absence=Absence::where(['date'=>date('Y-m-d'),'schedule_id'=>$str->schedule_id,'colleger_id'=>$colleger_id,'start_id'=>$str->id])->first();
        }

        $out = [
            "message" => "success",
            "result"=>[
                "str"=>$str,
                "absence"=>$absence
            ]
        ];
      
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
       
    }

    public function submit_absence(Request $request)
    {
        $date = $request->input("date");
        $colleger_id = $request->input("colleger_id");
        $schedule_id = $request->input("schedule_id");
        $start_id = $request->input("start_id");
        $status = $request->input("status");
        $note = $request->input("note");

        $check_absence=Absence::where(['start_id'=>$start_id,'date'=>$date,'colleger_id'=>$colleger_id,'schedule_id'=>$schedule_id])->first();

        $status_array=["alfa","hadir","izin"];

        if($check_absence){
            $status_data=Absence::where(['id'=>$check_absence->id])->update(['status'=>$status,'note'=>$note]);
        }else{
            $status_data=Absence::create(['start_id'=>$start_id,
                                            'date'=>$date,
                                            'colleger_id'=>$colleger_id,
                                            'schedule_id'=>$schedule_id,
                                            'status'=>$status,
                                        'note'=>$note]);
                                            
        }

        if(!$status_data){
            $message="Gagal mengisi absensi ".$status_array[$status];
            $code=0;
        }else{
            $colleger_data=Colleger::where("id",$colleger_id)->first();
            addLog(2,11,"mengisi absensi ".$status_array[$status]." mahasiswa ".$colleger_data->name." tanggal ".date_id($date,1));
            $message="Sukses mengisi absensi ".$status_array[$status];
            $code=1;
        }
        
        $out = [
            "code"=>$code,
            "message" => $message,
            
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

}