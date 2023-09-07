<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Colleger;
use App\Models\CollegerClass;
use App\Models\QuizClass;

use App\Models\Quiz;
use App\Models\QuizAbsence;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Models\StudyProgramFull;

use Illuminate\Http\Request;

use Yajra\DataTables\Datatables;

class QuizController extends Controller
{
    private $menu_id;
    private $key_;

    public function __construct()
    {
        $this->menu_id = 18;
        $this->key_ = 'Kuis';
    }

    public function index()
    {
        $prodi_data=StudyProgramFull::orderBy('program_id','ASC')->get();
        $data = [
            "prodi_data"=>$prodi_data,
        ];
        return view('admin/quiz', $data);
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $prodi_id = $request->input("prodi_id");
            $year = $request->input("year");
           
            $data=Quiz::with('lecturer','sks','sks.subject','sks.prodi')->orderBy('created_at','desc')->whereYear('created_at',$year)->get();
            if($prodi_id){
                $data=Quiz::with('lecturer','sks','sks.subject','sks.prodi')->orderBy('created_at','desc')->whereHas('sks', function($q) use($prodi_id){
                    $q->where(['prodi_id'=> $prodi_id]);
                })->whereYear('created_at',$year)->get();
            }
                
            return DataTables::of($data)->addIndexColumn()
                ->editColumn('time', function($row){
                    return date_id($row->created_at,5);
                })
                ->addColumn('lecturer_view', function($row){
                    return title_lecturer($row->lecturer);;
                })
                ->addColumn('prodi', function($row){
                    return $row->sks->prodi->program->name.' - '.$row->sks->prodi->study_program->name.' '.$row->sks->prodi->category->name;
                  })
                  ->addColumn('question', function($row){
                    return QuizQuestion::where('quiz_id',$row->id)->count();
                  })
                  ->addColumn('class', function($row){
                    return QuizClass::where('quiz_id',$row->id)->count();
                  })
                ->addColumn('action', function($row){
                    $action='<a class="btn btn-outline-primary btn-rounded btn-xs" href="'.url('4dm1n/kuis/detail?id='.$row->id).'"><i class="fa fa-eye"></i></a>';
                
                    return $action;
                })
                ->rawColumns(['action','img'])
                ->make(true);
        }
    }

    public function detail(Request $request)
    {
        $id = $request->input("id");
        $quiz_class_id = $request->input("kelas")?$request->input("kelas"):"";
        $quiz_data=Quiz::where('id',$id)->first();
        
        $data = [
            "data"=>$quiz_data,
            "class_id"=>$quiz_class_id
        ];
        return view('admin/quiz_detail', $data);
    }

    public function ajax_class(Request $request)
    {
        $quiz_class_id = $request->input("qc_id");
        $class_first=QuizClass::where("id",$quiz_class_id)->first();
        $qq=QuizQuestion::where('quiz_id',$class_first->quiz->id)->get();
        $cc_data=[];
        if($class_first){
            $colleger_class=CollegerClass::where('class_id',$class_first->class_id)->get()->sortBy('colleger.name');
            foreach ($colleger_class as $item) {
                $item->absence=QuizAbsence::where(['quiz_class_id'=>$quiz_class_id,
                                        'colleger_id'=>$item->colleger_id])->first();
                if($item->absence){
                    $item->absence->time=date_id($item->absence->created_at,5);
                }
                $score=0;
                $value=0;
                foreach($qq as $sub){
                    $value+=$sub->value;
                    $ans=QuizAnswer::where(['quiz_class_id'=>$quiz_class_id,
                    'colleger_id'=>$item->colleger_id,"quiz_question_id"=>$sub->id])->first();
                    if($ans){
                        $score+=(($ans->score/100)*$sub->value);
                    }
                }
                
                
                $item->score=$score;
                $item->value=$value;
                
                
                
                                        
                $item->passed=strtotime($class_first->end) <= strtotime(date('Y-m-d H:i'))?true:false;
                $item->avatar=$item->colleger->avatar ? asset(AVATAR_PATH . $item->colleger->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $item->colleger->name);
                array_push($cc_data,$item);
            }
        }
        
        $out = [
            "message" => "success",
            "result"=>[
                "data"=>$cc_data,
            ]
        ];
        
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function ajax_correction(Request $request)
    {
        $colleger_id = $request->input("id");
        $quiz_id = $request->input("quiz_id");
        $quiz_class_id = $request->input("qc_id");
       
        $colleger_data=Colleger::where("id",$colleger_id)->first();
        $absence_data=QuizAbsence::where(["colleger_id"=>$colleger_id,"quiz_class_id"=>$quiz_class_id])->first();

        $qq_data=QuizQuestion::where("quiz_id",$quiz_id)->orderBy('sort','ASC')->get();

        $answer=[];
        foreach($qq_data as $item){
            $a_data = QuizAnswer::where(["colleger_id" => $colleger_id,"quiz_class_id"=>$quiz_class_id,
                            "quiz_question_id"=>$item->id])->first();
            
            $id="";
            $answers="";
            $score="";
            if($a_data){
                $id=$a_data->id;
                $answers=$a_data->answer;
                if($a_data->file){
                    $answers.="<br><a class='text-info' href='".asset(DOC_PATH.$a_data->file)."'><i class='fa fa-download'></i> ".$a_data->file."</a>";
                }
                $score=$a_data->score?$a_data->score:0;
            }

            array_push($answer,[
                "id"=>$id,
                "question_id"=>$item->id,
                "answer"=>$answers,
                "score"=>$score
            ]);
        }
       
        $absence="-";
        if($absence_data){
            $absence=tr("mulai menegerjakan")." ".date_id($absence_data->created_at,5);
        }
        
        $data=[
            "colleger"=>$colleger_data,
            "answer"=>$answer,
            "absence"=>$absence,
        ];
       
        $out = [
            "message" => "success",
            "result"=>$data
        ];
        
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

}