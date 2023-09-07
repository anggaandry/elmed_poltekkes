<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\StudyProgramFull;
use App\Models\SKS;
use Illuminate\Http\Request;

use Yajra\DataTables\Datatables;

class SKSController extends Controller
{
    private $menu_id;
    private $key_;

    public function __construct()
    {
        $this->menu_id = 8;
        $this->key_ = 'SKS';
    }

    public function index(Request $request)
    {
        $prodi_id = $request->input("prodi_id");
        if(can_prodi()){$prodi_id=can_prodi();}
        
        $subject_data=Subject::orderBy('name','ASC')->get();
        $prodi_data=StudyProgramFull::orderBy('program_id','ASC')->get();
        $data = [
            "prodi_id"=>$prodi_id,
            "subject_data"=>$subject_data,
            "prodi_data"=>$prodi_data,
        ];

        return view('admin/sks', $data);
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $prodi_id = $request->input("prodi_id");

            $where=[];
            if($prodi_id){
                $where=['prodi_id'=>$prodi_id];
            }
            $data=SKS::with('subject','prodi','prodi.study_program','prodi.category','prodi.program')->where($where)->orderBy('semester','ASC')->get()->sortBy(function($query) {
                return $query->subject->name;
            });
                
            return DataTables::of($data)->addIndexColumn()
                
                ->addColumn('status_view', function($row){
                    return $row->status?'<span class="badge bg-success">'.tr('active').'</span>':'<span class="badge bg-danger">'.tr('unactive').'</span>';
                })
                ->addColumn('action', function($row){
                    $action="";
                    if(can($this->key_,'edit')){
                        $action.='<button class="btn btn-outline-info btn-rounded btn-xs" onclick="show_edit('.$row->id.')"><i class="fa fa-edit"></i></button>';
                    }

                    if(can($this->key_,'delete')){
                        $action.=' <button class="btn btn-outline-danger btn-rounded btn-xs" onclick="show_delete('.$row->id.',\''.$row->subject->name.'\')"><i class="fa fa-trash"></i></button>';
                    }
        
                    return $action;
                })
                ->addColumn('prodi', function($row){

                    return $row->prodi->program->name.' - '.$row->prodi->study_program->name.' '.$row->prodi->category->name;
                  })
                ->rawColumns(['action','status_view'])
                ->make(true);
        }
    }

    public function ajax_id(Request $request)
    {
        $id = $request->input("id");
        $data = SKS::where(["id" => $id])->first();

        if (!$data) {
            $out = [
                "message" => "ID not found",
                "result"=>[],
            ];
        }else{
            $out = [
                "message" => "success",
                "result"=>$data
            ];
        }
        
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

    public function add(Request $request)
    {
        $prodi_id = $request->input("prodi_id");
        $semester = $request->input("semester");
        $subject_id = $request->input("subject_id");
        $code = $request->input("code");
        $value = $request->input("value");
        $status = $request->input("status");

        $subject=Subject::where('id',$subject_id)->first();


        $status_data = SKS::create([
            "prodi_id"=>$prodi_id,
            "semester"=>$semester,
            "subject_id"=>$subject_id,
            "code"=>$code,
            "value"=>$value,
            "status"=>$status
        ]);

        if ($status_data) {
            addLog(0,$this->menu_id,'Menambah matkul prodi '.$subject->name);
            return redirect('4dm1n/sks?prodi_id='.$prodi_id)->with('success', tr('berhasil menambah').' '.tr('matkul prodi'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('matkul prodi'));
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $prodi_id = $request->input("prodi_id");
        $semester = $request->input("semester");
        $subject_id = $request->input("subject_id");
        $code = $request->input("code");
        $value = $request->input("value");
        $status = $request->input("status");
     
        $status_data = SKS::where(['id'=>$id])->update([
            "prodi_id"=>$prodi_id,
            "semester"=>$semester,
            "subject_id"=>$subject_id,
            "code"=>$code,
            "value"=>$value,
            "status"=>$status
        ]);

        $subject=Subject::where('id',$subject_id)->first();

        if ($status_data) {
            addLog(0,$this->menu_id,'Mengedit matkul prodi '.$subject->name);
            return redirect('4dm1n/sks?prodi_id='.$prodi_id)->with('success', tr('sukses mengedit').' '.tr('matkul prodi'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('matkul prodi'));
        }
    }

    public function delete($id)
    {
        $old_data = SKS::where(["id" => $id])->first();
        $status_data = SKS::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(0,$this->menu_id,'Menghapus patkul prodi '.$old_data->subject->name);
            return redirect()->back()->with('success', tr('matkul prodi').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('matkul prodi').' '.tr('gagal di hapus'));
        }
    }

}