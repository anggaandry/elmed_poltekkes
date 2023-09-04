<?php

namespace App\Imports;

use App\Models\Colleger;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Http\Request;

class ImportColleger implements ToModel
{
    public $request;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function model(array $row)
    {
        return new Colleger([
            'university_id' => 1,
            'prodi_id' => $this->request->prodi_id,
            'avatar' => "",
            'nim' => $row[0],
            'name' => $row[1],
            'password' => bcrypt($row[0]),
            'gender' => $row[2],
            'religion_id' => $row[3],
            'birthdate' => $row[4],
            'status' => 1,
            'year' => $this->request->year,
        ]);
    }
}
