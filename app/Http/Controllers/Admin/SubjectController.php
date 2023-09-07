<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\StudyProgramFull;
use App\Models\Subject;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;

class SubjectController extends Controller
{
    private $menu_id;
    private $key_;

    public function __construct()
    {
        $this->menu_id = 6;
        $this->key_ = 'Mata kuliah';
    }

    public function index()
    {
        $prodi_data=StudyProgramFull::orderBy('program_id','ASC')->get();
        $data = [
            "prodi_data"=>$prodi_data,
        ];
        return view('admin/subject', $data);
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
           
            $data=Subject::orderBy('name','asc')->get();
                
            return DataTables::of($data)->addIndexColumn()
                ->editColumn('time', function($row){
                    return date_id($row->created_at,5);
                })
                ->addColumn('action', function($row){
                    $action="";
                    if(can($this->key_,'edit')){
                        $action.='<button class="btn btn-outline-info btn-rounded btn-xs" onclick="show_edit('.$row->id.')"><i class="fa fa-edit"></i></button>';
                    }

                    if(can($this->key_,'delete')){
                        $action.=' <button class="btn btn-outline-danger btn-rounded btn-xs" onclick="show_delete('.$row->id.',\''.$row->name.'\')"><i class="fa fa-trash"></i></button>';
                    }
        
                    return $action;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function ajax_id(Request $request)
    {
        $id = $request->input("id");
        $data = Subject::where(["id" => $id])->first();

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
        $name = $request->input("name");

        $status_data = Subject::create([
            "name"=>$name,
        ]);

        if ($status_data) {
            addLog(0,$this->menu_id,'Menambah data '.$name);
            return redirect()->back()->with('success', tr('berhasil menambah').' '.tr('mata kuliah'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('mata kuliah'));
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $name = $request->input("name");
     
        $status_data = Subject::where(['id'=>$id])->update([
            "name"=>$name,
        ]);

        if ($status_data) {
            addLog(0,$this->menu_id,'Mengedit data '.$name);
            return redirect()->back()->with('success', tr('sukses mengedit').' '.tr('mata kuliah'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('mata kuliah'));
        }
    }

    public function delete($id)
    {
        $old_data = Subject::where(["id" => $id])->first();
        $status_data = Subject::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(0,$this->menu_id,'Menghapus data '.$old_data->name);
            return redirect()->back()->with('success', tr('mata kuliah').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('mata kuliah').' '.tr('gagal di hapus'));
        }
    }

}