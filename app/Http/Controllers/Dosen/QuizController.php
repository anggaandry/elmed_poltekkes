<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Colleger;
use App\Models\CollegerClass;
use App\Models\Quiz;
use App\Models\QuizClass;

use App\Models\LecturerStudyProgram;
use App\Models\Question;
use App\Models\QuizAbsence;
use App\Models\QuizAnswer;
use App\Models\QuizQuestion;
use App\Models\Schedule;
use App\Models\SKS;

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
        $lecturer_id=akun('dosen')->id;
        $quiz=QuizClass::where('start','<=',$this->now_hour)->where('publish','=',0)
                    ->whereHas('quiz', function($q) use ($lecturer_id) {
                        $q->where(['lecturer_id'=> $lecturer_id]);
                    })->get();

        $lsp_data=LecturerStudyProgram::where('lecturer_id',$lecturer_id)->get();
        $in_prodi=[];
        foreach($lsp_data as $item){
            array_push($in_prodi,$item->prodi_id);
        }

        $subject_data=[];
        $sks_data=SKS::whereIn('prodi_id',$in_prodi)->where('status',1)->orderBy('semester','ASC')->get();
        foreach($sks_data as $item){
            $check_schedule=Schedule::where(['sks_id'=>$item->id])->whereHas('schedule_lecturer',function ($q) use($lecturer_id) {
                $q->where('lecturer_id', '=', $lecturer_id);
            })->first();
            
            if($check_schedule){
                array_push($subject_data,$item);
            }
        }
       
        $data = [
            "active_quiz"=>$quiz,
            "subject_data"=>$subject_data
        ];
        return view('dosen/quiz', $data);
    }

    public function ajax_list(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->input("_search")?$request->input("_search"):"";
            $lecturer_id = $request->input("_lecturer");
            $limit = $request->input("_limit");

            $data=Quiz::where(['lecturer_id'=>$lecturer_id]);
            if($search!=""){
                $data=$data->where('name', 'like', '%' . $search . '%');
            }
            $data=$data->paginate($limit)->onEachSide(1);

            $data->through(function ($item) {
                $result=[
                    "id"=>$item->id,
                    "link"=>url('dosen/kuis/detail?id='.$item->id),
                    "image"=> url(QUIZ_G) . str_replace(' ', '_', $item->name),
                    "name"=>$item->name,
                    "time"=>date_id($item->created_at,1),
                    "subject"=>$item->sks->subject->name,
                    "prodi"=>$item->sks->prodi->program->name.' - '.$item->sks->prodi->study_program->name.' '.$item->sks->prodi->category->name,
                  
                    "total_class"=>count($item->quiz_class),
                    "total_question"=>count($item->quiz_question),
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

  
    public function add(Request $request)
    {
        $lecturer_id=akun('dosen')->id;
        $sks_id = $request->input("sks_id");
        $name = $request->input("name");
        $description = $request->input("description");
       
        $data=[
            "lecturer_id"=>$lecturer_id,
            "sks_id"=>$sks_id,
            "name"=>$name,
            "description"=>$description,
        ];

        $status_data = Quiz::create($data);

        if ($status_data) {
            addLog(1,$this->menu_id,'Menambah Kuis '.$name);
            return redirect('dosen/kuis/detail?id='.$status_data->id)->with('success', tr('berhasil menambah').' '.tr('kuis'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('kuis'));
        }
    }


    public function edit(Request $request)
    {
        $id = $request->input("id");
        $sks_id = $request->input("sks_id");
        $name = $request->input("name");
        $description = $request->input("description");
        $kelas=$request->input("kelas")?$request->input("kelas"):"";
        
        $data=[
            "sks_id"=>$sks_id,
            "name"=>$name,
            "description"=>$description,
        ];
       
        $status_data = Quiz::where("id",$id)->update($data);

        if ($status_data) {
            addLog(1,$this->menu_id,'Mengedit Kuis '.$name);
    
            return redirect()->back()->with('success', tr('berhasil mengedit').' '.tr('kuis'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('kuis'));
        }
    }

    public function delete($id)
    {
        $old_data = Quiz::where(["id" => $id])->first();
        $status_data = Quiz::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(1,$this->menu_id,'Menghapus kuis '.$old_data->name);
            return redirect('dosen/kuis')->with('success', tr('kuis').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('kuis').' '.tr('gagal di hapus'));
        }
    }


    public function detail(Request $request)
    {
        $id = $request->input("id");
        $lecturer_id=akun('dosen')->id;
        $tab = $request->input("tab")?$request->input("tab"):1;
        $quiz_class_id = $request->input("kelas")?$request->input("kelas"):"";
        
        $quiz_data=Quiz::where('id',$id)->first();
       

        if(count($quiz_data->quiz_class)>0){
            if($quiz_class_id==""){
                $quiz_class_id=$quiz_data->quiz_class[0]->id;
            }
        }

        $class_first=null;
        if($quiz_class_id){
            $class_first=QuizClass::where("id",$quiz_class_id)->first();
        }
        
        $cc_data=[];
        if($class_first){
            $colleger_class=CollegerClass::where('class_id',$class_first->class_id)->get()->sortBy('colleger.name');
            foreach ($colleger_class as $item) {
                $item->absence=QuizAbsence::where(['quiz_class_id'=>$quiz_class_id,
                                        'colleger_id'=>$item->colleger_id])->first();
                $item->score=QuizAnswer::where(['quiz_class_id'=>$quiz_class_id,
                                        'colleger_id'=>$item->colleger_id])->sum('score');
                array_push($cc_data,$item);
            }
        }

       

        $class_data=[];
        $class_da=Classes::where(['year'=>semester_now()->year,'odd'=>semester_now()->odd])->orderBy('name','asc')->get();
        foreach($class_da as $item){
            $check_schedule=Schedule::where(['class_id'=>$item->id])->whereHas('schedule_lecturer',function ($q) use($id) {
                $q->where('lecturer_id', '=', akun('dosen')->id);
            })->first();
            $check_elearning=QuizClass::where(["quiz_id"=>$id,"class_id"=>$item->id])->first();
            if($check_schedule && !$check_elearning){
                array_push($class_data,$item);
            }
        }

        $lsp_data=LecturerStudyProgram::where('lecturer_id',$lecturer_id)->get();
        $in_prodi=[];
        foreach($lsp_data as $item){
            array_push($in_prodi,$item->prodi_id);
        }

        $subject_data=[];
        $sks_data=SKS::whereIn('prodi_id',$in_prodi)->where('status',1)->orderBy('semester','ASC')->get();
        foreach($sks_data as $item){
            $check_schedule=Schedule::where(['sks_id'=>$item->id])->whereHas('schedule_lecturer',function ($q) use($lecturer_id) {
                $q->where('lecturer_id', '=', $lecturer_id);
            })->first();
            if($check_schedule){
                array_push($subject_data,$item);
            }
        }

        $data = [
            "tab"=>$tab,
            "data"=>$quiz_data,
            "class_data"=>$class_data,
            "subject_data"=>$subject_data,
            "cc"=>$cc_data,
          
            "class_id"=>$quiz_class_id,
            "class_first"=>$class_first
        ];
      
        return view('dosen/quiz_detail', $data);
    }

    public function add_class(Request $request)
    {
        $quiz_id = $request->input("quiz_id");
        $class_id = $request->input("class_id");
        $start = $request->input("date_start").":".$request->input("time_start");
        $end = $request->input("date_end").":".$request->input("time_end");
        $note = $request->input("note");
        $kelas=$request->input("kelas")?$request->input("kelas"):"";
       
        $status_data = QuizClass::create([
            "quiz_id"=>$quiz_id,
            "class_id"=>$class_id,
            "start"=>$start,
            "end"=>$end,
            "note"=>$note,
        ]);
        
        if ($status_data) {
            $quiz=Quiz::where("id",$quiz_id)->first();
            $class=Classes::where("id",$class_id)->first();
            addLog(1,$this->menu_id,'Menambah kelas '.$class->name.' ke kuis '.$quiz->name);
            return redirect()->back()->with('success', tr('berhasil menambah').' '.tr('kelas untuk kuis'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('kelas untuk kuis'));
        }
    }

    public function edit_class(Request $request)
    {
        $id = $request->input("id");
        $start = $request->input("date_start").":".$request->input("time_start");
        $end = $request->input("date_end").":".$request->input("time_end");
        $note = $request->input("note");
        $old_data=QuizClass::where("id",$id)->first();
        $kelas=$request->input("kelas")?$request->input("kelas"):"";

        $status_data = QuizClass::where("id",$id)->update([
            "start"=>$start,
            "end"=>$end,
            "note"=>$note,
        ]);
        
        if ($status_data) {
            addLog(1,$this->menu_id,'Mengedit kelas '.$old_data->class->name.' ke Kuis '.$old_data->quiz->name);
            return redirect()->back()->with('success', tr('berhasil mengedit').' '.tr('kelas untuk kuis'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('kelas untuk kuis'));
        }
    }

    public function delete_class($id)
    {
        $old_data = QuizClass::where(["id" => $id])->first();
        $status_data = QuizClass::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(1,$this->menu_id,'Menghapus kelas '.$old_data->class->name.' kuis '.$old_data->quiz->name);
            return redirect()->back()->with('success', tr('kelas untuk kuis').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('kelas untuk kuis').' '.tr('gagal di hapus'));
        }
    }

    public function ajax_question(Request $request)
    {
        if ($request->ajax()) {
            $lecturer_id = $request->input("lecturer_id");
            $sks_id = $request->input("sks_id");
            $quiz_id = $request->input("quiz_id");
            $data=Question::with('lecturer')->where(["lecturer_id"=>$lecturer_id,"sks_id"=>$sks_id])
                            ->orderBy('created_at','desc')->get();
            
            return DataTables::of($data)->addIndexColumn()
                ->editColumn('time', function($row){
                    return date_id($row->created_at,5);
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

                    switch ($row->type) {
                        case 0:
                            $fq.='<br><span class="badge badge-info">'.tr('essay').'</span>';
                            break;
                        case 1:
                            $fq.='<br><span class="badge badge-success">'.tr('pilihan berganda').'</span>';
                            break;
                        case 2:
                            $fq.='<br><span class="badge badge-danger">'.tr('upload file').'</span>';
                            break;
                        
                        default:
                            # code...
                            break;
                    }
                    
                 
                  return $fq;
                })
                ->addColumn('action', function($row) use ($quiz_id){
                    $btn='<form action="'.url('dosen/kuis/soal/bank').'" method="POST">
                    '.csrf_field().'
                    <input type="hidden" name="quiz_id" value="'.$quiz_id.'" />
                    <input type="hidden" name="question_id" value="'.$row->id.'" />
                   
                    <div class="form-group mb-3">
                        <input type="number" name="sort" class="form-control" style="text-align: center" placeholder="'.tr("nomor soal").'" required>  
                        
                    </div>
                    <div class="form-group mb-3">
                        <input type="number" name="value" class="form-control" style="text-align: center" placeholder="'.tr("bobot soal").'" required>
                    </div>
                    
                    <button type="submit" class="btn btn-info btn-xs">'.tr('ambil soal').'</button>
                    
                  </form>';
                    
                    return $btn;
                })
                ->rawColumns(['full_question','action'])
                ->make(true);
        }
    }

    public function bank_question(Request $request)
    {
        $quiz_id = $request->input("quiz_id");
        $sort = $request->input("sort");
        $question_id = $request->input("question_id");
        $value=$request->input("value");
       
        $status_data = QuizQuestion::create([
            "quiz_id"=>$quiz_id,
            "sort"=>$sort,
            "question_id"=>$question_id,
            "value"=>$value,
        ]);
        
        if ($status_data) {
            $quiz=Quiz::where("id",$quiz_id)->first();
            addLog(1,$this->menu_id,'Menambah soal no '.$sort.' ke Kuis '.$quiz->name);
            return redirect()->back()->with('success', tr('berhasil menambah').' '.tr('soal untuk kuis'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('soal untuk kuis'));
        }
    }

    public function delete_question($id)
    {
        $old_data = QuizQuestion::where(["id" => $id])->first();
        $status_data = QuizQuestion::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(1,$this->menu_id,'Menghapus soal untuk kuis '.$old_data->quiz->name);
            return redirect()->back()->with('success', tr('soal untuk kuis').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('soal untuk kuis').' '.tr('gagal di hapus'));
        }
    }

    public function add_question_view(Request $request)
    {
        $id = $request->input("id");
        $kelas = $request->input("kelas");
        $quiz_data=Quiz::where("id",$id)->first();
    
        $data = [
            "quiz_data"=>$quiz_data,
            "kelas"=>$kelas
        ];

        return view('dosen/quiz_question_add', $data);
    }

    public function edit_question_view(Request $request)
    {
        $id = $request->input("id");
        $kelas = $request->input("kelas");

        $data=QuizQuestion::where("id",$id)->first();

        $data = [
            "data"=>$data,
            "kelas"=>$kelas,
        ];

        return view('dosen/quiz_question_edit', $data);
    }

    public function add_question(Request $request)
    {
        $sort = $request->input("sort");
        $value = $request->input("value");
        $kelas = $request->input("kelas");
        $quiz_id = $request->input("quiz_id");
        $lecturer_id = $request->input("lecturer_id");
        $lecturer_id = $request->input("lecturer_id");
        $sks_id = $request->input("sks_id");
        $question = $request->input("question");
        $answer = $request->input("answer");
        $type = $request->input("type");
        $choice_answer = $request->input("choice_answer");

        $data=[
            "lecturer_id"=>$lecturer_id,
            "sks_id"=>$sks_id,
            "question"=>$question,
            "answer"=>$answer,
            "type"=>$type,
        ];

        if($type=="1"){
            $choice=[];
            array_push($choice,[
                "choice"=>"A",
                "desc"=> $request->input("choice_a")?$request->input("choice_a"):""
            ]);
            array_push($choice,[
                "choice"=>"B",
                "desc"=> $request->input("choice_b")?$request->input("choice_b"):""
            ]);
            array_push($choice,[
                "choice"=>"C",
                "desc"=> $request->input("choice_c")?$request->input("choice_c"):""
            ]);
            array_push($choice,[
                "choice"=>"D",
                "desc"=> $request->input("choice_d")?$request->input("choice_d"):""
            ]);
            array_push($choice,[
                "choice"=>"E",
                "desc"=> $request->input("choice_e")?$request->input("choice_e"):""
            ]);
            $data['choice']=json_encode($choice);
            $data['choice_answer']=$choice_answer;
        }

        $file=null;
        if ($request->file) {
           
            $file = time() . '-file-' . $request->file->getClientOriginalName();;
            $request->file->move(public_path(DOC_PATH), $file);
        }

        if($request->file){
            $data['file']=$file;
        }

       
        $status_data = Question::create($data);

        if ($status_data) {
            QuizQuestion::create([
                "quiz_id"=>$quiz_id,
                "sort"=>$sort,
                "question_id"=>$status_data->id,
                "value"=>$value,
            ]);

            $sks=SKS::where('id',$sks_id)->first();
            $quiz=Quiz::where('id',$quiz_id)->first();
            addLog(1,$this->menu_id,'Menambah soal matkul '.$sks->subject->name.' untuk kuis '.$quiz->name);
            return redirect('dosen/kuis/detail?id='.$quiz_id.'&kelas='.$kelas)->with('success', tr('berhasil menambah').' '.tr('soal kuis'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('soal kuis'));
        }

    }

    public function edit_question(Request $request)
    {
        $id=$request->input("id");
        $sort = $request->input("sort");
        $value = $request->input("value");
        $kelas = $request->input("kelas");
        $qq_data=QuizQuestion::where('id',$id)->first();

        $sks_id = $request->input("sks_id");
        $question = $request->input("question");
        $answer = $request->input("answer");
        $type = $request->input("type");
        $choice_answer = $request->input("choice_answer");

        $data=[
            "sks_id"=>$sks_id,
            "question"=>$question,
            "answer"=>$answer,
            "type"=>$type,
        ];

        if($type==1){
            $choice=[];
            array_push($choice,[
                "choice"=>"A",
                "desc"=> $request->input("choice_a")?$request->input("choice_a"):""
            ]);
            array_push($choice,[
                "choice"=>"B",
                "desc"=> $request->input("choice_b")?$request->input("choice_b"):""
            ]);
            array_push($choice,[
                "choice"=>"C",
                "desc"=> $request->input("choice_c")?$request->input("choice_c"):""
            ]);
            array_push($choice,[
                "choice"=>"D",
                "desc"=> $request->input("choice_d")?$request->input("choice_d"):""
            ]);
            array_push($choice,[
                "choice"=>"E",
                "desc"=> $request->input("choice_e")?$request->input("choice_e"):""
            ]);
            $data['choice']=json_encode($choice);
            $data['choice_answer']=$choice_answer;
        }

        $file=null;
        if ($request->file) {
           
            $file = time() . '-file-' . $request->file->getClientOriginalName();;
            $request->file->move(public_path(DOC_PATH), $file);
        }

        if($request->file){
            $data['file']=$file;
        }

       
        $status_data = Question::where("id",$qq_data->question_id)->update($data);

        if ($status_data) {
            QuizQuestion::where("id",$id)->update([
                "sort"=>$sort,
                "question_id"=>$qq_data->question_id,
                "value"=>$value,
            ]);

            $sks=SKS::where('id',$sks_id)->first();
            addLog(1,$this->menu_id,'Menambah soal matkul '.$sks->subject->name.' untuk kuis '.$qq_data->quiz->name);
            return redirect('dosen/kuis/detail?id='.$qq_data->quiz_id.'&kelas='.$kelas)->with('success', tr('berhasil mengedit').' '.tr('soal kuis'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('soal kuis'));
        }

    }

    public function ajax_class(Request $request)
    {
        $quiz_class_id = $request->input("qc_id");
        $class_first=QuizClass::where("id",$quiz_class_id)->first();
        $qq=QuizQuestion::where('quiz_id',$class_first->quiz->id)->get();
        $cc_data=[];
        if($class_first){
            $colleger_class=CollegerClass::with('colleger')->where('class_id',$class_first->class_id)->get()->sortBy('colleger.name');
            $ncc=json_decode(json_encode($colleger_class),false);
            foreach ($ncc as $item) {
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
            $absence=tr('mulai menegerjakan')." ".date_id($absence_data->created_at,5);
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

    public function scoring(Request $request)
    {
        $id = $request->input("id");
        $score = $request->input("score");
       
        $status_data=QuizAnswer::where(['id'=>$id])->update(['score'=>$score]);
        
        if(!$status_data){
            $message=tr('gagal memberi nilai kuis');
            $code=0;
        }else{
            $quiz_answer=QuizAnswer::where(['id'=>$id])->first();
            addLog(1,$this->menu_id,"memberi nilai ".$quiz_answer->colleger->name." pada kuis ".$quiz_answer->quiz_class->quiz->name);
            $message=tr('sukses mengisi nilai kuis')." ";
            $code=1;
        }

    
        $out = [
            "code"=>$code,
            "message" => $message,
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function publish(Request $request)
    {
        $id = $request->input("id");
        $value = $request->input("value")?1:0;
       
        $status_data=QuizClass::where(['id'=>$id])->update(['publish'=>$value]);
        
        if(!$status_data){
            $message=tr('gagal mempublish nilai');
            $code=0;
        }else{
            $quiz_class=QuizClass::where(['id'=>$id])->first();
            addLog(1,$this->menu_id,($value==1?"mempublish nilai ":" menarik publish nilai ").$quiz_class->class->name." pada kuis ".$quiz_class->quiz->name);
            $message=($value==1?"Sukses mempublish nilai kelas ":" Sukses menarik publish nilai kelas");
            $code=1;
        }

    
        $out = [
            "code"=>$code,
            "message" => $message,
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

   
    
   

}