<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;


class RoomController extends Controller
{
    private $menu_id;

    public function __construct()
    {
        $this->menu_id = 1;
    }

    public function index()
    {
        $room_data=Room::orderBy('name','ASC')->get();
        
        $data = [
            "room_data" => $room_data
        ];
        return view('admin/room', $data);
    }

    public function add(Request $request)
    {
        $name = $request->input("name");
        $description = $request->input("description");
       
        $status_data = Room::create([
            "name" => $name,
            "description"=>$description
        ]);

        if ($status_data) {
            addLog(0,$this->menu_id,'Menambah data '.$name);
            return redirect()->back()->with('success', tr('berhasil menambah').' '.tr('ruangan'));
        } else {
            return redirect()->back()->with('failed', tr('gagal menambah').' '.tr('ruangan'));
        }
    }

    public function edit(Request $request)
    {
        $id = $request->input("id");
        $name = $request->input("name");
        $description = $request->input("description");
       
        $update=[
            "name" => $name,
            "description"=>$description
        ];

        $status_data = Room::where(['id'=>$id])->update($update);

        if ($status_data) {
            addLog(0,$this->menu_id,'Mengedit data '.$name);
            return redirect()->back()->with('success', tr('sukses mengedit').' '.tr('ruangan'));
        } else {
            return redirect()->back()->with('failed', tr('gagal mengedit').' '.tr('ruangan'));
        }
    }

    public function delete($id)
    {
        $old_data = Room::where(["id" => $id])->first();
        $status_data = Room::where(["id" => $id])->delete();

        if ($status_data) {
            addLog(0,$this->menu_id,'Menghapus data '.$old_data->name);
            return redirect()->back()->with('success', tr('ruangan').' '.tr('berhasil di hapus'));
        } else {
            return redirect()->back()->with('failed', tr('ruangan').' '.tr('gagal di hapus'));
        }
    }

}