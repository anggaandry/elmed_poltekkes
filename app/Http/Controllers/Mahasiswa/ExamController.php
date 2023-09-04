<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;

use App\Models\CollegerClass;
use App\Models\ExamAbsence;
use App\Models\ExamClass;

use App\Models\ExamAnswer;
use App\Models\ExamQuestion;


use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class ExamController extends Controller
{
    private $menu_id;
    private $now_hour;

    public function __construct()
    {
        $this->menu_id = 18;
        $this->key_ = 'Ujian';
        $this->now_hour = date('Y-m-d H:i:s');
        $this->now_date = date('Y-m-d');
    }

    public function index()
    {
        $data = [];
        return view('mahasiswa/exam', $data);
    }

    public function ajax_list(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->input("_search")?$request->input("_search"):"";
            $colleger_id = $request->input("_colleger");
            $limit = $request->input("_limit");
            $cc_array=CollegerClass::where('colleger_id',$colleger_id)->pluck('class_id')->all();
            $data=ExamClass::whereIn('class_id',$cc_array)->where('start','<=',$this->now_hour)->orderBy('start','DESC');
            if($search!=""){
                $data=$data->whereHas('exam',function($q) use ($search){
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }
            $data=$data->paginate($limit)->onEachSide(1);

            $data->through(function ($item) {
                $status="<span class='badge badge-sm badge-success'>aktif</span>";
                if(strtotime($item->end)<strtotime($this->now_hour)){
                    $status="<span class='badge badge-sm badge-dark'>selesai</span>";
                    if($item->publish==0){
                        $status="<span class='badge badge-sm badge-danger'>dikoreksi</span>";
                    }
                }
              
                $result=[
                    "id"=>$item->id,
                    
                    "image"=>url(EXAM_G) . str_replace(' ', '_', $item->exam->name),
                    "name"=>$item->exam->name,
                    "class"=>$item->class->name,
                    "start"=>date_id($item->start,5),
                    "end"=>date_id($item->end,5),
                    "subject"=>$item->exam->sks->subject->name,
                    "total_question"=>count($item->exam->exam_question),
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
        $data = ExamClass::with('exam','class','exam.lecturer','exam.sks.subject')->where(["id" => $id])->first();
       
        if (!$data) {
            $out = [
                "message" => "ID not found",
                "result"=>[],
            ];
        }else{
            $data->lecturer=title_lecturer($data->exam->lecturer);
            $data->subject=$data->exam->sks->subject->name;
            

            $data->image=url(EXAM_G) . str_replace(' ', '_', $data->exam->name);
            $data->passed=true;
            if(strtotime($data->start)<=strtotime($this->now_hour) && strtotime($data->end)>=strtotime($this->now_hour)){
                $data->passed=false;
            }
            
            
            $data->start=date_id($data->start,5);
            $data->end=date_id($data->end,5);
            $data->score="-";
            
            if($data->passed){
                $data->score="<i class='text-danger'>sedang dikoreksi</i>";
            }

            if($data->publish==1){
                $score=0;
                $value=0;
                $qq=ExamQuestion::where('exam_id',$data->exam->id)->get();
                foreach($qq as $sub){
                    $value+=$sub->value;
                    $ans=ExamAnswer::where(['exam_class_id'=>$id,
                    'colleger_id'=>akun('mahasiswa')->id,"exam_question_id"=>$sub->id])->first();
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
        $exam_class=ExamClass::where("id",$id)->first();
        if(strtotime($exam_class->start)<=strtotime($this->now_hour) && strtotime($exam_class->end)>=strtotime($this->now_hour)){
            $colleger_data=akun('mahasiswa');
            $check_view=ExamAbsence::where(['colleger_id'=>$colleger_data->id,'exam_id'=>$exam_class->exam_id,'exam_class_id'=>$exam_class->id])->first();
            if(!$check_view ){
                ExamAbsence::create(['colleger_id'=>$colleger_data->id,'exam_id'=>$exam_class->exam_id,'exam_class_id'=>$exam_class->id]);
                
            }
            $question_data=[];
            foreach ($exam_class->exam->exam_question as $item) {
                $item->answer= ExamAnswer::where(['exam_class_id'=>$id,
                'colleger_id'=>$colleger_data->id,"exam_question_id"=>$item->id])->first();
                array_push($question_data,$item);
            }
            
            $data = [
                "qc"=>$exam_class,
                "question"=>$question_data,
                "colleger"=>$colleger_data,
            ];
            return view('mahasiswa/exam_do', $data);
        }else{
            return redirect('mahasiswa/ujian')->with('failed','Ujian berakhir');
        }
        
       
    }

    public function result($id)
    {
        $exam_class=ExamClass::where("id",$id)->first();
        if(strtotime($exam_class->end)<=strtotime($this->now_hour) && $exam_class->publish==1){
            $colleger_data=akun('mahasiswa');
            $question_data=[];
            $total_score=0;
            $total_value=0;
            foreach ($exam_class->exam->exam_question as $item) {
                $item->answer= ExamAnswer::where(['exam_class_id'=>$id,
                'colleger_id'=>$colleger_data->id,"exam_question_id"=>$item->id])->first();
                if($item->answer){
                    $item->score_value=(($item->answer->score/100)*$item->value);
                    $total_score+=$item->score_value;
                }
                $total_value+=$item->value;
                array_push($question_data,$item);
            }

            
            $data = [
                "qc"=>$exam_class,
                "question"=>$question_data,
                "colleger"=>$colleger_data,
                "final_score"=>$total_score,
                "total_value"=>$total_value
            ];
            return view('mahasiswa/exam_result', $data);
        }else{
            return redirect('mahasiswa/ujian')->with('failed','Nilai ujian belum dipublish');
        }
    }

    public function answer(Request $request)
    {
        $exam_id = $request->input("exam_id");
        $exam_question_id = $request->input("exam_question_id");
        $exam_class_id = $request->input("exam_class_id");
        $colleger_id = $request->input("colleger_id");
        $answer = $request->input("answer");

        $data=[
            "exam_id"=>$exam_id,
            "exam_question_id"=>$exam_question_id,
            "exam_class_id"=>$exam_class_id,
            "colleger_id"=>$colleger_id,
            "answer"=>$answer
            
        ];

        $qq=ExamQuestion::where('id',$exam_question_id)->first();

        if($qq->question->type==1){
            if($qq->question->choice_answer==$answer){
                $data['score']=100;
            }else{
                $data['score']=0;
            }
        }

        $check=ExamAnswer::where(['exam_class_id'=>$exam_class_id,
                            'colleger_id'=>$colleger_id,"exam_question_id"=>$exam_question_id])->first();
        
        if($check){
            $status_data=ExamAnswer::where('id',$check->id)->update($data);
        }else{
            $status_data=ExamAnswer::create($data);
        }

        if(!$status_data){
            $message="Jawaban gagal disimpan";
            $code=0;
        }else{
            $message="Jawaban berhasil disimpan";
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
        $exam_id = $request->input("exam_id");
        $exam_question_id = $request->input("exam_question_id");
        $exam_class_id = $request->input("exam_class_id");
        $colleger_id = $request->input("colleger_id");

        $data=[
            "exam_id"=>$exam_id,
            "exam_question_id"=>$exam_question_id,
            "exam_class_id"=>$exam_class_id,
            "colleger_id"=>$colleger_id,
        ];

        $file=null;
        if ($request->file) {
            $file = time() . '-answer.' . $request->file->extension();
            $request->file->move(public_path(LMS_PATH), $file);
            $data['file']=$file;
            $check=ExamAnswer::where(['exam_class_id'=>$exam_class_id,
                            'colleger_id'=>$colleger_id,"exam_question_id"=>$exam_question_id])->first();
        
            if($check){
                $status_data=ExamAnswer::where('id',$check->id)->update($data);
            }else{
                $status_data=ExamAnswer::create($data);
            }

            if(!$status_data){
                $message="File gagal disimpan";
                $code=0;
            }else{
                $message="File berhasil disimpan";
                $code=1;
            }
        }else{
            $message="File gagal disimpan,tidak ada file terdeteksi";
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
        $exam_question_id = $request->input("exam_question_id");
        $exam_class_id = $request->input("exam_class_id");
        $colleger_id = $request->input("colleger_id");
       
        $status_data=ExamAnswer::where(['exam_class_id'=>$exam_class_id,
        'colleger_id'=>$colleger_id,"exam_question_id"=>$exam_question_id])->delete();

        if(!$status_data){
            $message="Gagal menghapus jawaban";
            $code=0;
        }else{
            $message="Sukses menghapus jawaban ";
            $code=1;
        }
        
        $out = [
            "code"=>$code,
            "message" => $message,
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

  
    

   
    
   

}