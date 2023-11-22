<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\AbsenceStart;
use App\Models\AbsenceSubmit;
use App\Models\Calendar;
use App\Models\Classes;
use App\Models\CollegerClass;

use App\Models\Schedule;
use App\Models\ScheduleLecturer;
use App\Models\Semester;
use App\Models\StudyProgramFull;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        return view('admin/absence', $data);
    }

    public function ajax_table(Request $request)
    {
        if ($request->ajax()) {
            $date = $request->input("date");
            $schedule_id  = $request->input("schedule_id");
            $start_id  = $request->input("start_id");

            $data = [];
            if ($schedule_id && $start_id) {
                $schedule_data = Schedule::where('id', $schedule_id)->first();
                $data = CollegerClass::with('class', 'colleger')->where('class_id', $schedule_data->class_id)->get()->sortBy('colleger.name');
            }

            return DataTables::of($data)->addIndexColumn()
                ->addColumn('avatar', function ($row) {
                    $img = $row->colleger->avatar ? asset(AVATAR_PATH . $row->colleger->avatar) : 'https://ui-avatars.com/api/?background=89CFF0&&name=' . str_replace(' ', '+', $row->colleger->name);
                    $ava = '<div class="cropcircle"
                                style="
                                background-image: url(\'' . $img . '\');
                            ">';
                    return $ava;
                })
                ->addColumn('status_view', function ($row) use ($date, $schedule_id, $start_id) {
                    $status = "-";
                    $absence = Absence::where(['start_id' => $start_id, "colleger_id" => $row->colleger->id])->first();
                    if ($absence) {
                        switch ($absence->status) {
                            case 0:
                                $status = '<span class="badge bg-danger">' . tr('alfa') . '</span>';
                                break;
                            case 1:
                                $status = '<span class="badge bg-success">' . tr('hadir') . '</span>';
                                break;
                            case 2:
                                $status = '<span class="badge bg-warning">' . tr('izin') . '</span>';
                                break;
                            default:
                                # code...
                                break;
                        }
                    }

                    return $status;
                })
                ->addColumn('note', function ($row) use ($date, $schedule_id, $start_id) {
                    $note = "-";
                    $absence = Absence::where(['start_id' => $start_id, 'schedule_id' => $schedule_id, "date" => $date, "colleger_id" => $row->colleger->id])->first();
                    if ($absence) {
                        if ($absence->note) {
                            $note = $absence->note;
                        }
                    }

                    return $note;
                })
                ->rawColumns(['avatar', 'status_view'])
                ->make(true);
        }
    }

    public function ajax_class(Request $request)
    {
        $date = $request->input("_date");
        $prodi_id = $request->input("prodi_id");

        $where = [];
        if ($prodi_id) {
            $where['prodi_id'] = $prodi_id;
        }

        $semester = Semester::whereDate('start', '<=', $date)->whereDate('end', '>=', $date)->first();

        if ($semester) {
            $data = Classes::where(['year' => $semester->year, "odd" => $semester->odd])->where($where)->get();
            $out = [
                "message" => "success",
                "result" => $data
            ];
        } else {
            $out = [
                "message" => "Year not found",
                "result" => [],
            ];
        }


        return response()->json($out, 200, array(), JSON_PRETTY_PRINT);
    }

    public function check_status(Request $request)
    {
        $date = $request->input("_date");
        $schedule_id  = $request->input("_schedule");

        $schedule  = Schedule::where('id', $schedule_id)->first();

        $active = 0;
        $session = 0;
        $check_start = null;

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


        $message = tr("failed, jadwal yang berpindah tidak ditemukan");
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

    public function ajax_schedule(Request $request)
    {
        $date = $request->input("_date");
        $class_id = $request->input("_class");

        $day = date('w', strtotime($date));
        $schedule_data = Schedule::where(['class_id' => $class_id, "day" => $day])->get();

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
                $schedule_data = Schedule::where(["day" => $day, "class_id" => $class_id])->orderBy('start', 'ASC')->get();
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

            $schedule_move = AbsenceStart::where("date", $date)->where('moved_from', "!=", null)->whereHas('schedule', function ($q) use ($class_id) {
                $q->where('class_id', '=', $class_id);
            })->get();

            foreach ($schedule_move as $obj) {
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
}
