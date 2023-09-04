<?php

use App\Models\CollegerClass;
use App\Models\Log;
use App\Models\Menu;
use App\Models\RolePermission;
use App\Models\Semester;
use App\Models\University;

if (!function_exists('rupiah')) {
    function rupiah($angka)
    {
        $hasil_rupiah = "Rp. " . number_format($angka, 2, ',', '.');
        return $hasil_rupiah;
    }
}

if (!function_exists('addLog')) {
    function addLog($type, $menu, $log)
    {
        $update = [
            'log' => $log,
            'menu_id' => $menu,
            'type' => $type
        ];

        switch ($type) {
            case 0:
                $user = auth()->guard('admin')->user();
                $update['admin_id'] = $user->id;
                break;
            case 1:
                $user = auth()->guard('dosen')->user();
                $update['lecturer_id'] = $user->id;
                break;
            case 2:
                $user = auth()->guard('mahasiswa')->user();
                $update['colleger_id'] = $user->id;
                break;
        }

        Log::create($update);
    }
}

if (!function_exists('akun')) {
    function akun($type)
    {
        return auth()->guard($type)->user();
    }
}

if (!function_exists('univ')) {
    function univ()
    {
        return University::where('id', UNIVERSITY_ID)->first();
    }
}

if (!function_exists('active_class')) {
    function active_class()
    {
        $mahasiswa = auth()->guard('mahasiswa')->user();
        $now = date('Y-m-d');
        $semester = Semester::whereDate('start', '<=', $now)->whereDate('end', '>=', $now)->first();

        $now_class = CollegerClass::where('colleger_id', $mahasiswa->id)->whereHas('class', function ($q) use ($semester) {
            $q->where(['year' => $semester->year, 'odd' => $semester->odd]);
        })->first();

        $ac_ = null;
        if (!$now_class) {
            $_class = CollegerClass::with('class')->where('colleger_id', $mahasiswa->id)->get();
            if (count($_class) > 0) {
                $_class = $_class->sortByDesc('class.semester');
                $now_class = $_class[0];
                $ac_ = $now_class->class;
                $ac_->last = true;
            }
        } else {
            $ac_ = $now_class->class;
            $ac_->last = false;
        }

        return $ac_;
    }
}

if (!function_exists('random_color')) {
    function random_color()
    {
        $color = ['primary', 'secondary', 'info', 'dark', 'danger', 'warning', 'success'];
        return $color[rand(0, 6)];
    }
}



if (!function_exists('can')) {
    function can($menu, $access)
    {
        $user = auth()->guard('admin')->user();
        $menu_data = Menu::where('keyword', $menu)->orderBy('category', 'ASC')->first();
        if ($menu_data) {
            $check_permission = RolePermission::where(['role_id' => $user->role_id, 'menu_id' => $menu_data->id])->first();
            if ($check_permission) {
                $check = json_decode(json_encode($check_permission), true);
                if ($check[$access . '_access'] == 1) {
                    return true;
                }
            }
        }


        return false;
    }
}

if (!function_exists('can_prodi')) {
    function can_prodi()
    {
        $user = auth()->guard('admin')->user();
        return $user->role->prodi_id;
    }
}

if (!function_exists('can_parent')) {
    function can_parent($parent)
    {
        $user = auth()->guard('admin')->user();
        $menu_data = Menu::where('category', $parent)->get();
        foreach ($menu_data as $item) {
            $check_permission = RolePermission::where(['role_id' => $user->role_id, 'menu_id' => $item->id])->first();
            if ($check_permission) {
                if ($check_permission->view_access == 1) {
                    return true;
                }
            }
        }
        return false;
    }
}

if (!function_exists('semester_now')) {
    function semester_now()
    {
        $now = date('Y-m-d');
        $semester = Semester::whereDate('start', '<=', $now)->whereDate('end', '>=', $now)->first();
        return $semester;
    }
}

if (!function_exists('convert_age')) {
    function convert_age($birthdate)
    {
        $today = date("Y-m-d");
        $diff = date_diff(date_create($birthdate), date_create($today));
        return $diff->format('%y');
    }
}


if (!function_exists('date_id')) {
    function date_id($raw, $mode)
    {
        $day = date("w", strtotime($raw));
        $date = date("d", strtotime($raw));
        $month = date("m", strtotime($raw));
        $year = date("Y", strtotime($raw));
        $hour = date("H", strtotime($raw));
        $minute = date("i", strtotime($raw));

        switch ($mode) {
            case 0:
                return $date . " " . MONTH[$month - 1] . " " . $year;
                break;
            case 1:
                return $date . " " . MONTH[$month - 1] . " " . $year . " " . $hour . ":" . $minute;
                break;
            case 2:
                return DAY[$day] . ', ' . $date . " " . MONTH[$month - 1] . " " . $year . " " . $hour . ":" . $minute;
                break;
            case 3:
                return DAY[$day] . ', ' . $date . " " . MONTH[$month - 1] . " " . $year;
                break;
            case 4:
                return $date . " " . MON[$month - 1] . " " . $year;
                break;
            case 5:
                return $date . " " . MON[$month - 1] . " " . $year . " " . $hour . ":" . $minute;
                break;

            default:
                # code...
                break;
        }
    }
}

if (!function_exists('ago_model')) {
    function ago_model($raw)
    {
        $time_ago = strtotime($raw);
        $cur_time   = time();
        $time_elapsed   = $cur_time - $time_ago;
        $seconds    = $time_elapsed;
        $minutes    = round($time_elapsed / 60);
        $hours      = round($time_elapsed / 3600);
        $days       = round($time_elapsed / 86400);
        $weeks      = round($time_elapsed / 604800);
        $months     = round($time_elapsed / 2600640);
        $years      = round($time_elapsed / 31207680);
        // Seconds
        if ($seconds <= 60) {
            return "baru saja";
        }
        //Minutes
        else if ($minutes <= 60) {
            if ($minutes == 1) {
                return "1 menit lalu";
            } else {
                return "$minutes menit lalu";
            }
        }
        //Hours
        else if ($hours <= 24) {
            if ($hours == 1) {
                return "sejam yang lalu";
            } else {
                return "$hours jam lalu";
            }
        }
        //Days
        else if ($days <= 7) {
            if ($days == 1) {
                return "kemarin";
            } else {
                return "$days hari lalu";
            }
        }
        //Weeks
        else if ($weeks <= 4.3) {
            if ($weeks == 1) {
                return "seminggu yang lalu";
            } else {
                return "$weeks minggu lalu";
            }
        }
        //Months
        else if ($months <= 12) {
            if ($months == 1) {
                return "sebulan yang lalu";
            } else {
                return "$months months ago";
            }
        }
        //Years
        else {
            if ($years == 1) {
                return "setahun yang lalu";
            } else {
                return "$years tahun lalu";
            }
        }
    }
}

if (!function_exists('title_lecturer')) {
    function title_lecturer($lecturer)
    {
        $name = ($lecturer->front_title ? $lecturer->front_title . " " : "") . $lecturer->name . ($lecturer->back_title ? " " . $lecturer->back_title : "");
        return $name;
    }
}