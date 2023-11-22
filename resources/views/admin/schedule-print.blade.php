<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        .ctr {
            vertical-align: middle;
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #ddd;
            padding: 8px;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col" style="text-align: center;">
            <h4>
                Rekapitulasi Absen Kelas {{ $schedule->class->name }}
                <br>
                {{ $schedule->class->prodi->program->name .' '.$schedule->class->prodi->study_program->name .' '.$schedule->class->prodi->category->name }}
                <br>
                Semester {{ $schedule->class->semester }} Tahun Ajaran {{ $schedule->class->year }}/{{ ($schedule->class->year+1) }}
            </h4>
        </div>
    </div>
    <hr>
    <table>
        <tr>
            <td>Mata Kuliah</td>
            <th>: {{ $schedule->sks->subject->name }}</th>
        </tr>
        <tr>
            <td>Dicetak Oleh</td>
            <td>: {{ auth()->guard('admin')->user()->name }}</td>
        </tr>
        <tr>
            <td>Tanggal Cetak</td>
            <td>: {{ date('d-m-Y H:i') }}</td>
        </tr>
    </table>
    <br>
    <h4>Absensi Mahasiswa</h4>
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="ctr" style="vertical-align: middle;">No.</th>
                <th rowspan="2" class="ctr" style="vertical-align: middle;">Nama</th>
                <th rowspan="2" class="ctr" style="vertical-align: middle;">NIM</th>
                <th colspan="{{ count($absence_starts)}}" class="ctr">Pertemuan</th>
            </tr>
            <tr>
                <?php
                $i = 1;
                foreach ($absence_starts as $absence_start) { ?>
                    <th class="ctr">{{ $i }}</th>
                <?php
                    $i++;
                } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $i = 0;
            foreach ($collegers as $colleger) { ?>
                <tr>
                    <td class="ctr">{{ $no }}</td>
                    <td>{{ $colleger['name'] }}</td>
                    <td>{{ $colleger['nim'] }}</td>
                    <?php for ($j = 0; $j < count($absence_starts); $j++) {
                        $status = '';
                        if ($absences[$j][$i]->one_absence) {
                            switch ($absences[$j][$i]->one_absence->status) {
                                case 0:
                                    $status = '<span class="badge bg-danger">A</span>';
                                    break;
                                case 1:
                                    $status = '<span class="badge bg-success">H</span>';
                                    break;
                                case 2:
                                    $status = '<span class="badge bg-warning">I</span>';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                        } else {
                            $status = '<span class="badge bg-danger">A</span>';
                        }
                    ?>
                        <td>{!! $status !!}</td>
                    <?php } ?>
                </tr>
            <?php
                $i++;
                $no++;
            } ?>
        </tbody>
    </table>
    <br><br>

    <h4>Absensi Dosen</h4>
    <table style="page-break-inside: avoid;">
        <thead>
            <tr>
                <th rowspan="2" class="ctr" style="vertical-align: middle;">No.</th>
                <th rowspan="2" class="ctr" style="vertical-align: middle;">Nama</th>
                <th colspan="{{ count($absence_starts)}}" class="ctr">Pertemuan</th>
            </tr>
            <tr>
                <?php
                $i = 1;
                foreach ($absence_starts as $absence_start) { ?>
                    <th class="ctr">{{ $i }}</th>
                <?php
                    $i++;
                } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $i = 0;
            foreach ($lecturers as $lecturer) { ?>
                <tr>
                    <td class="ctr">{{ $no }}</td>
                    <td>{{ $lecturer->lecturer->front_title.' '.$lecturer->lecturer->name.' '.$lecturer->lecturer->back_title }}</td>
                    <?php for ($j = 0; $j < count($absence_starts); $j++) {
                        $status = '';
                        if ($lecturer_absences[$j][$i]->one_absence_submit) {
                            switch ($lecturer_absences[$j][$i]->one_absence_submit->status) {
                                case 0:
                                    $status = '<span class="badge bg-danger">A</span>';
                                    break;
                                case 1:
                                    $status = '<span class="badge bg-success">H</span>';
                                    break;
                                case 2:
                                    $status = '<span class="badge bg-warning">I</span>';
                                    break;
                                default:
                                    # code...
                                    break;
                            }
                        }
                    ?>
                        <td>{!! $status !!}</td>
                    <?php } ?>
                </tr>
            <?php
                $i++;
                $no++;
            } ?>
        </tbody>
    </table>
    <br>
    <br>

    <h4>Keterangan</h4>
    <table style="width: 100%;">
        <thead>
            <th class="ctr">Pertemuan</th>
            <th class="ctr">Tanggal</th>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($absence_starts as $absence_start) {
            ?>
                <tr>
                    <td class="ctr">{{ $i }}</td>
                    <td class="ctr">{{ date('d-m-Y', strtotime($absence_start->date)) }}</td>
                </tr>
            <?php
                $i++;
            } ?>
        </tbody>
    </table>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>

</html>