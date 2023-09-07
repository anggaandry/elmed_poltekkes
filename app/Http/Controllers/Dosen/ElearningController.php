<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\CollegerClass;
use App\Models\Elearning;
use App\Models\ElearningClass;
use App\Models\ElearningDiscussion;
use App\Models\ElearningQuiz;
use App\Models\ElearningView;
use App\Models\LecturerStudyProgram;
use App\Models\Quiz;
use App\Models\Schedule;
use App\Models\SKS;

use Illuminate\Http\Request;

class ElearningController extends Controller
{
    private $menu_id;
    private $now_hour;
    private $key_;
    private $now_date;

    public function __construct()
    {
        $this->menu_id = 17;
        $this->key_ = 'Materi';
        $this->now_hour = date('Y-m-d H:i:s');
        $this->now_date = date('Y-m-d');
    }

    public function index()
    {
        $elearning=ElearningClass::with('elearning_discussion')->where('start','<=',$this->now_hour)->where('end','>=',$this->now_hour)
                    ->whereHas('elearning', function($q) {
                        $q->where(['lecturer_id'=> akun('dosen')->id]);
                    })->get();
       
        $data = [
            "active_elearning"=>$elearning,
        ];
        return view('dosen/elearning', $data);
    }

    public function ajax_list(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->input("_search")?$request->input("_search"):"";
            $lecturer_id = $request->input("_lecturer");
            $limit = $request->input("_limit");

            $data=Elearning::where(['lecturer_id'=>$lecturer_id]);
            if($search!=""){
                $data=$data->where('name', 'like', '%' . $search . '%');
            }
            $data=$data->paginate($limit)->onEachSide(1);

            $data->through(function ($item) {
                $result=[
                    "id"=>$item->id,
                    "link"=>url('dosen/elearning/detail?id='.$item->id),
                    "image"=>$item->image ? asset(LMS_PATH . $item->image) : url(ELEARNING_G) . str_replace(' ', '_', $item->name),
                    "name"=>$item->name,
                    "time"=>date_id($item->created_at,1),
                    "subject"=>$item->sks->subject->name,
                    "prodi"=>$item->sks->prodi->program->name.' - '.$item->sks->prodi->study_program->name.' '.$item->sks->prodi->category->name,
                    "total_discussion"=>count($item->elearning_discussion),
                    "total_class"=>count($item->elearning_class)
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

    public function add_view()
    {
        $lecturer_id=akun('dosen')->id;
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
            "subject_data"=>$subject_data,
        ];
        return view('dosen/elearning_add', $data);
    }

    public function add(Request $request)
    {
        $lecturer_id=akun('dosen')->id;
        $sks_id = $request->input("sks_id");
        $name = $request->input("name");
        $video = $request->input("video");
        $description = $request->input("description");
       

        
        $data=[
            "lecturer_id"=>$lecturer_id,
            "sks_id"=>$sks_id,
            "name"=>$name,
            "video"=>$video,
            "description"=>$description,
        ];

        $image=null;
        if ($request->image) {
            $image = time() . '-elearning-img.' . $request->image->extension();
            $request->image->move(public_path(LMS_PATH), $image);
        }

        if($request->image){
            $data['image']=$image;
        }

        $file1=null;
        if ($request->file1) {
            $file1 = time() . '-file1-' . $request->file1->getClientOriginalName();;
            $request->file1->move(public_path(LMS_PATH), $file1);
        }

        if($request->file1){
            $data['file1']=$file1;
        }

        $file2=null;
        if ($request->file2) {
            $file2 = time() . '-file2-' . $request->file2->getClientOriginalName();;
            $request->file2->move(public_path(LMS_PATH), $file2);
        }

        if($request->file2){
            $data['file2']=$file2;
        }

       
       
        $status_data = Elearning::create($data);
        

        if ($status_data) {
            addLog(1,$this->menu_id,'Menambah E-learning '.$name);
            return redirect('dosen/elearning/detail?id='.$status_data->id)->with('success', tr('berhasil menambah').' '.tr('elearning'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('elearning'));
        }
    }

    public function edit_view(Request $request)
    {
        $id=$request->input("id");
        $kelas=$request->input("kelas")?$request->input("kelas"):"";
        $lecturer_id=akun('dosen')->id;
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

        $data=Elearning::where('id',$id)->first();

        $data = [
            "data"=>$data,
            "subject_data"=>$subject_data,
            "kelas"=>$kelas,
        ];
        return view('dosen/elearning_edit', $data);
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $sks_id = $request->input("sks_id");
        $name = $request->input("name");
        $video = $request->input("video");
        $description = $request->input("description");
        $kelas=$request->input("kelas")?$request->input("kelas"):"";
        
        $data=[
            "sks_id"=>$sks_id,
            "name"=>$name,
            "video"=>$video,
            "description"=>$description,
        ];

        $image=null;
        if ($request->image) {
            $image = time() . '-elearning-img.' . $request->image->extension();
            $request->image->move(public_path(LMS_PATH), $image);
        }

        if($request->image){
            $data['image']=$image;
        }

        $file1=null;
        if ($request->file1) {
            $file1 = time() . '-file1-' . $request->file1->getClientOriginalName();;
            $request->file1->move(public_path(LMS_PATH), $file1);
        }

        if($request->file1){
            $data['file1']=$file1;
        }

        $file2=null;
        if ($request->file2) {
            $file2 = time() . '-file2-' . $request->file2->getClientOriginalName();;
            $request->file2->move(public_path(LMS_PATH), $file2);
        }

        if($request->file2){
            $data['file2']=$file2;
        }
       
        $status_data = Elearning::where("id",$id)->update($data);

        if ($status_data) {
            addLog(1,$this->menu_id,'Mengedit E-learning '.$name);
    
            return redirect('dosen/elearning/detail?id='.$id.'&kelas='.$kelas)->with('success', tr('berhasil mengedit').' '.tr('elearning'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('elearning'));
        }
    }

    public function delete($id)
    {
        $old_data = Elearning::where(["id" => $id])->first();
        $status_data = Elearning::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(1,$this->menu_id,'Menghapus elearning '.$old_data->name);
            return redirect('dosen/elearning')->with('success', tr('elearning').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('elearning').' '.tr('gagal di hapus'));
        }
    }


    public function detail(Request $request)
    {
        $id = $request->input("id");
        $class_id = $request->input("kelas")?$request->input("kelas"):"";
        $tab = $request->input("tab")?$request->input("tab"):1;
        $elearning_class_id = $request->input("tab")?$request->input("kelas"):"";
        $elearning_data=Elearning::where('id',$id)->first();
        

        $discussion=[];
        if($elearning_class_id!=""){
            $discussion_data=ElearningDiscussion::where('elearning_class_id',$elearning_class_id)->where('discussion_id','!=',null)->get();
            foreach($discussion_data as $item){
                $item->sub=ElearningDiscussion::where('elearning_class_id',$elearning_class_id)->where('discussion_id',$item->id)->get();
                array_push($discussion,$item);
            }
            
        }

        $class_data=[];
        $class_da=Classes::where(['year'=>semester_now()->year,'odd'=>semester_now()->odd])->orderBy('name','asc')->get();
        foreach($class_da as $item){
            $check_schedule=Schedule::where(['class_id'=>$item->id])->whereHas('schedule_lecturer',function ($q) {
                                                                        $q->where('lecturer_id', '=', akun('dosen')->id);
                                                                    })->first();
            $check_elearning=ElearningClass::where(["elearning_id"=>$id,"class_id"=>$item->id])->first();
            if($check_schedule && !$check_elearning){
                array_push($class_data,$item);
            }
        }

        $array_class=[];
        if(count($elearning_data->elearning_class)>0){
            if($class_id==""){
                $class_id=$elearning_data->elearning_class[0]->id;
            }

            foreach($elearning_data->elearning_class as $item){
                array_push($array_class,$item->class_id);
            }
            
        }

        $class_first=null;
        if($class_id){
            $class_first=ElearningClass::where("id",$class_id)->first();
        }

        $quiz_data=[];
        if(count($array_class)>0){
            $quizs=Quiz::where(["sks_id"=>$elearning_data->sks_id,"lecturer_id"=>akun('dosen')->id])->whereHas('quiz_class',function ($q) use($array_class) {
                            $q->whereIn('class_id',$array_class);
                        })->get();

            foreach($quizs as $item){
                $check=ElearningQuiz::where(["quiz_id"=>$item->id,"elearning_id"=>$elearning_data->id])->first();
                if(!$check){
                    array_push($quiz_data,$item);
                }
            }
        }

        $viewer_data=[];
        $presence=0;
        if($class_first){
          
            foreach($class_first->class->colleger_class as $item){

                $check=ElearningView::where(['elearning_id'=>$elearning_data->id,
                                            'elearning_class_id'=>$class_first->id,
                                            "colleger_id"=>$item->colleger_id])->first();
                if($check){
                    $presence++;
                    $item->status=$check->created_at;
                }
                
                array_push($viewer_data,$item);
            }
        }

        
        $data = [
            "tab"=>$tab,
            "data"=>$elearning_data,
            "viewer_data"=>$viewer_data,
            "class_id"=>$elearning_class_id,
            "discussion"=>$discussion,
            "class_data"=>$class_data,
            "class_id"=>$class_id,
            "class_first"=>$class_first,
            "quiz_data"=>$quiz_data,
            "presence"=>$presence,
        ];
        return view('dosen/elearning_detail', $data);
    }

    public function add_class(Request $request)
    {
        $elearning_id = $request->input("elearning_id");
        $class_id = $request->input("class_id");
        $start = $request->input("date_start").":".$request->input("time_start");
        $end = $request->input("date_end").":".$request->input("time_end");
        $note = $request->input("note");
        $kelas=$request->input("kelas")?$request->input("kelas"):"";
       
        $status_data = ElearningClass::create([
            "elearning_id"=>$elearning_id,
            "class_id"=>$class_id,
            "start"=>$start,
            "end"=>$end,
            "note"=>$note,
        ]);
        
        if ($status_data) {
            $elearning=Elearning::where("id",$elearning_id)->first();
            $class=Classes::where("id",$class_id)->first();
            addLog(1,$this->menu_id,'Menambah kelas '.$class->name.' ke E-learning '.$elearning->name);
            return redirect()->back()->with('success', tr('berhasil menambah').' '.tr('kelas elearning'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('kelas elearning'));
        }
    }

    public function edit_class(Request $request)
    {
        $id = $request->input("id");
        $start = $request->input("date_start").":".$request->input("time_start");
        $end = $request->input("date_end").":".$request->input("time_end");
        $note = $request->input("note");
        $old_data=ElearningClass::where("id",$id)->first();
        $kelas=$request->input("kelas")?$request->input("kelas"):"";

        $status_data = ElearningClass::where("id",$id)->update([
            "start"=>$start,
            "end"=>$end,
            "note"=>$note,
        ]);
        
        if ($status_data) {
            addLog(1,$this->menu_id,'Mengedit kelas '.$old_data->class->name.' ke E-learning '.$old_data->elearning->name);
            return redirect()->back()->with('success', tr('berhasil mengedit').' '.tr('kelas elearning'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('kelas elearning'));
        }
    }

    public function delete_class($id)
    {
        $old_data = ElearningClass::where(["id" => $id])->first();
        $status_data = ElearningClass::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(1,$this->menu_id,'Menghapus kelas '.$old_data->class->name.' elearning '.$old_data->elearning->name);
            return redirect()->back()->with('success', tr('kelas elearning').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('kelas elearning').' '.tr('gagal di hapus'));
        }
    }

    public function add_quiz(Request $request)
    {
        $elearning_id = $request->input("elearning_id");
        $quiz_id = $request->input("quiz_id");
        $kelas=$request->input("kelas")?$request->input("kelas"):"";
     
        $status_data = ElearningQuiz::create([
            "elearning_id"=>$elearning_id,
            "quiz_id"=>$quiz_id,
        ]);
        
        if ($status_data) {
            $elearning=Elearning::where("id",$elearning_id)->first();
            $quiz=Quiz::where("id",$quiz_id)->first();
            addLog(1,$this->menu_id,'Menambah kuis '.$quiz->name.' ke E-learning '.$elearning->name);
            return redirect('dosen/elearning/detail?tab=4&id='.$elearning_id.'&kelas='.$kelas)->with('success', tr('berhasil menambah').' '.tr('kuis elearning'));
        } else {
            return redirect('dosen/elearning/detail?tab=4&id='.$elearning_id.'&kelas='.$kelas)->with('failed', tr('gagal menambah').' '.tr('kuis elearning'));
        }
    }

    public function delete_quiz(Request $request)
    {
        $id=$request->input("id");
        $kelas=$request->input("kelas")?$request->input("kelas"):"";
        $old_data = ElearningQuiz::where(["id" => $id])->first();
        $status_data = ElearningQuiz::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(1,$this->menu_id,'Menghapus kuis '.$old_data->quiz->name.' elearning '.$old_data->quiz->name);
            return redirect('dosen/elearning/detail?tab=4&id='.$old_data->elearning_id.'&kelas='.$kelas)->with('success', tr('kuis elearning').' '.tr('berhasil di hapus'));
        } else {
            return redirect('dosen/elearning/detail?tab=4&id='.$old_data->elearning_id.'&kelas='.$kelas)->with('failed', tr('kuis elearning').' '.tr('gagal di hapus'));
        }
    }

    public function send_discussion(Request $request)
    {
        $lecturer_id=$request->input("lecturer_id");
        $discussion_id = $request->input("discussion_id");
        $elearning_id = $request->input("elearning_id");
        $elearning_class_id = $request->input("elearning_class_id");
        $comment = $request->input("comment")?$request->input("comment"):"";
       
        $data=[
            "lecturer_id"=>$lecturer_id,
            "discussion_id"=>$discussion_id,
            "elearning_id"=>$elearning_id,
            "elearning_class_id"=>$elearning_class_id,
            "comment"=>$comment,
        ];

        $image=null;
        if ($request->image) {
            $image = time() . '-discussion-img.' . $request->image->extension();
            $request->image->move(public_path(LMS_PATH), $image);
        }

        if($request->image){
            $data['image']=$image;
        }

        $file=null;
        if ($request->file) {
            $file = time() . '-' . $request->file->getClientOriginalName();;
            $request->file->move(public_path(LMS_PATH), $file);
        }

        if($request->file){
            $data['file']=$file;
        }

        $status_data = ElearningDiscussion::create($data);
        

        $message="";
        if ($status_data) {
            $elearning_data=Elearning::where('id',$elearning_id)->first();
            addLog(1,$this->menu_id,$elearning_data->lecturer->name.' mengomentari '.'"'.$comment.'"'.'  E-learning '.$elearning_data->name);
            $message="success";
        } else {
            $message="failed";
        }

        $out = [
            "message"=>$message,
        ];

        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function discussion_list(Request $request)
    {
        $elearning_class_id = $request->input("_class_id");
        
        $discussion=[];
        $discussion_data=ElearningDiscussion::where('elearning_class_id',$elearning_class_id)->where('discussion_id','=',null)->get();
        foreach($discussion_data as $item){

            if($item->colleger_id){
                $item->avatar=$item->colleger->avatar ? asset(AVATAR_PATH . $item->colleger->avatar) : 'https://ui-avatars.com/api/?background=FFFFFF&&name=' . str_replace(' ', '+', $item->colleger->name);
                $item->name=$item->colleger->name;
                $item->status=0;
            }else{
                $item->avatar=$item->lecturer->avatar ? asset(AVATAR_PATH . $item->lecturer->avatar) : 'https://ui-avatars.com/api/?background=FFFFFF&&name=' . str_replace(' ', '+', $item->lecturer->name);
                $item->name=title_lecturer($item->lecturer);
                $item->status=1;
            }

            if($item->file){
                $item->file='<a href="'.asset(LMS_PATH . $item->file).'" class="btn btn-primary btn-xs"
                                download>'.$item->file.' <span class="btn-icon-end"><i
                        class="fa fa-download"></i></span></a>';
            }else{
                $item->file="";
            }

            if($item->image){
                $item->image='<img src="'.asset(LMS_PATH . $item->image).'"width="200" alt="" class="mb-3">';
            }else{
                $item->image="";
            }

            $item->time=ago_model($item->created_at);

            $sub=[];
            $sub_discuss=ElearningDiscussion::where('elearning_class_id',$elearning_class_id)->where('discussion_id',$item->id)->get();

            foreach ($sub_discuss as $n_item) {
                if($n_item->colleger_id){
                    $n_item->avatar=$n_item->colleger->avatar ? asset(AVATAR_PATH . $n_item->colleger->avatar) : 'https://ui-avatars.com/api/?background=FFFFFF&&name=' . str_replace(' ', '+', $n_item->colleger->name);
                    $n_item->name=$n_item->colleger->name;
                    $n_item->status=0;
                }else{
                    $n_item->avatar=$n_item->lecturer->avatar ? asset(AVATAR_PATH . $n_item->lecturer->avatar) : 'https://ui-avatars.com/api/?background=FFFFFF&&name=' . str_replace(' ', '+', $n_item->lecturer->name);
                    $n_item->name=title_lecturer($n_item->lecturer);
                    $n_item->status=1;
                }
    
                if($n_item->file){
                    $n_item->file='<a href="'.asset(LMS_PATH . $n_item->file).'" class="btn btn-primary btn-xs"
                                    download>'.$n_item->file.'<span class="btn-icon-end"><i
                            class="fa fa-download"></i></span></a>';
                }else{
                    $n_item->file="";
                }
    
                if($n_item->image){
                    $n_item->image='<img src="'.asset(LMS_PATH . $n_item->image).'"width="200" alt="" class="mb-3">';
                }else{
                    $n_item->image="";
                }

                $n_item->time=ago_model($n_item->created_at);
                array_push($sub,[
                    "id"=>$item->id,
                    "name"=>$n_item->name,
                    "avatar"=>$n_item->avatar,
                    "comment"=>$n_item->comment,
                    "time"=>$n_item->time,
                    "file"=>$n_item->file,
                    "image"=>$n_item->image,
                    "status"=>$n_item->status,
                ]);
            }
            
            $item->sub=$sub;
            array_push($discussion,[
                "id"=>$item->id,
                "name"=>$item->name,
                "avatar"=>$item->avatar,
                "comment"=>$item->comment,
                "time"=>$item->time,
                "file"=>$item->file,
                "image"=>$item->image,
                "status"=>$item->status,
                "sub"=>$item->sub
            ]);
        }

        $ec_data=ElearningClass::where('id',$elearning_class_id)->first();
        $passed=true;
        if(strtotime($ec_data->start)<=strtotime($this->now_hour) && strtotime($ec_data->end)>=strtotime($this->now_hour)){
            $passed=false;
        }

         $out = [
            "message"=>"success",
            "result" => [
                "passed"=>$passed,
                "discussion"=>$discussion
            ],
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function discussion_id(Request $request)
    {
        $id = $request->input("discussion_id");
        
        $discussion=ElearningDiscussion::where('id',$id)->first();
        
        $out = [
            "message"=>"success",
            "result" => $discussion,
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

   

}