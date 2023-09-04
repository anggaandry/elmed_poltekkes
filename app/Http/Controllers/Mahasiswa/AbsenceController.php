<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\AbsenceStart;
use App\Models\Calendar;
use App\Models\CollegerClass;

use App\Models\Schedule;
use App\Models\ScheduleLecturer;
use App\Models\Semester;

use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class AbsenceController extends Controller
{
   
    public function index()
    {
        $data=[
            "colleger_data"=>akun('mahasiswa'),
        ];
        return view('mahasiswa/absence',$data);
    }

    public function table(Request $request)
    {
        if ($request->ajax()) {
            $date = $request->input("_date");
            $colleger_id = $request->input("_colleger");

            $data=[];
            $semester=Semester::whereDate('start','<=',$date)->whereDate('end','>=',$date)->first();
           
            if($semester){
                $class_data=CollegerClass::where('colleger_id',$colleger_id)->whereHas('class', function($q) use($semester){
                    $q->where(['odd'=> $semester->odd,'year'=>$semester->year]);
                })->orderBy('id','DESC')->first();
                $schedule_data=Schedule::with('sks','sks.subject','class','room')->where('class_id',$class_data->class_id)
                                ->where('day',date('w',strtotime($date)))->orderBy('day','asc')->orderBy('start','asc')->get();
                foreach ($schedule_data as $item) {
                    $item->move=null;
                    $item->time=date_id($date." ".$item->start,2).' - '.date('H:i',strtotime($item->end));
                    
                    $lectxt="";
                    $dosen = ScheduleLecturer::with('lecturer','sls')->where(["schedule_id" => $item->id])->get();
                    $lectxt.="<ul class='text-start'>";
                    $i=0;
                    foreach ($dosen as $obj) {
                        $i++;
                        $lectxt.='<li>
                            <span class="mt-5">1. '.title_lecturer($obj->lecturer).'</span>
                            <span class="badge badge-xs bg-'.$obj->sls->bg.'" >'.$obj->sls->name.'</span> </li>';
                    }
                    $lectxt.="</ul>";

                    $status="-";
                    $note="-";
                    $session="-";
                    $activity="-";
                    $absence_start=AbsenceStart::where(['schedule_id'=>$item->id,'date'=>$date])->where('moved_from','=',null)->first();
                    if($absence_start){
                        $absence_check=Absence::where(['schedule_id'=>$item->id,'colleger_id'=>$colleger_id,'start_id'=>$absence_start->id])->first();
                        $session="".$absence_start->session;
                        $activity=$absence_start->activity;
                        if($absence_check){
                            if($absence_check->status){
                                switch ($absence_check->status) {
                                    case 0:
                                        $status='<span class="badge bg-danger">Absen</span>';
                                        break;
                                    case 1:
                                        $status='<span class="badge bg-success">Hadir</span>';
                                        break;
                                    case 2:
                                        $status='<span class="badge bg-warning">Izin</span>';
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            }

                            if($absence_check->note){
                                $note=$absence_check->note;
                            }
                        }
                    }

                    $absence_move=AbsenceStart::where(['schedule_id'=>$item->id])->where('moved_from','=',$date)->first();
                    if($absence_move){
                        $absence_check=Absence::where(['schedule_id'=>$item->id,'colleger_id'=>$colleger_id,'start_id'=>$absence_move->id])->first();
                        $session="".$absence_move->session;
                        $activity=$absence_move->activity;
                        $item->time.="<br><span class='badge badge-danger badge-xs mt-1'>Dipindahkan ke ".date_id($absence_move->date." ".$absence_move->start,2).' - '.date('H:i',strtotime($absence_move->end))."</span>";
                        if($absence_check){
                            if($absence_check->status){
                                switch ($absence_check->status) {
                                    case 0:
                                        $status='<span class="badge bg-danger">Absen</span>';
                                        break;
                                    case 1:
                                        $status='<span class="badge bg-success">Hadir</span>';
                                        break;
                                    case 2:
                                        $status='<span class="badge bg-warning">Izin</span>';
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            }

                            if($absence_check->note){
                                $note=$absence_check->note;
                            }
                        }
                        $item->move=$absence_move;
                    }
                  

                    $item->nosession=null;
                    if(!$absence_move && !$absence_start && strtotime($item->end)<=strtotime(date('Y-m-d H:i:s'))){
                        $item->nosession='<i class="text-danger">Berakhir tanpa sesi kelas</i>';
                    }

                    if($absence_move){
                        if($absence_move->active==0 && strtotime($item->end)<=strtotime(date('Y-m-d H:i:s'))){
                            $item->nosession='<i class="text-danger">Berakhir tanpa sesi kelas</i>';
                        }
                    }
                   
                    $item->dosen=$lectxt;
                    $item->status=$status;
                    $item->note=$note;
                    $item->session=$session;
                    $item->activity=$activity;
                    $item->class_name=$item->class->name;
                    $item->sks_name=$item->sks->subject->name." (".$item->sks->value." SKS)";
                    $item->room_name=$item->room->name;

                    array_push($data,$item);
                }
            }

            $event=Calendar::where('date',$date)->first();
            $holiday="";      
            if($event){
                $holiday=$event->name;
                $data=[];
            }else{
                $holiday="Libur jadwal kosong";
                if(date('w',strtotime($date))==0){
                    $holiday="Libur hari minggu";
                }
            }

            $out = [
                "message" => "success",
                "result"=>$data,
                "holiday"=>$holiday,
            ];
            
            return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
        }
    }

   
}