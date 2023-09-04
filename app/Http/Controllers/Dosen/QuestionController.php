<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;

use App\Models\LecturerStudyProgram;
use App\Models\Question;
use App\Models\Schedule;
use App\Models\SKS;

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

    public function index()
    {
        $data = [];
        return view('dosen/question', $data);
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $data=Question::with('lecturer')->where(["lecturer_id"=>akun('dosen')->id])->orderBy('created_at','desc')->get();
            
            return DataTables::of($data)->addIndexColumn()
                ->editColumn('subject', function($row){
                    return $row->sks->subject->name.' (sem '.$row->sks->semester.')';
                })
                ->editColumn('type_name', function($row){
                    $type_name="";
                    switch ($row->type) {
                        case 0:
                            $type_name="<spam class='text-info'>Essay</span>";
                            break;
                        case 1:
                            $type_name="<spam class='text-success'>Pilihan berganda</span>";
                            break;
                        case 2:
                            $type_name="<spam class='text-danger'>Upload file</span>";
                            break;
                    }
                    return $type_name;
                })
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
                  return $fq;
                })
                ->addColumn('action', function($row){
                    $action='<button class="btn btn-outline-primary btn-rounded btn-xs" onclick="show_detail('.$row->id.')"><i class="fa fa-eye"></i></button>';
                    $action.='<br> <a class="btn btn-outline-info mt-1 btn-rounded btn-xs" href="'.url('dosen/soal/form/edit?id='.$row->id).'"><i class="fa fa-edit"></i></a>';
                    $action.='<br> <button class="btn btn-outline-danger mt-1 btn-rounded btn-xs" onclick="show_delete('.$row->id.')"><i class="fa fa-trash"></i></button>';
                    
                    
                    return $action;
                })
                ->rawColumns(['full_question','action','type_name'])
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
                    $data->type_name="ESSAY";
                    break;
                case 1:
                    $data->type_name="PILIHAN BERGANDA";
                    break;
                case 2:
                    $data->type_name="UPLOAD FILE";
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

    public function add_view()
    {
        $id=akun('dosen')->id;
        $lsp_data=LecturerStudyProgram::where('lecturer_id',$id)->get();
        $in_prodi=[];
        foreach($lsp_data as $item){
            array_push($in_prodi,$item->prodi_id);
        }

        $subject_data=[];
        $sks_data=SKS::whereIn('prodi_id',$in_prodi)->where('status',1)->orderBy('semester','ASC')->get();
        foreach($sks_data as $item){
            $check_schedule=Schedule::where(['sks_id'=>$item->id])->whereHas('schedule_lecturer',function ($q) use($id) {
                $q->where('lecturer_id', '=', $id);
            })->first();
            if($check_schedule){
                array_push($subject_data,$item);
            }
        }

        $data = [
            "subject_data"=>$subject_data,
        ];

        return view('dosen/question_add', $data);
    }

    public function edit_view(Request $request)
    {
        $id = $request->input("id");
        $l_id=akun('dosen')->id;
        $lsp_data=LecturerStudyProgram::where('lecturer_id',$l_id)->get();
        $in_prodi=[];
        foreach($lsp_data as $item){
            array_push($in_prodi,$item->prodi_id);
        }

        $subject_data=[];
        $sks_data=SKS::whereIn('prodi_id',$in_prodi)->where('status',1)->orderBy('semester','ASC')->get();
        foreach($sks_data as $item){
            $check_schedule=Schedule::where(['sks_id'=>$item->id])->whereHas('schedule_lecturer',function ($q) use($id) {
                $q->where('lecturer_id', '=', $id);
            })->first();
            if($check_schedule){
                array_push($subject_data,$item);
            }
        }

        $question_data=Question::where("id",$id)->first();

        $data = [
            "question_data"=>$question_data,
            "subject_data"=>$subject_data,
        ];


        return view('dosen/question_edit', $data);
    }

    public function add(Request $request)
    {
        $lecturer_id = $request->input("lecturer_id");
        $sks_id = $request->input("sks_id");
        $question = $request->input("question");
        $answer = $request->input("answer");
        $choice_answer = $request->input("choice_answer");
        $type = $request->input("type");

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
            $sks=SKS::where('id',$sks_id)->first();
            addLog(1,$this->menu_id,'Menambah soal matkul '.$sks->subject->name);
            return redirect('dosen/soal')->with('success', 'berhasil menambah soal');
        } else {
            return redirect()->back()->with('failed', 'gagal menambah soal');
        }

    }

    public function edit(Request $request)
    {
        $id=$request->input("id");
        $sks_id = $request->input("sks_id");
        $question = $request->input("question");
        $answer = $request->input("answer");
        $choice_answer = $request->input("choice_answer");
        $type = $request->input("type");

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

       
        $status_data = Question::where("id",$id)->update($data);

        if ($status_data) {
            $sks=SKS::where('id',$sks_id)->first();
            addLog(1,$this->menu_id,'Mengedit soal matkul '.$sks->subject->name);
            return redirect('dosen/soal')->with('success', 'berhasil mengedit soal');
        } else {
            return redirect()->back()->with('failed', 'gagal mengedit soal');
        }

    }

    public function delete($id)
    {
        
        $old_data = Question::where(["id" => $id])->first();
        $status_data = Question::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(1,$this->menu_id,'Menghapus doal matkul '.$old_data->sks->subject->name);
            return redirect()->back()->with('success', 'Soal berhasil di hapus');
        } else {
            return redirect()->back()->with('failed', 'Soal gagal di hapus');
        }
    }

}