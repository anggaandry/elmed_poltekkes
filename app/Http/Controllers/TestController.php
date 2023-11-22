<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Colleger;
use App\Models\Schedule;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $schedule_id = '1658';
        $data['schedule'] = Schedule::find($schedule_id);

        $absences = Colleger::with(
            ['one_absence' => function ($query) {
                $query->where('start_id', '7688');
            }]
        )->whereHas('colleger_class', function ($q) use ($data) {
            $q->where('class_id', '=', $data['schedule']->class_id);
        })
            ->get();

        print_r($absences);
    }
}
