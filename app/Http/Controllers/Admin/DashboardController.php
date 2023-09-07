<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Colleger;
use App\Models\Elearning;
use App\Models\Lecturer;
use App\Models\Major;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Semester;
use App\Models\SKS;
use App\Models\StudyProgramFull;

use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DashboardController extends Controller
{
    public function index()
    {
        $where=["status"=>1];
        if(can_prodi()){
            $where['prodi_id']=can_prodi();
        }
        $total_jurusan=Major::count();
        $total_prodi=StudyProgramFull::count();
        $total_matkul=SKS::where($where)->count();

        $total_dosen=Lecturer::count();
        if(can_prodi()){
            $total_dosen=Lecturer::whereHas('lecturer_study_program', function($q){
                $q->where(['prodi_id'=> can_prodi()]);
            })->count();
        }

        $total_mahasiswa=Colleger::where($where)->count();

        $total_elearning=Elearning::count();
        if(can_prodi()){
            $total_elearning=Elearning::whereHas('sks', function($q){
                    $q->where(['prodi_id'=> can_prodi()]);
                })->count();
        }
        
        $total_kuis=Quiz::count();
        if(can_prodi()){
            $total_kuis=Quiz::whereHas('sks', function($q){
                    $q->where(['prodi_id'=> can_prodi()]);
                })->count();
        }

        $total_soal=Question::count();
        if(can_prodi()){
            $total_soal=Question::whereHas('sks', function($q){
                    $q->where(['prodi_id'=> can_prodi()]);
                })->count();
        }

        $prodi_id = "";
        if(can_prodi()){$prodi_id=can_prodi();}
        $prodi_data=StudyProgramFull::orderBy('program_id','ASC')->get();


        $data=[
            "prodi_id"=>$prodi_id,
            "prodi_data"=>$prodi_data,
            "total_jurusan"=>$total_jurusan,
            "total_prodi"=>$total_prodi,
            "total_matkul"=>$total_matkul,
            "total_dosen"=>$total_dosen,
            "total_mahasiswa"=>$total_mahasiswa,
            "total_elearning"=>$total_elearning,
            "total_kuis"=>$total_kuis,
            "total_soal"=>$total_soal
        ];
        return view('admin/dashboard',$data);
    }

    public function lms_chart(Request $request)
    {
        $prodi_id = $request->input("prodi_id");
        $y_series=[];
        $lms_series=[];
        $quiz_series=[];
        
        $semester=Semester::orderBy('start','ASC')->get();
       
        foreach($semester as $item){
            $lms=Elearning::whereBetween('created_at',[$item->start,$item->end])->count(); 
            if($prodi_id){
                $lms=Elearning::whereBetween('created_at',[$item->start,$item->end])->whereHas('sks', function($q) use ($prodi_id){
                    $q->where(['prodi_id'=> $prodi_id]);
                })->count(); 
            }

            $quiz=Quiz::whereBetween('created_at',[$item->start,$item->end])->count(); 
            if($prodi_id){
                $quiz=Quiz::whereBetween('created_at',[$item->start,$item->end])->whereHas('sks', function($q) use ($prodi_id) {
                    $q->where(['prodi_id'=> $prodi_id]);
                })->count(); 
            }
             
            array_push($y_series,($item->odd==1?tr("ganjil"):tr("genap"))." ".$item->year."/".($item->year+1));
            array_push($lms_series,$lms);
            array_push($quiz_series,$quiz);
        }   
        

        $out=[
            'y'=>$y_series,
            'lms'=>$lms_series,
            'quiz'=>$quiz_series,
        ];
      
        $out = [
            "message" => "success",
            "result"  => $out,
        ];
       
      
        return response()->json($out, 200,array(),JSON_PRETTY_PRINT);
    }

   
}