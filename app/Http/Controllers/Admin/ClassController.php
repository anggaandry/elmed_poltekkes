<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Colleger;
use App\Models\CollegerClass;
use App\Models\StudyProgramFull;
use Illuminate\Http\Request;

use Yajra\DataTables\Datatables;

class ClassController extends Controller
{
    private $menu_id;
    private $key_;

    public function __construct()
    {
        $this->menu_id = 9;
        $this->key_ = 'Kelas';
    }

    public function index(Request $request)
    {
        $prodi_id = $request->input("prodi");
        if (can_prodi()) {
            $prodi_id = can_prodi();
        }

        $year = $request->input("tahun");
        if (!$year) {
            $year = semester_now()->year;
        }

        $odd = $request->input("odd") ? $request->input("odd") : 1;

        $prodi_data = StudyProgramFull::orderBy('program_id', 'ASC')->get();

        $data = [
            "prodi_id" => $prodi_id,
            "year" => $year,
            "odd" => $odd,
            "prodi_data" => $prodi_data,
        ];

        return view('admin/kelas', $data);
    }

    public function detail(Request $request)
    {
        $id = $request->input("id");

        $class_data = Classes::where('id', $id)->first();
        $cc_data = CollegerClass::where('class_id', $id)->get();

        $ref_class = Classes::whereIn('year', [$class_data->year, $class_data->year - 1])->where('prodi_id', $class_data->prodi_id)->where('odd', $class_data->odd == 1 ? 2 : 1)->orderBy('year', 'ASC')->get();

        $data = [
            "class_data" => $class_data,
            "ref_class" => $ref_class,
            "cc_data" => $cc_data,
        ];

        return view('admin/kelas_detail', $data);
    }

    public function ajax_colleger(Request $request)
    {
        if ($request->ajax()) {
            $class_id = $request->input("class_id");
            $year = $request->input("year");
            $class_data = Classes::where('id', $class_id)->first();
            $class_relate = Classes::where(['semester' => $class_data->semester, 'year' => $class_data->year, 'prodi_id' => $class_data->prodi_id])->pluck('id')->toArray();

            $colleger_data = Colleger::where(['prodi_id' => $class_data->prodi_id, 'status' => 1, 'year' => $year])->orderBy('name', 'ASC')->get();

            $data = [];
            foreach ($colleger_data as $item) {
                $cc = CollegerClass::whereIn('class_id', $class_relate)->where('colleger_id', $item->id)->first();
                if (!$cc) {
                    array_push($data, $item);
                }
            }

            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) use ($class_id) {
                    $action = '<a class="btn btn-outline-primary btn-rounded btn-xs" href="' . url('4dm1n/kelas/colleger/add?class_id=' . $class_id . '&colleger_id=' . $row->id) . '">Tambah <i class="fa fa-plus"></i></a>';
                    return $action;
                })
                ->addColumn('avatar', function ($row) {
                    $img = $row->avatar ? asset(AVATAR_PATH . $row->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $row->name);
                    $ava = '<div class="cropcircle"
                                style="
                                background-image: url(\'' . $img . '\');
                            ">';
                    return $ava;
                })
                ->rawColumns(['action', 'avatar'])
                ->make(true);
        }
    }

    public function add_colleger(Request $request)
    {
        $class_id = $request->input("class_id");
        $colleger_id = $request->input("colleger_id");

        $status_data = CollegerClass::create([
            "class_id" => $class_id,
            "colleger_id" => $colleger_id,
        ]);

        $class = Classes::where('id', $class_id)->first();
        $colleger = Colleger::where('id', $colleger_id)->first();

        if ($status_data) {
            addLog(0, $this->menu_id, 'Menambah mahasiswa ' . $colleger->name . ' ke kelas ' . $class->name);
            return redirect()->back()->with('success', 'berhasil menambah mahasiswa ke kelas');
        } else {
            return redirect()->back()->with('failed', 'gagal menambah mahasiswa ke kelas');
        }
    }

    public function add_previous_colleger(Request $request)
    {

        $class_id = $request->input("class_id");
        $previous_id = $request->input("previous_id");

        $class = Classes::where('id', $class_id)->first();
        $previous = Classes::where('id', $previous_id)->first();

        $previous_data = CollegerClass::where(['class_id' => $previous_id])->get();
        foreach ($previous_data as $item) {
            $status_data = CollegerClass::create([
                "class_id" => $class_id,
                "colleger_id" => $item->colleger_id,
            ]);

            $class = Classes::where('id', $class_id)->first();
        }


        if ($status_data) {
            addLog(0, $this->menu_id, 'Menambah mamindahkan mahasiswa dari kelas ' . $previous->name . ' ke kelas ' . $class->name);
            return redirect()->back()->with('success', 'berhasil memindahkan mahasiswa');
        } else {
            return redirect()->back()->with('failed', 'gagal memindahkan mahasiswa');
        }
    }

    public function delete_colleger($id)
    {
        $old_data = CollegerClass::where(["id" => $id])->first();
        $status_data = CollegerClass::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(0, $this->menu_id, 'Menghapus mahasiswa ' . $old_data->colleger->name . ' dari kelas ' . $old_data->class->name);
            return redirect()->back()->with('success', 'Mahasiswa di kelas ini berhasil di hapus');
        } else {
            return redirect()->back()->with('failed', 'Mahasiswa di kelas ini gagal di hapus');
        }
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $prodi_id = $request->input("prodi_id");
            $year = $request->input("year");
            $odd = $request->input("odd") ? $request->input("odd") : 1;

            $where = ['year' => $year, 'odd' => $odd];
            if ($prodi_id) {
                $where['prodi_id'] = $prodi_id;
            }

            $data = Classes::where($where)->orderBy('name', 'ASC')->get();

            return DataTables::of($data)->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $action = '<a class="btn btn-outline-primary btn-rounded btn-xs" href="' . url('4dm1n/kelas/detail?id=' . $row->id) . '"><i class="fa fa-users"></i></a>';
                    if (can($this->key_, 'edit')) {
                        $action .= ' <button class="btn btn-outline-info btn-rounded btn-xs" onclick="show_edit(' . $row->id . ')"><i class="fa fa-edit"></i></button>';
                    }

                    if (can($this->key_, 'delete')) {
                        $action .= ' <button class="btn btn-outline-danger btn-rounded btn-xs" onclick="show_delete(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-trash"></i></button>';
                    }

                    return $action;
                })
                ->addColumn('prodi', function ($row) {
                    return $row->prodi->program->name . ' - ' . $row->prodi->study_program->name . ' ' . $row->prodi->category->name;
                })
                ->addColumn('year_view', function ($row) {
                    return $row->year . "/" . ($row->year + 1);
                })
                ->addColumn('colleger', function ($row) {
                    $colleger = CollegerClass::where('class_id', $row->id)->count();
                    return $colleger;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function ajax_id(Request $request)
    {
        $id = $request->input("id");
        $data = Classes::where(["id" => $id])->first();

        if (!$data) {
            $out = [
                "message" => "ID not found",
                "result" => [],
            ];
        } else {
            $out = [
                "message" => "success",
                "result" => $data
            ];
        }

        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }

    public function add(Request $request)
    {
        $prodi_id = $request->input("prodi_id");
        $odd = $request->input("odd");
        $semester = $request->input("semester");
        $name = $request->input("name");
        $year = $request->input("year");

        $status_data = Classes::create([
            "prodi_id" => $prodi_id,
            "odd" => $odd,
            "semester" => $semester,
            "name" => $name,
            "year" => $year,
        ]);

        if ($status_data) {
            addLog(0, $this->menu_id, 'Menambah kelas ' . $name);
            return redirect('4dm1n/kelas?prodi=' . $prodi_id . '&tahun=' . $year . '&odd=' . $odd)->with('success', 'berhasil menambah kelas');
        } else {
            return redirect()->back()->with('failed', 'gagal menambah kelas');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $prodi_id = $request->input("prodi_id");
        $odd = $request->input("odd");
        $semester = $request->input("semester");
        $name = $request->input("name");
        $year = $request->input("year");

        $status_data = Classes::where(['id' => $id])->update([
            "prodi_id" => $prodi_id,
            "odd" => $odd,
            "semester" => $semester,
            "name" => $name,
            "year" => $year,
        ]);


        if ($status_data) {
            addLog(0, $this->menu_id, 'Mengedit kelas ' . $name);
            return redirect('4dm1n/kelas?prodi=' . $prodi_id . '&tahun=' . $year . '&odd=' . $odd)->with('success', 'sukses mengedit kelas');
        } else {
            return redirect()->back()->with('failed', 'gagal mengedit kelas');
        }
    }

    public function delete($id)
    {
        $old_data = Classes::where(["id" => $id])->first();
        $status_data = Classes::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(0, $this->menu_id, 'Menghapus kelas ' . $old_data->name);
            return redirect()->back()->with('success', 'Kelas berhasil di hapus');
        } else {
            return redirect()->back()->with('failed', 'Kelas gagal di hapus');
        }
    }
}
