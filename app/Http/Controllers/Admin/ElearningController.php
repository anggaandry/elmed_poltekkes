<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Colleger;
use App\Models\CollegerClass;
use App\Models\Elearning;
use App\Models\ElearningClass;
use App\Models\ElearningDiscussion;
use App\Models\ElearningQuiz;
use App\Models\ElearningView;
use App\Models\LecturerStudyProgram;
use App\Models\Major;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\SKS;
use App\Models\StudyProgramFull;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Datatables;

class ElearningController extends Controller
{
    private $menu_id;
    private $key_;

    public function __construct()
    {
        $this->menu_id = 17;
        $this->key_ = 'Materi';
    }

    public function index()
    {
        $prodi_data=StudyProgramFull::orderBy('program_id','ASC')->get();
        $data = [
            "prodi_data"=>$prodi_data,
        ];
        return view('admin/elearning', $data);
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $prodi_id = $request->input("prodi_id");
            $year = $request->input("year");
           
            $data=Elearning::with('lecturer','sks','sks.subject','sks.prodi')->orderBy('created_at','desc')->whereYear('created_at',$year)->get();
            if($prodi_id){
                $data=Elearning::with('lecturer','sks','sks.subject','sks.prodi')->orderBy('created_at','desc')->whereHas('sks', function($q) use($prodi_id){
                    $q->where(['prodi_id'=> $prodi_id]);
                })->whereYear('created_at',$year)->get();
            }
                
            return DataTables::of($data)->addIndexColumn()
                ->editColumn('img', function($row){
                        $img=$row->image ? asset(LMS_PATH . $row->image) : url(ELEARNING_G) . str_replace(' ', '_', $row->name);
                        $key = '<img src="'.$img.'" alt="img"  height="100">';
                        return $key;
                })
                ->editColumn('time', function($row){
                    return date_id($row->created_at,5);
                })
                ->addColumn('lecturer_view', function($row){
                    return title_lecturer($row->lecturer);;
                })
                ->addColumn('prodi', function($row){
                    return $row->sks->prodi->program->name.' - '.$row->sks->prodi->study_program->name.' '.$row->sks->prodi->category->name;
                  })
                ->addColumn('action', function($row){
                    $action='<a class="btn btn-outline-primary btn-rounded btn-xs" href="'.url('4dm1n/elearning/detail?id='.$row->id).'"><i class="fa fa-eye"></i></a>';
                
                    return $action;
                })
                ->rawColumns(['action','img'])
                ->make(true);
        }
    }

  

    public function detail(Request $request)
    {
        $id = $request->input("id");
        $tab = $request->input("tab")?$request->input("tab"):1;
        $elearning_class_id = $request->input("kelas")?$request->input("kelas"):"";
        $elearning_data=Elearning::where('id',$id)->first();
        $elearning_class=ElearningClass::where('elearning_id',$id)->get();
        $class=[];
        foreach ($elearning_class as $item) {
            $item->total_colleger=CollegerClass::where(['class_id'=>$item->class_id])->count();
            $item->total_colleger_view=ElearningView::where(['elearning_class_id'=>$item->id])->count();
           array_push($class,$item);
        }

        $elearning_quiz=ElearningQuiz::where('elearning_id',$id)->get();
        $viewer_data=ElearningView::where('elearning_id',$id)->orderBy('created_at','DESC')->get();

        $discussion=[];
        if($elearning_class_id!=""){
            $discussion_data=ElearningDiscussion::where('elearning_class_id',$elearning_class_id)->where('discussion_id','!=',null)->get();
            foreach($discussion_data as $item){
                $item->sub=ElearningDiscussion::where('elearning_class_id',$elearning_class_id)->where('discussion_id',$item->id)->get();
                array_push($discussion,$item);
            }
            
        }

        $data = [
            "tab"=>$tab,
            "data"=>$elearning_data,
            "class"=>$class,
            "quiz"=>$elearning_quiz,
            "viewer_data"=>$viewer_data,
            "class_id"=>$elearning_class_id,
            "discussion"=>$discussion
        ];
        return view('admin/elearning_detail', $data);
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


        $out = [
            "message"=>"success",
            "result" => $discussion,
        ];
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }



   

}