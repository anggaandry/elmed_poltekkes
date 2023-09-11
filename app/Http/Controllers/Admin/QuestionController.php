<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\LecturerStudyProgram;
use App\Models\Question;
use App\Models\Schedule;
use App\Models\SKS;
use App\Models\StudyProgramFull;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class QuestionController extends Controller
{
    private $menu_id;
    private $key_;

    public function __construct()
    {
        $this->menu_id = 16;
        $this->key_ = 'Soal';
    }

    public function index(Request $request)
    {
        $prodi_id = $request->input("prodi_id");
        if(can_prodi()){$prodi_id=can_prodi();}
        $prodi_data=StudyProgramFull::orderBy('program_id','ASC')->get();
        $data = [
            "prodi_data"=>$prodi_data,
            "prodi_id"=>$prodi_id,
        ];
        return view('admin/question', $data);
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $prodi_id=$request->input("prodi_id")?$request->input("prodi_id"):"";

            $data=[];
            if($prodi_id==""){
                $data=Question::with('lecturer')->orderBy('created_at','desc')->get();
            }else{
                $data=Question::with('lecturer')->whereHas('sks',function($q) use ($prodi_id){
                    $q->where('prodi_id',$prodi_id);
                })->orderBy('created_at','desc')->get();
            }
            
            
            return DataTables::of($data)->addIndexColumn()
                ->editColumn('subject', function($row){
                    return $row->sks->subject->name.' (sem '.$row->sks->semester.') <br><small> '.tr('oleh').' '.title_lecturer($row->lecturer).'</small>';
                })
                ->editColumn('time', function($row){
                    return date_id($row->created_at,5);
                })
                ->editColumn('type_name', function($row){
                    $type_name="";
                    switch ($row->type) {
                        case 0:
                            $type_name="<span class='text-info'>".tr('essay')."</span>";
                            break;
                        case 1:
                            $type_name="<span class='text-success'>".tr('pilihan berganda')."</span>";
                            break;
                        case 2:
                            $type_name="<span class='text-danger'>".tr('upload file')."</span>";
                            break;
                    }
                    return $type_name;
                })
                
                ->addColumn('full_question', function($row){
                    $fq=$row->question;
                    if($row->choice){
                        $fq.='<table class="table table-borderless">';
                        $choices=json_decode($row->choice,false);
                        foreach($choices as $item){
                            $fq.="<tr><th class='m-0 p-0' width='5%'><p>".$item->choice.".</p></th><td class='m-0 p-0'> ".$item->desc."</td></tr>";
                        }
                        $fq.='</table>';
                    }
                    
                    if($row->file){
                        $fq.='<a class="btn btn-xs btn-info" href="'.asset(DOC_PATH.$row->file).'" download><i class="fa fa-download"></i> '.$row->file.'</a><br>';
                    }
                  return $fq;
                })
                ->addColumn('action', function($row){
                    $action='<button class="btn btn-outline-primary btn-rounded btn-xs" onclick="show_detail('.$row->id.')"><i class="fa fa-eye"></i></button>';
                   
                    return $action;
                })
                ->rawColumns(['full_question','action','subject','type_name'])
                ->make(true);
        }
    }

    public function ajax_id(Request $request)
    {
        $id = $request->input("id");
        $data = Question::where(["id" => $id])->first();

        if (!$data) {
            $out = [
                "message" => "ID not found",
                "result"=>[],
            ];
        }else{
            $fq=$data->question;
            if($data->choice){
                $fq.='<table class="table table-borderless">';
                $choices=json_decode($data->choice,false);
                foreach($choices as $item){
                    $fq.="<tr><th class='m-0 p-0' width='5%'><p>".$item->choice.".</p></th><td class='m-0 p-0'> ".$item->desc."</td></tr>";
                }
                $fq.='</table>';
            }
            
            if($data->file){
                $fq.='<a class="btn btn-xs btn-info" href="'.asset(DOC_PATH.$data->file).'" download><i class="fa fa-download"></i> '.$data->file.'</a><br>';
            }
                  
            $data->fq=$fq;

            $data->type_name="";

            switch ($data->type) {
                case 0:
                    $data->type_name=tr("essay");
                    break;
                case 1:
                    $data->type_name=tr("pilihan berganda");
                    break;
                case 2:
                    $data->type_name=tr("upload file");
                    break;
                
                default:
                    # code...
                    break;
            }
            $out = [
                "message" => "success",
                "result"=>$data
            ];
        }
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

   
}