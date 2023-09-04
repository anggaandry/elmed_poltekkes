<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\AbsenceStart;
use App\Models\Calendar;
use App\Models\Classes;
use App\Models\Colleger;
use App\Models\CollegerClass;
use App\Models\Log;
use App\Models\Religion;
use App\Models\Schedule;
use App\Models\ScheduleLecturer;
use App\Models\Semester;
use App\Models\StudyProgramFull;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Datatables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportColleger;

class CollegerController extends Controller
{
    private $menu_id;
    private $menu_account_id;
    private $key_;

    public function __construct()
    {
        $this->menu_id = 13;
        $this->menu_account_id = 23;
        $this->key_ = 'Dosen';
    }

    public function index(Request $request)
    {
        $prodi_id = $request->input("prodi_id");
        if (can_prodi()) {
            $prodi_id = can_prodi();
        }
        $status_id = $request->input("status_id") ? $request->input("status_id") : 1;
        $prodi_data = StudyProgramFull::orderBy('program_id', 'ASC')->get();
        $data = [
            "prodi_data" => $prodi_data,
            "prodi_id" => $prodi_id,
            "status_id" => $status_id
        ];
        return view('admin/colleger', $data);
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $prodi_id = $request->input("prodi_id");
            $year = $request->input("year");
            $status_id = $request->input("status_id") ? $request->input("status_id") : "";

            $where = ["year" => $year];
            if ($prodi_id) {
                $where['prodi_id'] = $prodi_id;
            }

            if ($status_id != "") {
                $where['status'] = $status_id;
            }

            $data = Colleger::with('prodi', 'prodi.study_program', 'prodi.category', 'prodi.program')->where($where)->orderBy('name', 'asc')->get();

            return DataTables::of($data)->addIndexColumn()
                ->editColumn('online', function ($row) {
                    return $row->online ? date_id($row->online, 4) : "-";
                })
                ->addColumn('status_view', function ($row) {
                    $status = "";
                    switch ($row->status) {

                        case 1:
                            $status = '<span class="badge bg-success">active</span>';
                            break;
                        case 2:
                            $status = '<span class="badge bg-info">graduated</span>';
                            break;
                        case 3:
                            $status = '<span class="badge bg-danger">D.O</span>';
                            break;
                    }

                    return $status;
                })
                ->addColumn('prodi', function ($row) {

                    return $row->prodi->program->name . ' - ' . $row->prodi->study_program->name . ' ' . $row->prodi->category->name;
                })
                ->addColumn('avatar', function ($row) {
                    $img = $row->avatar ? asset(AVATAR_PATH . $row->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $row->name);
                    $ava = '<div class="cropcircle"
                    style="
                    background-image: url(\'' . $img . '\');
                ">';
                    return $ava;
                })
                ->addColumn('action', function ($row) {
                    $action = '<a class="btn btn-outline-primary btn-rounded btn-xs" href="' . url('4dm1n/mahasiswa/detail?id=' . $row->id) . '"><i class="fa fa-eye"></i></a>';
                    if (can($this->key_, 'edit')) {
                        $action .= ' <a class="btn btn-outline-info btn-rounded btn-xs" href="' . url('4dm1n/mahasiswa/form/edit?id=' . $row->id) . '"><i class="fa fa-edit"></i></a>';
                    }

                    if (can($this->key_, 'delete')) {
                        $action .= ' <button class="btn btn-outline-danger btn-rounded btn-xs" onclick="show_delete(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-trash"></i></button>';
                    }

                    return $action;
                })
                ->rawColumns(['avatar', 'status_view', 'action'])
                ->make(true);
        }
    }

    public function add_view(Request $request)
    {
        $prodi_id = $request->input("prodi_id");
        if (can_prodi()) {
            $prodi_id = can_prodi();
        }
        $prodi_data = StudyProgramFull::orderBy('program_id', 'ASC')->get();
        $religion_data = Religion::orderBy('id', 'ASC')->get();

        $data = [
            "prodi_id" => $prodi_id,
            "prodi_data" => $prodi_data,
            "religion_data" => $religion_data
        ];

        return view('admin/colleger_add', $data);
    }

    public function edit_view(Request $request)
    {
        $id = $request->input("id");
        $route = $request->input("route") ? 1 : 0;

        $colleger_data = Colleger::where('id', $id)->first();
        $religion_data = Religion::orderBy('id', 'ASC')->get();
        $prodi_data = StudyProgramFull::orderBy('program_id', 'ASC')->get();

        $data = [
            "route" => $route,
            "prodi_data" => $prodi_data,
            "colleger_data" => $colleger_data,
            "religion_data" => $religion_data
        ];

        return view('admin/colleger_edit', $data);
    }

    public function add(Request $request)
    {
        $nim = $request->input("nim");
        $year = $request->input("year");
        $name = $request->input("name");
        $gender = $request->input("gender");
        $status = $request->input("status");
        $prodi_id = $request->input("prodi_id");
        $religion_id = $request->input("religion_id");
        $birthdate = $request->input("birthdate");
        $year = $request->input("year");
        $password = Hash::make($nim);

        $data = [
            "name" => $name,
            "nim" => $nim,
            "birthdate" => $birthdate,
            "gender" => $gender,
            "status" => $status,
            "prodi_id" => $prodi_id,
            "religion_id" => $religion_id,
            "password" => $password,
            "year" => $year
        ];

        $check_nim = Colleger::where('nim', $nim)->first();

        if (!$check_nim) {
            $avatar = null;
            if ($request->avatar) {
                $avatar = time() . '-mahasiswa.' . $request->avatar->extension();
                $request->avatar->move(public_path(AVATAR_PATH), $avatar);
            }

            if ($request->avatar) {
                $data['avatar'] = $avatar;
            }

            $status_data = Colleger::create($data);

            if ($status_data) {
                addLog(0, $this->menu_id, 'Menambah mahasiswa ' . $name);
                return redirect('4dm1n/mahasiswa/detail?id=' . $status_data->id)->with('success', 'berhasil menambah mahasiswa');
            } else {
                return redirect()->back()->with('failed', 'gagal menambah mahasiswa');
            }
        } else {
            return redirect()->back()->with('failed', 'nomor identitas sudah ada');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $old_data = Colleger::where(['id' => $id])->first();

        $route = $request->input("route");
        $nim = $request->input("nim");

        $name = $request->input("name");
        $gender = $request->input("gender");
        $status = $request->input("status");
        $religion_id = $request->input("religion_id");
        $prodi_id = $request->input("prodi_id");
        $year = $request->input("year");

        $birthdate = $request->input("birthdate");

        $old_coll = Colleger::where('id', $id)->first();
        $check_nim = Colleger::where('nim', $nim)->first();
        if ($old_coll->nim != $nim) {
            if ($check_nim) {
                return redirect()->back()->with('failed', 'NIM sudah ada');
            }
        }

        $data = [
            "name" => $name,
            "nim" => $nim,
            "birthdate" => $birthdate,
            "gender" => $gender,
            "status" => $status,
            "prodi_id" => $prodi_id,
            "religion_id" => $religion_id,
            "year" => $year,
        ];

        $avatar = null;
        if ($request->avatar) {
            $avatar = time() . '-mahasiswa.' . $request->avatar->extension();
            $request->avatar->move(public_path(AVATAR_PATH), $avatar);
        }

        if ($request->avatar) {
            $data['avatar'] = $avatar;
        }

        $status_data = Colleger::where(['id' => $id])->update($data);


        if ($status_data) {
            if ($request->avatar && $old_data->avatar) {
                $file_path = AVATAR_PATH . $old_data->avatar;
                unlink($file_path);
            }

            addLog(0, $this->menu_id, 'Mengedit mahasiswa ' . $name);
            if ($route == 1) {
                return redirect('4dm1n/mahasiswa/detail?id=' . $id)->with('success', 'sukses mengedit mahasiswa');
            } else {
                return redirect('4dm1n/mahasiswa')->with('success', 'sukses mengedit mahasiswa');
            }
        } else {
            return redirect()->back()->with('failed', 'gagal mengedit mahasiswa');
        }
    }

    public function delete(Request $request)
    {
        $id = $request->route("id");
        $route = $request->input("route") ? 1 : 0;
        $old_data = Colleger::where(["id" => $id])->first();
        $status_data = Colleger::where(["id" => $id])->delete();

        if ($status_data) {
            if ($old_data->avatar) {
                $file_path = AVATAR_PATH . $old_data->avatar;
                unlink($file_path);
            }
            addLog(0, $this->menu_id, 'Menghapus mahasiswa ' . $old_data->name);
            if ($route == 1) {
                return redirect('4dm1n/mahasiswa')->with('success', 'Mahasiswa berhasil di hapus');
            } else {
                return redirect()->back()->with('success', 'Mahasiswa berhasil di hapus');
            }
        } else {
            return redirect()->back()->with('failed', 'Mahasiswa gagal di hapus');
        }
    }

    public function detail(Request $request)
    {
        $id = $request->input("id");
        $tab = $request->input("tab") ? $request->input("tab") : 0;
        $colleger_data = Colleger::where('id', $id)->first();
        $last_activity = Log::where('colleger_id', $id)->orderBy('created_at', 'DESC')->first();
        $class_data = CollegerClass::where('colleger_id', $id)->get()->sortBy(function ($query) {
            return $query->class->semester;
        });

        $data = [
            "tab" => $tab,
            "colleger_data" => $colleger_data,
            "last_activity" => $last_activity,
            "class_data" => $class_data
        ];
        return view('admin/colleger_detail', $data);
    }

    public function ajax_schedule(Request $request)
    {
        if ($request->ajax()) {
            $class_id = $request->input("_class");

            $data = Schedule::with('sks', 'sks.subject', 'class', 'room')->where('class_id', $class_id)->orderBy('day', 'asc')->orderBy('start', 'asc')->get();

            return DataTables::of($data)->addColumn('days', function ($row) {
                return DAY[$row->day];
            })->addColumn('time', function ($row) {
                return date('H:i', strtotime($row->start)) . ' - ' . date('H:i', strtotime($row->end));
            })->addColumn('lecturer', function ($row) {
                $txt = "";
                $dosen = ScheduleLecturer::with('lecturer', 'sls')->where(["schedule_id" => $row->id])->get();
                $txt .= "<ul class='text-start'>";
                $i = 0;
                foreach ($dosen as $obj) {
                    $i++;

                    $txt .= '<li>
                        ' . $i . '. ' . title_lecturer($obj->lecturer) . '
                        <span class="badge badge-xs bg-' . $obj->sls->bg . '" >' . $obj->sls->name . '</span> </li>';
                }

                $txt .= "</ul>";

                return $txt;
            })->rawColumns(['lecturer'])->make(true);
        }
    }

    public function ajax_absence(Request $request)
    {
        if ($request->ajax()) {
            $date = $request->input("_date");
            $colleger_id = $request->input("_colleger");

            $data = [];
            $semester = Semester::whereDate('start', '<=', $date)->whereDate('end', '>=', $date)->first();

            if ($semester) {
                $class_data = CollegerClass::where('colleger_id', $colleger_id)->whereHas('class', function ($q) use ($semester) {
                    $q->where(['odd' => $semester->odd, 'year' => $semester->year]);
                })->orderBy('id', 'DESC')->first();
                $schedule_data = Schedule::with('sks', 'sks.subject', 'class', 'room')->where('class_id', $class_data->class_id)
                    ->where('day', date('w', strtotime($date)))->orderBy('day', 'asc')->orderBy('start', 'asc')->get();
                foreach ($schedule_data as $item) {
                    $item->move = null;
                    $item->time = date_id($date . " " . $item->start, 2) . ' - ' . date('H:i', strtotime($item->end));

                    $lectxt = "";
                    $dosen = ScheduleLecturer::with('lecturer', 'sls')->where(["schedule_id" => $item->id])->get();
                    $lectxt .= "<ul class='text-start'>";
                    $i = 0;
                    foreach ($dosen as $obj) {
                        $i++;
                        $lectxt .= '<li>
                            <span class="mt-5">1. ' . title_lecturer($obj->lecturer) . '</span>
                            <span class="badge badge-xs bg-' . $obj->sls->bg . '" >' . $obj->sls->name . '</span> </li>';
                    }
                    $lectxt .= "</ul>";

                    $status = "-";
                    $note = "-";
                    $session = "-";
                    $activity = "";
                    $absence_start = AbsenceStart::where(['schedule_id' => $item->id, 'date' => $date])->where('moved_from', '=', null)->first();
                    if ($absence_start) {
                        $absence_check = Absence::where(['schedule_id' => $item->id, 'colleger_id' => $colleger_id, 'start_id' => $absence_start->id])->first();
                        $session = "" . $absence_start->session;
                        $activity = $absence_start->activity;
                        if ($absence_check) {
                            if ($absence_check->status) {
                                switch ($absence_check->status) {
                                    case 0:
                                        $status = '<span class="badge bg-danger">Absen</span>';
                                        break;
                                    case 1:
                                        $status = '<span class="badge bg-success">Hadir</span>';
                                        break;
                                    case 2:
                                        $status = '<span class="badge bg-warning">Izin</span>';
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            }

                            if ($absence_check->note) {
                                $note = $absence_check->note;
                            }
                        }
                    }

                    $absence_move = AbsenceStart::where(['schedule_id' => $item->id])->where('moved_from', '=', $date)->first();
                    if ($absence_move) {
                        $absence_check = Absence::where(['schedule_id' => $item->id, 'colleger_id' => $colleger_id, 'start_id' => $absence_move->id])->first();
                        $session = "" . $absence_move->session;
                        $activity = $absence_move->activity;
                        $item->time .= "<br><span class='badge badge-danger badge-xs mt-1'>Dipindahkan ke " . date_id($absence_move->date . " " . $absence_move->start, 2) . ' - ' . date('H:i', strtotime($absence_move->end)) . "</span>";
                        if ($absence_check) {
                            if ($absence_check->status) {
                                switch ($absence_check->status) {
                                    case 0:
                                        $status = '<span class="badge bg-danger">Absen</span>';
                                        break;
                                    case 1:
                                        $status = '<span class="badge bg-success">Hadir</span>';
                                        break;
                                    case 2:
                                        $status = '<span class="badge bg-warning">Izin</span>';
                                        break;
                                    default:
                                        # code...
                                        break;
                                }
                            }

                            if ($absence_check->note) {
                                $note = $absence_check->note;
                            }
                        }
                        $item->move = $absence_move;
                    }


                    $item->nosession = null;
                    if (!$absence_move && !$absence_start && strtotime($item->end) <= strtotime(date('Y-m-d H:i:s'))) {
                        $item->nosession = '<i class="text-danger">Berakhir tanpa sesi kelas</i>';
                    }

                    if ($absence_move) {
                        if ($absence_move->active == 0 && strtotime($item->end) <= strtotime(date('Y-m-d H:i:s'))) {
                            $item->nosession = '<i class="text-danger">Berakhir tanpa sesi kelas</i>';
                        }
                    }

                    $item->dosen = $lectxt;
                    $item->status = $status;
                    $item->note = $note;
                    $item->session = $session;
                    $item->activity = $activity;
                    $item->class_name = $item->class->name;
                    $item->sks_name = $item->sks->subject->name . " (" . $item->sks->value . " SKS)";
                    $item->room_name = $item->room->name;
                    array_push($data, $item);
                }
            }

            $event = Calendar::where('date', $date)->first();
            $holiday = "";
            if ($event) {
                $holiday = $event->name;
                $data = [];
            } else {
                $holiday = "Libur jadwal kosong";
                if (date('w', strtotime($date)) == 0) {
                    $holiday = "Libur hari minggu";
                }
            }

            $out = [
                "message" => "success",
                "result" => $data,
                "holiday" => $holiday,
            ];

            return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
        }
    }


    public function account(Request $request)
    {
        $prodi_id = $request->input("prodi_id");
        if (can_prodi()) {
            $prodi_id = can_prodi();
        }
        $status_id = $request->input("status_id") ? $request->input("status_id") : 1;
        $prodi_data = StudyProgramFull::orderBy('program_id', 'ASC')->get();
        $data = [
            "prodi_data" => $prodi_data,
            "prodi_id" => $prodi_id,
            "status_id" => $status_id
        ];
        return view('admin/account_colleger', $data);
    }

    public function ajax_table_account(Request $request)
    {
        if ($request->ajax()) {
            $prodi_id = $request->input("prodi_id");
            $status_id = $request->input("status_id") ? $request->input("status_id") : "";

            $where = [];
            if ($prodi_id) {
                $where['prodi_id'] = $prodi_id;
            }

            if ($status_id != "") {
                $where['status'] = $status_id;
            }

            $data = Colleger::with('prodi', 'prodi.study_program', 'prodi.category', 'prodi.program')->where($where)->orderBy('name', 'asc')->get();

            return DataTables::of($data)->addIndexColumn()
                ->editColumn('online', function ($row) {
                    return $row->online ? date_id($row->online, 4) : "-";
                })
                ->addColumn('status_view', function ($row) {
                    $status = "";
                    switch ($row->status) {

                        case 1:
                            $status = '<span class="badge bg-success">active</span>';
                            break;
                        case 2:
                            $status = '<span class="badge bg-info">graduated</span>';
                            break;
                        case 3:
                            $status = '<span class="badge bg-danger">D.O</span>';
                            break;
                    }

                    return $status;
                })
                ->addColumn('prodi', function ($row) {

                    return $row->prodi->program->name . ' - ' . $row->prodi->study_program->name . ' ' . $row->prodi->category->name;
                })
                ->addColumn('avatar', function ($row) {
                    $img = $row->avatar ? asset(AVATAR_PATH . $row->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $row->name);
                    $ava = '<div class="cropcircle"
                    style="
                    background-image: url(\'' . $img . '\');
                ">';
                    return $ava;
                })
                ->addColumn('action', function ($row) {
                    $action = '<button class="btn btn-outline-primary btn-rounded btn-xs" onclick="show_status(' . $row->id . ',' . $row->status . ',\'' . $row->name . '\')"><i class="fa fa-edit"></i></button>';

                    $action .= ' <button class="btn btn-outline-info btn-rounded btn-xs" onclick="show_respass(' . $row->id . ',\'' . $row->name . '\')"><i class="fa fa-lock"></i></button>';


                    return $action;
                })
                ->rawColumns(['avatar', 'status_view', 'action'])
                ->make(true);
        }
    }



    public function password_reset(Request $request)
    {
        $id = $request->input("id");
        $route = $request->input("route") ? 1 : 0;
        $prodi_id = $request->input("prodi_id");
        $status_id = $request->input("status_id");
        $account_data = Colleger::where(["id" => $id])->first();

        if ($account_data) {
            $password = Hash::make($account_data->nim);
            $status_data = Colleger::where(['id' => $id])->update(['password' => $password]);
            if ($status_data) {
                addLog(0, $this->menu_account_id, 'Mereset password akun mahasiswa ' . $account_data->name);
                if ($route == 1) {
                    return redirect()->back()->with('success', 'password akun mahasiswa berhasil di reset');
                } else {
                    return redirect('4dm1n/akun/mahasiswa?prodi_id=' . $prodi_id . '&status_id=' . $status_id)->with('success', 'password akun mahasiswa berhasil di reset');
                }
            } else {
                return redirect('4dm1n/akun/mahasiswa?prodi_id=' . $prodi_id . '&status_id=' . $status_id)->with('failed', 'password akun mahasiswa gagal di reset');
            }
        } else {
            return redirect('4dm1n/akun/mahasiswa?prodi_id=' . $prodi_id . '&status_id=' . $status_id)->with('failed', 'ID akun tidak ditemukan');
        }
    }

    public function status(Request $request)
    {
        $id = $request->input("id");
        $prodi_id = $request->input("prodi_id");
        $status_id = $request->input("status_id");
        $status = $request->input("status");
        $account_data = Colleger::where(["id" => $id])->first();

        if ($account_data) {
            $status_data = Colleger::where(['id' => $id])->update(['status' => $status]);
            if ($status_data) {
                $status_name = "";
                switch ($status) {

                    case 1:
                        $status_name = 'Mengaktifkan akun mahasiswa ';
                        break;
                    case 2:
                        $status_name = 'Meluluskan akun mahasiswa';
                        break;
                    case 3:
                        $status_name = 'Mengeluarkan akun mahasiswa';
                        break;
                }

                addLog(0, $this->menu_account_id, $status_name . $account_data->name);
                return redirect('4dm1n/akun/mahasiswa?prodi_id=' . $prodi_id . '&status_id=' . $status_id)->with('success', 'Status mahasiswa berhasil diubah');
            } else {
                return redirect('4dm1n/akun/mahasiswa?prodi_id=' . $prodi_id . '&status_id=' . $status_id)->with('failed', 'Status mahasiswa gagal diubah');
            }
        } else {
            return redirect('4dm1n/akun/mahasiswa?prodi_id=' . $prodi_id . '&status_id=' . $status_id)->with('failed', 'ID akun tidak ditemukan');
        }
    }

    public function import(Request $request)
    {
        try {
            Excel::import(
                new ImportColleger($request),
                $request->file('file')->store('files')
            );
            return redirect()->back()->with('success', 'Berhasil Import Mahasiswa');
        } catch (\Exception $e) {
            return redirect()->back()->with('failed', 'Gagal Import Mahasiswa - ' . $e->getMessage());
        }
    }
}
