<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Colleger;
use App\Models\CollegerClass;
use App\Models\Elearning;
use App\Models\ElearningClass;
use App\Models\ElearningDiscussion;
use App\Models\ElearningQuiz;
use App\Models\ElearningView;
use App\Models\LecturerStudyProgram;
use App\Models\Quiz;
use App\Models\QuizClass;
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
       
        $data = [ ];
        return view('mahasiswa/elearning', $data);
    }

    public function ajax_list(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->input("_search")?$request->input("_search"):"";
            $colleger_id = $request->input("_colleger");
            $limit = $request->input("_limit");
            $cc_array=CollegerClass::where('colleger_id',$colleger_id)->pluck('class_id')->all();
            $data=ElearningClass::whereIn('class_id',$cc_array)->where('start','<=',$this->now_hour)->orderBy('start','DESC');
            if($search!=""){
                $data=$data->whereHas('elearning',function($q) use ($search){
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }
            $data=$data->paginate($limit);

            $data->through(function ($item) {
                $status="<span class='badge badge-sm badge-success'>".tr('aktif')."</span>";
                if(strtotime($item->end)<strtotime($this->now_hour)){
                    $status="<span class='badge badge-sm badge-dark'>".tr('selesai')."</span>";
                }
                $result=[
                    "id"=>$item->id,
                    "link"=>url('mahasiswa/elearning/detail?id='.$item->id),
                    "image"=>$item->elearning->image ? asset(LMS_PATH . $item->elearning->image) : url(ELEARNING_G) . str_replace(' ', '_', $item->elearning->name),
                    "name"=>$item->elearning->name,
                    "class"=>$item->class->name,
                    "start"=>date_id($item->start,5),
                    "end"=>date_id($item->end,5),
                    "subject"=>$item->elearning->sks->subject->name,
                   
                    "total_discussion"=>count($item->elearning_discussion),
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


    public function detail(Request $request)
    {
        $id = $request->input("id");
        $colleger_id=akun('mahasiswa')->id;

        $ec_data=ElearningClass::where('id',$id)->first();
        $view=null;
        $check_view=ElearningView::where(['colleger_id'=>$colleger_id,'elearning_id'=>$ec_data->elearning_id,'elearning_class_id'=>$id])->first();
        if(!$check_view && strtotime($ec_data->start)<=strtotime($this->now_hour) && strtotime($ec_data->end)>=strtotime($this->now_hour)){
            $view=date_id($this->now_hour,4);
            ElearningView::create(['colleger_id'=>$colleger_id,'elearning_id'=>$ec_data->elearning_id,'elearning_class_id'=>$id]);
            
        }
        
        $quiz_data=ElearningQuiz::where(["elearning_id"=>$ec_data->id])->get();
        $quiz=[];
        foreach($quiz_data as $item){
            $item->class = QuizClass::where('class_id',$ec_data->class_id)->where('start','<=',$this->now_hour)->where('end','>=',$this->now_hour)->first();
            if($item->class){
                array_push($quiz,$item);
            }
        }

        
        $data = [
            "data"=>$ec_data->elearning,
            "class_id"=>$id,
            "quiz_data"=>$quiz,
            "class_first"=>$ec_data,
            "view"=>$view,
           
        ];
        return view('mahasiswa/elearning_detail', $data);
    }

   
    public function send_discussion(Request $request)
    {
        $colleger_id=$request->input("colleger_id");
        $discussion_id = $request->input("discussion_id");
        $elearning_id = $request->input("elearning_id");
        $elearning_class_id = $request->input("elearning_class_id");
        $comment = $request->input("comment")?$request->input("comment"):"";
       
        $data=[
            "colleger_id"=>$colleger_id,
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
            $colleger_data=Colleger::where('id',$colleger_id)->first();
            $elearning_data=Elearning::where('id',$elearning_id)->first();
            addLog(2,$this->menu_id,$colleger_data->name.' mengomentari '.'"'.$comment.'"'.'  E-learning '.$elearning_data->name);
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