<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Colleger;

class TestController extends Controller
{
    public function index(Request $request)
    {
        // echo Hash::make('admin');
        $rows = Colleger::where(['prodi_id' => 34, 'password' => '0'])->get();
        // print_r($rows);
        foreach ($rows as $row) {
            Colleger::where(['id' => $row->id])->update(['password' => Hash::make($row->nim)]);
        }
    }
}
