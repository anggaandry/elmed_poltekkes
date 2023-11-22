<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\AbsenceStart;
use App\Models\AbsenceSubmit;
use App\Models\Calendar;
use App\Models\Classes;
use App\Models\Colleger;
use App\Models\CollegerClass;
use App\Models\Lecturer;
use App\Models\Schedule;
use App\Models\ScheduleLecturer;
use App\Models\Semester;
use Illuminate\Http\Request;
use Yajra\DataTables\Datatables;


class AbsenceController extends Controller
{
    private $menu_id;

    public function __construct()
    {
        $this->menu_id = 11;
    }

    public function index()
    {
        $data = [];
        return view('dosen/absence', $data);
    }

    public function check_status(Request $request)
    {
        $date = $request->input("_date");
        $schedule_id  = $request->input("_schedule");
        $lecturer_id  = $request->input("_lecturer");
        $schedule  = Schedule::where('id', $schedule_id)->first();

        $active = 0;
        $session = 0;
        $check_start = null;
        $check_submit = null;
        $check_move = null;
        $session = AbsenceStart::where(["schedule_id" => $schedule_id, "active" => 1])->count();
        $lecturer_data = [];

        if ($schedule) {
            if (strtotime($date . " " . $schedule->start) <= strtotime(date('Y-m-d H:i:s')) && strtotime($date . " " . $schedule->end) >= strtotime(date('Y-m-d H:i:s'))) {
                $active = 1;
            } else if (strtotime($date . " " . $schedule->end) <= strtotime(date('Y-m-d H:i:s'))) {
                $active = 2;
            }


            $check_start = AbsenceStart::where(['date' => $date, "schedule_id" => $schedule_id, "active" => 1])->first();

            if ($check_start) {
                $check_start->dosen = title_lecturer($check_start->lecturer);

                $check_start->schedule_info = date_id($schedule->date . " " . $schedule->start, 1) . " - " . date('H:i', strtotime($schedule->end));
                if ($check_start->moved == 1) {
                    $check_start->schedule_info = date_id($check_start->date . " " . $check_start->start, 1) . " - " . date("H:i", strtotime($check_start->end)) .
                        "<br><small class='text-danger'>" . tr('pindahan dari') . " " . date_id($schedule->date . " " . $schedule->start, 1) . " - " . date("H:i", strtotime($schedule->end)) . "</small>";
                }
                $check_submit = AbsenceSubmit::where(['start_id' => $check_start->id, "schedule_id" => $schedule_id, "lecturer_id" => $lecturer_id])->first();
                if ($check_submit) {
                    $check_submit->time = date_id($check_submit->created_at, 1);
                }

                $check_start->countdown = date('M d, Y H:i:s', strtotime($date . " " . $check_start->end));
                $lecturer = ScheduleLecturer::where("schedule_id", $schedule->id)->get();
                $no = 0;
                foreach ($lecturer as $item) {
                    $no++;
                    $submit_data = AbsenceSubmit::where(['start_id' => $check_start->id, "schedule_id" => $schedule_id, "lecturer_id" => $item->lecturer_id])->first();
                    $status = tr("belum submit");
                    $status_note = "";
                    $status_color = "dark";

                    if ($submit_data) {
                        if ($submit_data) {
                            $status_note = $submit_data->note;
                        }
                        switch ($submit_data->status) {
                            case 0:
                                $status = tr("alfa");
                                $status_color = "danger";
                                break;
                            case 1:
                                $status = tr("hadir");
                                $status_color = "success";
                                break;
                            case 2:
                                $status = tr("izin");
                                $status_color = "info";
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                    array_push($lecturer_data, [
                        "no" => $no,
                        "position" => $item->sls->name,
                        "position_color" => $item->sls->bg,
                        "lecturer" => title_lecturer($item->lecturer),
                        "status" => $status,
                        "status_note" => $status_note,
                        "status_color" => $status_color,

                    ]);
                }
            }

            $check_move = AbsenceStart::where(['moved_from' => $date, "schedule_id" => $schedule_id])->first();
            if ($check_move) {
                $check_move->time_info = date_id($check_move->date . " " . $check_move->start, 2) . " - " . date('H:i', strtotime($check_move->end));
            }

            $schedule->sks_name = $schedule->sks->subject->name . " (" . $schedule->sks->value . " sks)";
            $schedule->class_name = $schedule->class->name;
            $schedule->day_name = DAY[$schedule->day];
        }

        $out = [
            "message" => "success",
            "result" => [
                "check_start" => $check_start,
                "active" => $active,
                "check_submit" => $check_submit,
                "same_day" => $date == date('Y-m-d'),
                "schedule" => $schedule,
                "session" => $session + 1,
                "lecturer" => $lecturer_data,
                "check_move" => $check_move
            ]
        ];


        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }

    public function check_move(Request $request)
    {
        $date = $request->input("_date");
        $schedule_  = $request->input("_schedule");
        $lecturer_id  = $request->input("_lecturer");

        $message = "failed, jadwal yang berpindah tidak ditemukan";
        $result = [];
        if (str_contains($schedule_, ".")) {
            $arr_schedule = explode(".", $schedule_);
            $schedule_id = $arr_schedule[0];
            $move_id = $arr_schedule[1];
            $schedule  = Schedule::where('id', $schedule_id)->first();
            $move = AbsenceStart::where("id", $move_id)->first();

            $active = 0;
            $session = 0;
            $check_start = null;
            $check_submit = null;
            $session = AbsenceStart::where(["schedule_id" => $schedule_id, "active" => 1])->count();
            $lecturer_data = [];

            if ($move && $schedule) {
                if (strtotime($date . " " . $move->start) <= strtotime(date('Y-m-d H:i:s')) && strtotime($date . " " . $move->end) >= strtotime(date('Y-m-d H:i:s'))) {
                    $active = 1;
                } else if (strtotime($date . " " . $move->end) <= strtotime(date('Y-m-d H:i:s'))) {
                    $active = 2;
                }

                $check_start = $move;
                $check_start->dosen = title_lecturer($check_start->lecturer);
                $check_start->day_name = DAY[date('w', strtotime($check_start->date))];

                $check_start->schedule_info = date_id($schedule->date . " " . $schedule->start, 1) . " - " . date('H:i', strtotime($schedule->end));
                if ($check_start->moved == 1) {
                    $check_start->schedule_info = date_id($check_start->date . " " . $check_start->start, 1) . " - " . date("H:i", strtotime($check_start->end)) .
                        "<br><small class='text-danger'>" . tr('pindahan dari') . " " . date_id($schedule->date . " " . $schedule->start, 1) . " - " . date("H:i", strtotime($schedule->end)) . "</small>";
                }
                $check_submit = AbsenceSubmit::where(['start_id' => $check_start->id, "schedule_id" => $schedule_id, "lecturer_id" => $lecturer_id])->first();
                if ($check_submit) {
                    $check_submit->time = date_id($check_submit->created_at, 1);
                }

                $check_start->countdown = date('M d, Y H:i:s', strtotime($date . " " . $check_start->end));
                $lecturer = ScheduleLecturer::where("schedule_id", $schedule->id)->get();
                $no = 0;
                foreach ($lecturer as $item) {
                    $no++;
                    $submit_data = AbsenceSubmit::where(['start_id' => $check_start->id, "schedule_id" => $schedule_id, "lecturer_id" => $item->lecturer_id])->first();
                    $status = tr("belum submit");
                    $status_note = "";
                    $status_color = "dark";

                    if ($submit_data) {
                        if ($submit_data) {
                            $status_note = $submit_data->note;
                        }
                        switch ($submit_data->status) {
                            case 0:
                                $status = tr("alfa");
                                $status_color = "danger";
                                break;
                            case 1:
                                $status = tr("hadir");
                                $status_color = "success";
                                break;
                            case 2:
                                $status = tr("izin");
                                $status_color = "info";
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                    array_push($lecturer_data, [
                        "no" => $no,
                        "position" => $item->sls->name,
                        "position_color" => $item->sls->bg,
                        "lecturer" => title_lecturer($item->lecturer),
                        "status" => $status,
                        "status_note" => $status_note,
                        "status_color" => $status_color,

                    ]);
                }

                $schedule->sks_name = $schedule->sks->subject->name . " (" . $schedule->sks->value . " sks)";
                $schedule->class_name = $schedule->class->name;
                $schedule->day_name = DAY[$schedule->day];
            }
            $message = "success";
            $result = [
                "check_start" => $check_start,
                "active" => $active,
                "check_submit" => $check_submit,
                "same_day" => $date == date('Y-m-d'),
                "schedule" => $schedule,
                "session" => $session + 1,
                "lecturer" => $lecturer_data,
            ];
        }


        $out = [
            "message" => $message,
            "result" => $result,
        ];


        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $date = $request->input("date");
            $start_id  = $request->input("start_id");


            $data = [];
            if ($start_id) {
                $start = AbsenceStart::where("id", $start_id)->first();
                $schedule_data = Schedule::where('id', $start->schedule_id)->first();
                $data = CollegerClass::with('class', 'colleger')->where('class_id', $schedule_data->class_id)->get()->sortBy('colleger.name');
            }

            return DataTables::of($data)->addIndexColumn()
                ->addColumn('avatar', function ($row) {
                    $img = $row->colleger->avatar ? asset(AVATAR_PATH . $row->colleger->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $row->colleger->name);
                    $ava = '<div class="media d-flex align-content-center justify-content-center p-2 float-start">
                            <div class="avatar me-3">
                                <div class="cropcircle"
                                    style="background-image: url(\'' . $img . '\');">
                                </div>

                            </div>
                            <div class="text-start">
                                
                                <h5 class="mb-0 fs--1">' . $row->colleger->name . '<br><small>NIM. ' . $row->colleger->nim . '</small>
                                </h5>
                            </div>
                        </div>';

                    return $ava;
                })
                ->addColumn('absent', function ($row) use ($date, $start_id) {
                    $absence = Absence::where(['start_id' => $start_id, "date" => $date, "colleger_id" => $row->colleger_id, "status" => 0])->first();
                    $checked = "";
                    if ($absence) {
                        $checked = "checked";
                    }

                    $disabled = "";
                    $submit = AbsenceSubmit::where(['start_id' => $start_id, "lecturer_id" => akun('dosen')->id])->first();
                    if ($submit) {
                        $disabled = "disabled";
                    }

                    $act = '<div class="checkbox-danger">
                            <input ' . $disabled . ' type="radio" class="form-check-input abs" ' . $checked . ' onchange="change_status(0,' . $row->colleger_id . ');" id="absent' . $row->colleger_id . '" name="radioAb' . $row->colleger_id . '">
                          </div>';
                    return $act;
                })

                ->addColumn('present', function ($row) use ($date, $start_id) {
                    $absence = Absence::where(['start_id' => $start_id, "date" => $date, "colleger_id" => $row->colleger_id, "status" => 1])->first();
                    $checked = "";
                    if ($absence) {
                        $checked = "checked";
                    }

                    $disabled = "";
                    $submit = AbsenceSubmit::where(['start_id' => $start_id, "lecturer_id" => akun('dosen')->id])->first();
                    if ($submit) {
                        $disabled = "disabled";
                    }

                    $act = '<div class="checkbox-success">
                            <input ' . $disabled . ' type="radio" class="form-check-input abs" ' . $checked . ' onchange="change_status(1,' . $row->colleger_id . ');" id="present' . $row->colleger_id . '" name="radioAb' . $row->colleger_id . '">
                        </div>';
                    return $act;
                })
                ->addColumn('permit', function ($row) use ($date, $start_id) {
                    $absence = Absence::where(['start_id' => $start_id, "date" => $date, "colleger_id" => $row->colleger_id, "status" => 2])->first();
                    $checked = "";
                    if ($absence) {
                        $checked = "checked";
                    }

                    $disabled = "";
                    $submit = AbsenceSubmit::where(['start_id' => $start_id, "lecturer_id" => akun('dosen')->id])->first();
                    if ($submit) {
                        $disabled = "disabled";
                    }

                    $act = '<div class="checkbox-info">
                                <input ' . $disabled . ' type="radio" class="form-check-input abs" ' . $checked . ' onchange="change_status(2,' . $row->colleger_id . ');" id="permit' . $row->colleger_id . '" name="radioAb' . $row->colleger_id . '">
                           </div>';
                    return $act;
                })
                ->addColumn('note', function ($row) use ($date, $start_id) {
                    $absence = Absence::where(['start_id' => $start_id, "date" => $date, "colleger_id" => $row->colleger_id])->first();
                    $n = "";
                    if ($absence) {
                        $n = $absence->note;
                    }

                    $disabled = "";
                    $submit = AbsenceSubmit::where(['start_id' => $start_id, "lecturer_id" => akun('dosen')->id])->first();
                    if ($submit) {
                        $disabled = "disabled";
                    }

                    $note = '<textarea ' . $disabled . ' class="form-control abs" id="note' . $row->colleger_id . '" oninput="input_note(' . $row->colleger_id . ');">' . $n . '</textarea>';
                    return $note;
                })
                ->addColumn('action', function ($row) use ($date, $start_id) {
                    $disabled = "disabled";
                    $action = "";
                    $absence = Absence::where(['start_id' => $start_id, "date" => $date, "colleger_id" => $row->colleger->id])->first();
                    if ($absence) {
                        $disabled = "";
                        $action = 'onclick="delete_absence(' . $row->colleger->id . ');"';
                    }

                    $disabled = "";
                    $submit = AbsenceSubmit::where(['start_id' => $start_id, "lecturer_id" => akun('dosen')->id])->first();
                    if ($submit) {
                        $disabled = "disabled";
                    }

                    $act = '<button ' . $disabled . ' id="btn' . $row->colleger->id . '" class="btn btn-danger btn-xs  abs" ' . $action . ' ' . $disabled . '><i class="fa fa-trash"></i></textarea>';
                    return $act;
                })
                ->rawColumns(['avatar', 'note', 'present', 'absent', 'permit', 'action'])
                ->make(true);
        }
    }

    public function ajax_schedule(Request $request)
    {
        $date = $request->input("_date");
        $lecturer_id = $request->input("_lecturer");

        $semester = Semester::whereDate('start', '<=', $date)->whereDate('end', '>=', $date)->first();
        $day = date('w', strtotime($date));

        $data = [];
        $holiday = "";
        $event = Calendar::where('date', $date)->first();
        if ($event) {
            $holiday = $event->name;
        } else {
            $schedule_data = [];
            if ($semester) {
                if (session('ic') === true) {
                    $schedule_data = Schedule::where(["day" => $day])
                        ->whereHas('schedule_lecturer', function ($q) use ($lecturer_id) {
                            $q->where('lecturer_id', '=', $lecturer_id);
                        })
                        ->whereHas('class', function ($q) use ($semester) {
                            $q->where('odd', '=', $semester->odd)->where('year', '=', $semester->year);
                        })
                        ->whereHas('class.prodi', function ($q) {
                            $q->where(['category_id' => 6]);
                        })
                        ->orderBy('start', 'ASC')
                        ->get();
                } else {
                    $schedule_data = Schedule::where(["day" => $day])
                        ->whereHas('schedule_lecturer', function ($q) use ($lecturer_id) {
                            $q->where('lecturer_id', '=', $lecturer_id);
                        })
                        ->whereHas('class', function ($q) use ($semester) {
                            $q->where('odd', '=', $semester->odd)->where('year', '=', $semester->year);
                        })
                        ->orderBy('start', 'ASC')
                        ->get();
                }
            }
            foreach ($schedule_data as $item) {
                $name = date('H:i', strtotime($item->start)) . "-" . date('H:i', strtotime($item->end)) . ": " . $item->sks->subject->name . " (" . $item->class->name . ")";
                $sel = "";
                if (strtotime($date . " " . $item->start) <= strtotime(date('Y-m-d H:i')) && strtotime($date . " " . $item->end) >= strtotime(date('Y-m-d H:i'))) {
                    $sel = "selected";
                }
                array_push($data, [
                    "id" => $item->id,
                    "start" => strtotime($item->start),
                    "name" => $name,
                    "selected" => $sel,
                ]);
            }

            $schedule_move = AbsenceStart::where("date", $date)->where('moved_from', "!=", null)->get();
            foreach ($schedule_move as $obj) {
                if (session('ic') === true) {
                    $schedule_lec = ScheduleLecturer::where(['schedule_id' => $obj->schedule_id, 'lecturer_id' => akun('dosen')->id])
                        ->whereHas('schedule.class.prodi', function ($q) {
                            $q->where(['category_id' => 6]);
                        })
                        ->first();
                } else {
                    $schedule_lec = ScheduleLecturer::where(['schedule_id' => $obj->schedule_id, 'lecturer_id' => akun('dosen')->id])->first();
                }
                // $schedule_lec = ScheduleLecturer::where(['schedule_id' => $obj->schedule_id, 'lecturer_id' => akun('dosen')->id])->first();
                if ($schedule_lec) {
                    $item = $obj->schedule;
                    $name = date('H:i', strtotime($obj->start)) . "-" . date('H:i', strtotime($obj->end)) . ": " . $item->sks->subject->name . " (" . $item->class->name . ")" .
                        " " . tr("pindahan dari hari") . " " . date_id($obj->moved_from, 3) . " " . date('H:i', strtotime($item->start)) . "-" . date('H:i', strtotime($item->end));
                    $sel = "";
                    if (strtotime($date . " " . $obj->start) <= strtotime(date('Y-m-d H:i')) && strtotime($date . " " . $obj->end) >= strtotime(date('Y-m-d H:i'))) {
                        $sel = "selected";
                    }
                    array_push($data, [
                        "id" => $item->id . '.' . $obj->id,
                        "start" => strtotime($obj->start),
                        "name" => $name,
                        "selected" => $sel,
                    ]);
                }
            }

            usort($data, function ($a, $b) {
                return strcmp($a['start'], $b['start']);
            });
        }

        if ($holiday == "" && count($data) == 0) {
            $holiday = tr("libur jadwal kosong");
            if (date('w', strtotime($date)) == 0) {
                $holiday = tr("libur hari minggu");
            }
        }

        $out = [
            "message" => "success",
            "holiday" => $holiday,
            "result" => $data
        ];

        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }

    public function status(Request $request)
    {
        $date = $request->input("_date");
        $colleger = $request->input("_colleger");
        $schedule = $request->input("_schedule");
        $status = $request->input("_status");
        $start = $request->input("_start");

        $check_absence = Absence::where(['start_id' => $start, 'date' => $date, 'colleger_id' => $colleger, 'schedule_id' => $schedule])->first();

        $status_array = ["alfa", "hadir", "izin"];

        if ($check_absence) {
            $status_data = Absence::where(['id' => $check_absence->id])->update(['status' => $status]);
        } else {
            $status_data = Absence::create([
                'start_id' => $start,
                'date' => $date,
                'colleger_id' => $colleger,
                'schedule_id' => $schedule,
                'status' => $status
            ]);
        }

        if (!$status_data) {
            $message = tr("gagal mengisi absensi") . " " . $status_array[$status];
            $code = 0;
        } else {
            $colleger_data = Colleger::where("id", $colleger)->first();
            addLog(1, $this->menu_id, tr("membuat absensi"), " " . $status_array[$status] . " " . tr("mahasiswa") . " " . $colleger_data->name . " " . tr("tanggal") . " " . date_id($date, 1));
            $message = tr("sukses mengisi absensi") . " " . $status_array[$status];
            $code = 1;
        }

        $out = [
            "code" => $code,
            "message" => $message,

        ];

        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }

    public function note(Request $request)
    {
        $start = $request->input("_start");
        $date = $request->input("_date");
        $colleger = $request->input("_colleger");
        $schedule = $request->input("_schedule");
        $note = $request->input("_note");

        $check_absence = Absence::where(['start_id' => $start, 'date' => $date, 'colleger_id' => $colleger, 'schedule_id' => $schedule])->first();

        if ($check_absence) {
            $status_data = Absence::where(['id' => $check_absence->id])->update(['note' => $note]);
        } else {
            $status_data = Absence::create([
                'start_id' => $start,
                'date' => $date,
                'colleger_id' => $colleger,
                'schedule_id' => $schedule,
                'note' => $note
            ]);
        }

        if (!$status_data) {
            $message = tr("gagal mengisi catatan absensi");
            $code = 0;
        } else {
            $colleger_data = Colleger::where("id", $colleger)->first();
            addLog(1, $this->menu_id, tr("membuat catatan absensi mahasiswa") . " " . $colleger_data->name . " " . tr("tanggal") . " " . date_id($date, 1));
            $message = tr("sukses mengisi catatan absensi") . " ";
            $code = 1;
        }

        $out = [
            "code" => $code,
            "message" => $message,
        ];

        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }

    public function reset(Request $request)
    {
        $start = $request->input("_start");
        $date = $request->input("_date");
        $colleger = $request->input("_colleger");
        $schedule = $request->input("_schedule");

        $status_data = Absence::where(['start_id' => $start, 'date' => $date, 'colleger_id' => $colleger, 'schedule_id' => $schedule])->delete();

        if (!$status_data) {
            $message = tr("gagal menghapus absensi mahasiswa");
            $code = 0;
        } else {
            $colleger_data = Colleger::where("id", $colleger)->first();
            addLog(1, $this->menu_id, tr("menghapus absensi mahasiswa") . " " . $colleger_data->name . " " . tr("tanggal") . " " . date_id($date, 1));
            $message = tr("sukses menghapus absensi mahasiswa") . " ";
            $code = 1;
        }

        $out = [
            "code" => $code,
            "message" => $message,
        ];

        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }

    public function start(Request $request)
    {
        $id = $request->input("id");
        $schedule_id = $request->input("schedule_id");
        $lecturer_id = $request->input("lecturer_id");
        $session = $request->input("session");
        $date = $request->input("date");
        $start = $request->input("start");
        $end = $request->input("end");

        $activity = $request->input("activity");

        if (!$id) {
            $check = AbsenceStart::where(['schedule_id' => $schedule_id, "date" => $date, "start" => $start])->first();
            if (!$check) {
                $status_data = AbsenceStart::create([
                    "schedule_id" => $schedule_id,
                    "lecturer_id" => $lecturer_id,
                    "session" => $session,
                    "date" => $date,
                    "start" => $start,
                    "end" => $end,
                    "moved" => 0,
                    "activity" => $activity,
                    "active" => 1
                ]);

                $message = tr("memulai absensi gagal") . " ";
                $code = 0;

                if ($status_data) {
                    addLog(1, $this->menu_id, 'memulai absensi ' . date_id($date . " " . $start, 1));
                    $message = tr('memulai absensi sukses');
                    $code = 1;
                }
            } else {
                $code = 1;
                $message = tr('absensi sudah dimulai');
            }
        } else {
            $check = AbsenceStart::where("id", $id)->where("active", 1)->first();
            if (!$check) {
                $status_data = AbsenceStart::where("id", $id)->update([
                    "activity" => $activity,
                    "active" => 1
                ]);

                if ($status_data) {
                    addLog(1, $this->menu_id, 'memulai absensi pindahan ' . date_id($date . " " . $start, 1));
                    $message = tr('memulai absensi pindahan sukses');
                    $code = 1;
                }
            } else {
                $code = 1;
                $message = tr('absensi sudah dimulai');
            }
        }

        $out = [
            "message" => $message,
            "code" => $code,
        ];

        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }

    public function move(Request $request)
    {
        $schedule_id = $request->input("schedule_id");
        $lecturer_id = $request->input("lecturer_id");
        $session = $request->input("session");
        $date = $request->input("date");
        $start = $request->input("start");
        $end = $request->input("end");

        $moved_from = $request->input("moved_from");
        $move_reason = $request->input("move_reason");

        $calendar = Calendar::where("date", $date)->first();
        $weekoff = date('w', strtotime($date));
        if ($calendar) {
            $code = 0;
            $message = tr("tidak bisa di pindahakan ke hari libur") . " (" . $calendar->name . ")";
        } else if ($weekoff == 0) {
            $code = 0;
            $message = tr('tidak bisa di pindahakan ke hari minggu');
        } else {
            $check = AbsenceStart::where(['schedule_id' => $schedule_id, "moved_from" => $moved_from])->first();

            if (!$check) {
                $status_data = AbsenceStart::create([
                    "schedule_id" => $schedule_id,
                    "lecturer_id" => $lecturer_id,
                    "session" => $session,
                    "date" => $date,
                    "start" => $start,
                    "end" => $end,
                    "moved" => 1,
                    "move_reason" => $move_reason,
                    "moved_from" => $moved_from,
                    "active" => 0
                ]);

                $message = tr('memindahkan jadwal gagal ');
                $code = 0;

                if ($status_data) {
                    addLog(1, $this->menu_id, 'memindahkan jadwal ' . date_id($date . " " . $start, 1));
                    $message = tr('memindahkan absensi sukses');
                    $code = 1;
                }
            } else {
                $status_data = AbsenceStart::where('id', $check->id)->update([
                    "schedule_id" => $schedule_id,
                    "lecturer_id" => $lecturer_id,
                    "session" => $session,
                    "date" => $date,
                    "start" => $start,
                    "end" => $end,
                    "moved" => 1,
                    "move_reason" => $move_reason,
                    "moved_from" => $moved_from,
                    "active" => 0
                ]);

                if ($status_data) {
                    addLog(1, $this->menu_id, 'mengedit pindahan jadwal ' . date_id($date . " " . $start, 1));
                    $message = tr('mengedit pindahan jadwal sukses');
                    $code = 1;
                }
            }
        }




        $out = [
            "message" => $message,
            "code" => $code,
        ];

        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }

    public function move_cancel(Request $request)
    {
        $id = $request->input("id");
        $old_data = AbsenceStart::where("id", $id)->first();
        $status_data = AbsenceStart::where("id", $id)->delete();

        if (!$status_data) {
            $message = tr('gagal mereset pemindahan jadwal ');
            $code = 0;
        } else {

            addLog(1, $this->menu_id, "mereset pemindahan jadwal tanggal " . date_id($old_data->moved_from . " " . $old_data->schedule->start, 1));
            $message = tr('sukses mereset pemindahan jadwal');
            $code = 1;
        }

        $out = [
            "code" => $code,
            "message" => $message,
        ];

        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }

    public function activity(Request $request)
    {
        $id = $request->input("id");
        $activity = $request->input("activity");

        $check = AbsenceStart::where("id", $id)->first();

        if ($check) {
            $status_data = AbsenceStart::where("id", $id)->update([
                "activity" => $activity,
            ]);

            $message = tr('mengganti aktivitas pembelajaran gagal ');
            $code = 0;

            if ($status_data) {
                addLog(1, $this->menu_id, 'mengganti aktivitas pembelajaran ' . date_id($check->date . " " . $check->start, 1));
                $message = tr('mengganti aktivitas pembelajaran sukses');
                $code = 1;
            }
        } else {
            $code = 1;
            $message = tr('jadwal tidak ditemukan');
        }


        $out = [
            "message" => $message,
            "code" => $code,
        ];

        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }

    public function submit(Request $request)
    {
        $start_id = $request->input("start_id");
        $schedule_id = $request->input("schedule_id");
        $lecturer_id = $request->input("lecturer_id");
        $status = $request->input("status");
        $note = $request->input("note");


        $check = AbsenceSubmit::where(['schedule_id' => $schedule_id, "start_id" => $start_id, "lecturer_id" => $lecturer_id])->first();

        if (!$check) {
            $status_data = AbsenceSubmit::create([
                "schedule_id" => $schedule_id,
                "lecturer_id" => $lecturer_id,
                "start_id" => $start_id,
                "status" => $status,
                "note" => $note,
            ]);

            $message = tr('submit absensi gagal ');
            $code = 0;

            if ($status_data) {
                $start = AbsenceStart::where("id", $start_id)->first();
                $cc_data = CollegerClass::where("class_id", $start->schedule->class_id)->get();
                foreach ($cc_data as $item) {
                    $check_absence = Absence::where(['start_id' => $start->id, 'date' => $start->date, 'colleger_id' => $item->colleger_id, 'schedule_id' => $start->schedule_id])->first();
                    if (!$check_absence) {
                        $status_data = Absence::create([
                            'start_id' => $start->id,
                            'date' => $start->date,
                            'colleger_id' => $item->colleger_id,
                            'schedule_id' => $start->schedule_id,
                            'status' => 0
                        ]);
                    }
                }

                addLog(1, $this->menu_id, 'submit absensi ' . date_id($start->date . " " . $start->start, 1));
                $message = tr('submit absensi sukses');
                $code = 1;
            }
        } else {
            $code = 1;
            $message = tr('jadwal sudah disubmit');
        }


        $out = [
            "message" => $message,
            "code" => $code,
        ];

        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }

    public function delete(Request $request)
    {
        $start_id = $request->input("start_id");

        $out = [
            "message" => "Berhasil Menghapus Jadwal",
            "code" => 1,
        ];
        $start = AbsenceStart::where("id", $start_id)->first();
        $start->delete();
        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }
}
