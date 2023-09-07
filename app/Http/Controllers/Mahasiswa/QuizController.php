<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

use App\Models\CollegerClass;
use App\Models\QuizAbsence;
use App\Models\QuizClass;

use App\Models\QuizAnswer;
use App\Models\QuizQuestion;


use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class QuizController extends Controller
{
    private $menu_id;
    private $now_hour;
    private $key_;
    private $now_date;

    public function __construct()
    {
        $this->menu_id = 18;
        $this->key_ = 'Kuis';
        $this->now_hour = date('Y-m-d H:i:s');
        $this->now_date = date('Y-m-d');
    }

    public function index()
    {
        $data = [];
        return view('mahasiswa/quiz', $data);
    }

    public function ajax_list(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->input("_search")?$request->input("_search"):"";
            $colleger_id = $request->input("_colleger");
            $limit = $request->input("_limit");
            $cc_array=CollegerClass::where('colleger_id',$colleger_id)->pluck('class_id')->all();
            $data=QuizClass::whereIn('class_id',$cc_array)->where('start','<=',$this->now_hour)->orderBy('start','DESC');
            if($search!=""){
                $data=$data->whereHas('quiz',function($q) use ($search){
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }
            $data=$data->paginate($limit)->onEachSide(1);

            $data->through(function ($item) {
                $status="<span class='badge badge-sm badge-success'>".tr('aktif')."</span>";
                if(strtotime($item->end)<strtotime($this->now_hour)){
                    $status="<span class='badge badge-sm badge-dark'>".tr('selesai')."</span>";
                    if($item->publish==0){
                        $status="<span class='badge badge-sm badge-danger'>".tr('dikoreksi')."</span>";
                    }
                }
              
                $result=[
                    "id"=>$item->id,
                    
                    "image"=>url(QUIZ_G) . str_replace(' ', '_', $item->quiz->name),
                    "name"=>$item->quiz->name,
                    "class"=>$item->class->name,
                    "start"=>date_id($item->start,5),
                    "end"=>date_id($item->end,5),
                    "subject"=>$item->quiz->sks->subject->name,
                    "total_question"=>count($item->quiz->quiz_question),
                    "status"=>$status
                ];
                return $result;
            });


            $out = [
                "message" => "success",
                "result"=>$data,
               
            ];

            return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
        }
    }

    public function ajax_id(Request $request)
    {
        $id = $request->input("id");
        $data = QuizClass::with('quiz','class','quiz.lecturer','quiz.sks.subject')->where(["id" => $id])->first();
       
        if (!$data) {
            $out = [
                "message" => "ID not found",
                "result"=>[],
            ];
        }else{
            $data->lecturer=title_lecturer($data->quiz->lecturer);
            $data->subject=$data->quiz->sks->subject->name;
            

            $data->image=url(QUIZ_G) . str_replace(' ', '_', $data->quiz->name);
            $data->passed=true;
            if(strtotime($data->start)<=strtotime($this->now_hour) && strtotime($data->end)>=strtotime($this->now_hour)){
                $data->passed=false;
            }
            $data->start=date_id($data->start,5);
            $data->end=date_id($data->end,5);
            $data->score="-";
            
            if($data->passed){
                $data->score="<i class='text-danger'>".tr('sedang dikoreksi')."</i>";
            }
            
            if($data->publish==1){
                $score=0;
                $value=0;
                $qq=QuizQuestion::where('quiz_id',$data->quiz->id)->get();
                foreach($qq as $sub){
                    $value+=$sub->value;
                    $ans=QuizAnswer::where(['quiz_class_id'=>$id,
                    'colleger_id'=>akun('mahasiswa')->id,"quiz_question_id"=>$sub->id])->first();
                    if($ans){
                        $score+=($ans->score/100)*$sub->value;
                    }
                }
                
                
                $data->score='<span class="badge badge-info">'.$score.'/'.$value.'</span>';
                
            }
            
            $out = [
                "message" => "success",
                "result"=>$data
            ];
        }
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function do($id)
    {
        $quiz_class=QuizClass::where("id",$id)->first();
        if(strtotime($quiz_class->start)<=strtotime($this->now_hour) && strtotime($quiz_class->end)>=strtotime($this->now_hour)){
            $colleger_data=akun('mahasiswa');
            $check_view=QuizAbsence::where(['colleger_id'=>$colleger_data->id,'quiz_id'=>$quiz_class->quiz_id,'quiz_class_id'=>$quiz_class->id])->first();
            if(!$check_view ){
                QuizAbsence::create(['colleger_id'=>$colleger_data->id,'quiz_id'=>$quiz_class->quiz->id,'quiz_class_id'=>$quiz_class->id]);
            }

            $question_data=[];
            foreach ($quiz_class->quiz->quiz_question as $item) {
                $item->answer= QuizAnswer::where(['quiz_class_id'=>$id,
                'colleger_id'=>$colleger_data->id,"quiz_question_id"=>$item->id])->first();
                array_push($question_data,$item);
            }
            
            $data = [
                "qc"=>$quiz_class,
                "question"=>$question_data,
                "colleger"=>$colleger_data,
            ];
            return view('mahasiswa/quiz_do', $data);
        }else{
            return redirect('mahasiswa/kuis')->with('failed',tr('kuis berakhir'));
        }
        
       
    }

    public function result($id)
    {
        $quiz_class=QuizClass::where("id",$id)->first();
        if(strtotime($quiz_class->end)<=strtotime($this->now_hour) && $quiz_class->publish==1){
            $colleger_data=akun('mahasiswa');
            $question_data=[];
            $total_score=0;
            $total_value=0;
            foreach ($quiz_class->quiz->quiz_question as $item) {
                $item->answer= QuizAnswer::where(['quiz_class_id'=>$id,
                'colleger_id'=>$colleger_data->id,"quiz_question_id"=>$item->id])->first();
                if($item->answer){
                    $item->score_value=(($item->answer->score/100)*$item->value);
                    $total_score+=$item->score_value;
                }
                $total_value+=$item->value;
                array_push($question_data,$item);
            }

           
            
            $data = [
                "qc"=>$quiz_class,
                "question"=>$question_data,
                "colleger"=>$colleger_data,
                "final_score"=>$total_score,
                "total_value"=>$total_value
            ];
            return view('mahasiswa/quiz_result', $data);
        }else{
            return redirect('mahasiswa/kuis')->with('failed',tr('nilai kuis belum dipublish'));
        }
    }

    public function answer(Request $request)
    {
        $quiz_id = $request->input("quiz_id");
        $quiz_question_id = $request->input("quiz_question_id");
        $quiz_class_id = $request->input("quiz_class_id");
        $colleger_id = $request->input("colleger_id");
        $answer = $request->input("answer");

        $data=[
            "quiz_id"=>$quiz_id,
            "quiz_question_id"=>$quiz_question_id,
            "quiz_class_id"=>$quiz_class_id,
            "colleger_id"=>$colleger_id,
            "answer"=>$answer
            
        ];

        $qq=QuizQuestion::where('id',$quiz_question_id)->first();

        if($qq->question->type==1){
            if($qq->question->choice_answer==$answer){
                $data['score']=100;
            }else{
                $data['score']=0;
            }
        }

        $check=QuizAnswer::where(['quiz_class_id'=>$quiz_class_id,
                            'colleger_id'=>$colleger_id,"quiz_question_id"=>$quiz_question_id])->first();
        
        if($check){
            $status_data=QuizAnswer::where('id',$check->id)->update($data);
        }else{
            $status_data=QuizAnswer::create($data);
        }

        if(!$status_data){
            $message=tr('jawaban gagal disimpan');
            $code=0;
        }else{
            $message=tr('jawaban berhasil disimpan');
            $code=1;
        }
        
        $out = [
            "code"=>$code,
            "message" => $message,
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function file(Request $request)
    {
        $quiz_id = $request->input("quiz_id");
        $quiz_question_id = $request->input("quiz_question_id");
        $quiz_class_id = $request->input("quiz_class_id");
        $colleger_id = $request->input("colleger_id");

        $data=[
            "quiz_id"=>$quiz_id,
            "quiz_question_id"=>$quiz_question_id,
            "quiz_class_id"=>$quiz_class_id,
            "colleger_id"=>$colleger_id,
        ];

        $file=null;
        if ($request->file) {
            $file = time() . '-answer.' . $request->file->extension();
            $request->file->move(public_path(LMS_PATH), $file);
            $data['file']=$file;
            $check=QuizAnswer::where(['quiz_class_id'=>$quiz_class_id,
                            'colleger_id'=>$colleger_id,"quiz_question_id"=>$quiz_question_id])->first();
        
            if($check){
                $status_data=QuizAnswer::where('id',$check->id)->update($data);
            }else{
                $status_data=QuizAnswer::create($data);
            }

            if(!$status_data){
                $message=tr('file gagal disimpan');
                $code=0;
            }else{
                $message=tr('file berhasil disimpan');
                $code=1;
            }
        }else{
            $message=tr("file gagal disimpan,tidak ada file terdeteksi");
            $code=0;
        }

        $out = [
            "code"=>$code,
            "message" => $message,
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function reset(Request $request)
    {
        $quiz_question_id = $request->input("quiz_question_id");
        $quiz_class_id = $request->input("quiz_class_id");
        $colleger_id = $request->input("colleger_id");
       
        $status_data=QuizAnswer::where(['quiz_class_id'=>$quiz_class_id,
        'colleger_id'=>$colleger_id,"quiz_question_id"=>$quiz_question_id])->delete();

        if(!$status_data){
            $message=tr('gagal menghapus jawaban');
            $code=0;
        }else{
            $message=tr('sukses menghapus jawaban')." ";
            $code=1;
        }
        
        $out = [
            "code"=>$code,
            "message" => $message,
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }
    
}