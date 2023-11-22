<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin as admin;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\Dosen as dosen;
use App\Http\Controllers\Mahasiswa as mahasiswa;
use App\Http\Controllers\TestController;
use App\Http\Middleware\ClassCheck;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (!defined('AVATAR_PATH')) define('AVATAR_PATH', 'images/avatar/');
if (!defined('LMS_PATH')) define('LMS_PATH', 'images/lms/');
if (!defined('LOGO_PATH')) define('LOGO_PATH', 'images/logo/');
if (!defined('DOC_PATH')) define('DOC_PATH', 'images/document/');
if (!defined('ELEARNING_G')) define('ELEARNING_G', 'gen-image?o=2&q=');
if (!defined('EXAM_G')) define('EXAM_G', 'gen-image?o=4&q=');
if (!defined('QUIZ_G')) define('QUIZ_G', 'gen-image?o=3&q=');
if (!defined('FILE_PATH')) define('FILE_PATH', 'file/');
if (!defined('UNIVERSITY_ID')) define('UNIVERSITY_ID', '1');
if (!defined('LANG')) define('LANG', ['id', 'en']);
if (!defined('DAY')) define('DAY', ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"]);
if (!defined('DAY_COLOR')) define('DAY_COLOR', ["bg-light-info", "bg-light-dark", "bg-light-danger", "bg-light-success", "bg-light-primary", "bg-light-warning", "bg-light-secondary"]);
if (!defined('MONTH')) define('MONTH', ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]);
if (!defined('MON')) define('MON', ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"]);

Route::get('/', [admin\AuthController::class, 'index']);
Route::get('/gen-image', [GeneralController::class, 'index']);
Route::prefix('4dm1n')->controller(admin\AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login_admin');
    Route::get('/logout', 'logout');
    Route::post('/login', 'login');
});

Route::prefix('dosen')->controller(dosen\AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login_dosen');;
    Route::get('/logout', 'logout');
    Route::post('/login', 'login');
});

Route::prefix('mahasiswa')->controller(mahasiswa\AuthController::class)->group(function () {
    Route::get('/', 'index')->name('login_mahasiswa');;
    Route::get('/logout', 'logout');
    Route::post('/login', 'login');
});

Route::get('/test', [TestController::class, 'index']);


Route::group(['middleware' => ['auth:admin', 'language_manager']], function () {
    Route::prefix('4dm1n')->group(function () {
        Route::prefix('/auth')->controller(admin\AuthController::class)->group(function () {
            Route::post('/password', 'password_change');
            Route::get('/lang', 'lang_change');
            Route::post('/avatar', 'avatar_change');
            Route::post('/profile', 'profile_change');
            Route::post('/online', 'online');
        });

        Route::controller(admin\DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index');
            Route::post('/ajax/chart/lms', 'lms_chart');
        });

        Route::prefix('/ruangan')->controller(admin\RoomController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Ruangan,view');
            Route::post('/add', 'add')->middleware('access:Ruangan,add');
            Route::post('/edit', 'edit')->middleware('access:Ruangan,edit');
            Route::get('/delete/{id}', 'delete')->middleware('access:Ruangan,delete');
        });

        Route::prefix('/jurusan')->controller(admin\MajorController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Jurusan,view');
            Route::post('/add', 'add')->middleware('access:Jurusan,add');
            Route::post('/edit', 'edit')->middleware('access:Jurusan,edit');
            Route::get('/delete/{id}', 'delete')->middleware('access:Jurusan,delete');
        });

        Route::prefix('/jenis_prodi')->controller(admin\SPCController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Jenis prodi,view');
            Route::post('/add', 'add')->middleware('access:Jenis prodi,add');
            Route::post('/edit', 'edit')->middleware('access:Jenis prodi,edit');
            Route::get('/delete/{id}', 'delete')->middleware('access:Jenis prodi,delete');
        });

        Route::prefix('/prodi')->controller(admin\StudyProgramController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Prodi,view');
            Route::post('/add', 'add')->middleware('access:Prodi,add');
            Route::post('/edit', 'edit')->middleware('access:Prodi,edit');
            Route::get('/delete/{id}', 'delete')->middleware('access:Prodi,delete');
        });

        Route::prefix('/full_prodi')->controller(admin\SPFController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Prodi Lengkap,view');
            Route::post('/add', 'add')->middleware('access:Prodi Lengkap,add');
            Route::post('/edit', 'edit')->middleware('access:Prodi Lengkap,edit');
            Route::get('/delete/{id}', 'delete')->middleware('access:Prodi Lengkap,delete');
        });

        Route::prefix('/matkul')->controller(admin\SubjectController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Mata kuliah,view');
            Route::post('/add', 'add')->middleware('access:Mata kuliah,add');
            Route::post('/edit', 'edit')->middleware('access:Mata kuliah,edit');
            Route::post('/ajax/table', 'ajax_table')->middleware('access:Mata kuliah,view');
            Route::post('/ajax/id', 'ajax_id')->middleware('access:Mata kuliah,edit');
            Route::get('/delete/{id}', 'delete')->middleware('access:Mata kuliah,delete');
        });

        Route::prefix('/semester')->controller(admin\SemesterController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Semester,view');
            Route::post('/add', 'add')->middleware('access:Semester,add');
            Route::post('/edit', 'edit')->middleware('access:Semester,edit');
            Route::get('/delete/{id}', 'delete')->middleware('access:Semester,delete');
        });

        Route::prefix('/kalender')->controller(admin\CalendarController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:kalender,view');
            Route::post('/add', 'add')->middleware('access:Kalender,add');
            Route::post('/edit', 'edit')->middleware('access:Kalender,edit');
            Route::get('/delete/{id}', 'delete')->middleware('access:Kalender,delete');
        });

        Route::prefix('/sks')->controller(admin\SKSController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:SKS,view');
            Route::post('/add', 'add')->middleware('access:SKS,add');
            Route::post('/edit', 'edit')->middleware('access:SKS,edit');
            Route::post('/ajax/table', 'ajax_table')->middleware('access:SKS,view');
            Route::post('/ajax/id', 'ajax_id')->middleware('access:SKS,edit');
            Route::get('/delete/{id}', 'delete')->middleware('access:SKS,delete');
        });

        Route::prefix('/kelas')->controller(admin\ClassController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Kelas,view');
            Route::post('/add', 'add')->middleware('access:Kelas,add');
            Route::post('/edit', 'edit')->middleware('access:Kelas,edit');
            Route::post('/ajax/table', 'ajax_table')->middleware('access:Kelas,view');
            Route::post('/ajax/id', 'ajax_id')->middleware('access:Kelas,edit');
            Route::get('/delete/{id}', 'delete')->middleware('access:Kelas,delete');

            Route::get('/detail', 'detail')->middleware('access:Kelas,view');
            Route::post('/ajax/colleger', 'ajax_colleger')->middleware('access:Kelas,view');
            Route::get('/colleger/add', 'add_colleger')->middleware('access:Kelas,add');
            Route::post('/colleger/previous/add', 'add_previous_colleger')->middleware('access:Kelas,add');
            Route::get('/colleger/delete/{id}', 'delete_colleger')->middleware('access:Kelas,delete');
        });

        Route::prefix('/jadwal')->controller(admin\ScheduleController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Jadwal,view');
            Route::post('/ajax/table', 'ajax_table')->middleware('access:Jadwal,view');
            Route::post('/ajax/id', 'ajax_id')->middleware('access:Jadwal,edit');
            Route::post('/ajax/sks', 'ajax_sks')->middleware('access:Jadwal,edit');
            Route::post('/ajax/lecturer', 'ajax_lecturer')->middleware('access:Jadwal,edit');
            Route::post('/add', 'add')->middleware('access:Jadwal,add');
            Route::post('/edit', 'edit')->middleware('access:Jadwal,edit');
            Route::post('/delete', 'delete')->middleware('access:Jadwal,delete');
            Route::get('/print/{id}', 'print')->middleware('access:Jadwal,view');

            Route::post('/lecturer/add', 'lecturer_add')->middleware('access:Jadwal,add');
            Route::post('/lecturer/edit', 'lecturer_edit')->middleware('access:Jadwal,edit');
            Route::post('/lecturer/delete', 'lecturer_delete')->middleware('access:Jadwal,delete');
        });

        Route::prefix('/absensi')->controller(admin\AbsenceController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Absensi,view');
            Route::post('/ajax/table', 'ajax_table')->middleware('access:Absensi,view');
            Route::post('/ajax/class', 'ajax_class')->middleware('access:Absensi,view');
            Route::post('/ajax/jadwal', 'ajax_schedule')->middleware('access:Absensi,view');
            Route::post('/check_status', 'check_status')->middleware('access:Absensi,view');
            Route::post('/check_move', 'check_move')->middleware('access:Absensi,view');
        });

        Route::prefix('/dosen')->controller(admin\LecturerController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Dosen,view');
            Route::get('/detail', 'detail')->middleware('access:Dosen,view');
            Route::post('/ajax/table', 'ajax_table')->middleware('access:Dosen,view');
            Route::get('/form/add', 'add_view')->middleware('access:Dosen,add');
            Route::get('/form/edit', 'edit_view')->middleware('access:Dosen,edit');
            Route::post('/add', 'add')->middleware('access:Dosen,add');
            Route::post('/edit', 'edit')->middleware('access:Dosen,edit');
            Route::get('/delete', 'delete')->middleware('access:Dosen,delete');

            Route::post('/prodi/add', 'add_prodi')->middleware('access:Dosen,add');
            Route::get('/prodi/status', 'status_prodi')->middleware('access:Dosen,edit');
            Route::get('/prodi/delete/{id}', 'delete_prodi')->middleware('access:Dosen,delete');

            Route::post('/ajax/class', 'ajax_class')->middleware('access:Dosen,view');
            Route::post('/ajax/schedule', 'ajax_schedule')->middleware('access:Dosen,view');
        });

        Route::prefix('/mahasiswa')->controller(admin\CollegerController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Mahasiswa,view');
            Route::get('/detail', 'detail')->middleware('access:Mahasiswa,view');
            Route::post('/ajax/table', 'ajax_table')->middleware('access:Mahasiswa,view');
            Route::get('/form/add', 'add_view')->middleware('access:Mahasiswa,add');
            Route::get('/form/edit', 'edit_view')->middleware('access:Mahasiswa,edit');
            Route::post('/add', 'add')->middleware('access:Mahasiswa,add');
            Route::post('/import', 'import')->middleware('access:Mahasiswa,add');
            Route::post('/edit', 'edit')->middleware('access:Mahasiswa,edit');
            Route::get('/delete/{id}', 'delete')->middleware('access:Mahasiswa,delete');
            Route::post('/ajax/schedule', 'ajax_schedule')->middleware('access:Mahasiswa,view');
            Route::post('/ajax/absence', 'ajax_absence')->middleware('access:Mahasiswa,view');
        });

        Route::prefix('/soal')->controller(admin\QuestionController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Soal,view');;
            Route::post('/ajax/table', 'ajax_table')->middleware('access:Soal,view');;
            Route::post('/ajax/id', 'ajax_id')->middleware('access:Soal,view');;
        });

        Route::prefix('/elearning')->controller(admin\ElearningController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Materi,view');
            Route::get('/detail', 'detail')->middleware('access:Materi,view');
            Route::post('/ajax/table', 'ajax_table')->middleware('access:Materi,view');
            Route::post('/ajax/discussion/list', 'discussion_list');
        });

        Route::prefix('/kuis')->controller(admin\QuizController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Kuis,view');
            Route::get('/detail', 'detail')->middleware('access:Kuis,view');
            Route::post('/ajax/table', 'ajax_table')->middleware('access:Kuis,view');
            Route::post('/ajax/correction', 'ajax_correction')->middleware('access:Kuis,view');
            Route::post('/ajax/class', 'ajax_class');
        });

        Route::prefix('/ujian')->controller(admin\ExamController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Ujian,view');
            Route::get('/detail', 'detail')->middleware('access:Ujian,view');
            Route::post('/ajax/table', 'ajax_table')->middleware('access:Ujian,view');
            Route::post('/ajax/correction', 'ajax_correction')->middleware('access:Ujian,view');
            Route::post('/ajax/class', 'ajax_class');
        });

        Route::prefix('/role')->controller(admin\RoleController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Role,view');
            Route::get('/form/add', 'add_view')->middleware('access:Role,add');
            Route::get('/form/edit', 'edit_view')->middleware('access:Role,edit');
            Route::post('/add', 'add')->middleware('access:Role,add');
            Route::post('/edit', 'edit')->middleware('access:Role,edit');
            Route::get('/delete/{id}', 'delete')->middleware('access:Role,delete');
        });

        Route::prefix('/akun/admin')->controller(admin\AdminController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Admin,view');
            Route::post('/add', 'add')->middleware('access:Admin,add');
            Route::post('/edit', 'edit')->middleware('access:Admin,edit');
            Route::post('/ajax/table', 'ajax_table')->middleware('access:Admin,view');
            Route::post('/ajax/id', 'ajax_id')->middleware('access:Admin,edit');
            Route::get('/password/reset', 'password_reset')->middleware('access:Admin,edit');
            Route::post('/avatar/update', 'avatar_update')->middleware('access:Admin,edit');
            Route::get('/status', 'status')->middleware('access:Admin,edit');
            Route::get('/delete/{id}', 'delete')->middleware('access:Admin,delete');
        });

        Route::prefix('/akun/dosen')->controller(admin\LecturerController::class)->group(function () {
            Route::get('/', 'account')->middleware('access:Akun dosen,view');
            Route::post('/ajax/table', 'ajax_table_account')->middleware('access:Akun dosen,view');
            Route::get('/password/reset', 'password_reset')->middleware('access:Akun dosen,edit');
            Route::get('/status', 'status')->middleware('access:Akun dosen,edit');
        });

        Route::prefix('/akun/mahasiswa')->controller(admin\CollegerController::class)->group(function () {
            Route::get('/', 'account')->middleware('access:Akun mahasiswa,view');
            Route::post('/ajax/table', 'ajax_table_account')->middleware('access:Akun mahasiswa,view');
            Route::get('/password/reset', 'password_reset')->middleware('access:Akun mahasiswa,edit');
            Route::post('/status', 'status')->middleware('access:Akun mahasiswa,edit');
        });

        Route::prefix('/log')->controller(admin\LogController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Log data,view');
            Route::post('/ajax/table', 'ajax_table')->middleware('access:Log data,view');
        });

        Route::prefix('/konfigurasi')->controller(admin\ConfigController::class)->group(function () {
            Route::get('/', 'index')->middleware('access:Konfigurasi,view');
            Route::post('/update', 'update')->middleware('access:Konfigurasi,view');
        });
    });
});

Route::group(['middleware' => ['auth:dosen', 'language_manager']], function () {
    Route::prefix('dosen')->group(function () {
        Route::controller(dosen\DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index');
            Route::get('/profil', 'profile');
            Route::post('/ajax/class', 'ajax_class');
        });

        Route::prefix('/jadwal')->controller(dosen\ScheduleController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/ajax/table', 'ajax_table');
        });

        Route::prefix('/elearning')->controller(dosen\ElearningController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/ajax/list', 'ajax_list');
            Route::get('/detail', 'detail');
            Route::get('/form/add', 'add_view');
            Route::get('/form/edit', 'edit_view');
            Route::post('/add', 'add');
            Route::post('/edit', 'edit');
            Route::get('/delete/{id}', 'delete');

            Route::post('/kelas/add', 'add_class');
            Route::post('/kelas/edit', 'edit_class');
            Route::get('/kelas/delete/{id}', 'delete_class');

            Route::post('/kuis/add', 'add_quiz');
            Route::get('/kuis/delete', 'delete_quiz');

            Route::post('/discussion/send', 'send_discussion');
            Route::post('/ajax/discussion/list', 'discussion_list');
            Route::post('/ajax/discussion/id', 'discussion_id');
        });

        Route::prefix('/kuis')->controller(dosen\QuizController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/ajax/list', 'ajax_list');
            Route::post('/ajax/id', 'ajax_id');
            Route::get('/detail', 'detail');
            Route::post('/add', 'add');
            Route::post('/edit', 'edit');
            Route::get('/delete/{id}', 'delete');

            Route::post('/kelas/add', 'add_class');
            Route::post('/kelas/edit', 'edit_class');
            Route::get('/kelas/delete/{id}', 'delete_class');

            Route::post('/ajax/soal', 'ajax_question');
            Route::post('/soal/bank', 'bank_question');
            Route::get('/soal/form/add', 'add_question_view');
            Route::post('/soal/add', 'add_question');
            Route::get('/soal/form/edit', 'edit_question_view');
            Route::post('/soal/edit', 'edit_question');
            Route::get('/soal/delete/{id}', 'delete_question');

            Route::post('/scoring', 'scoring');
            Route::post('/publish', 'publish');
            Route::post('/ajax/correction', 'ajax_correction');
            Route::post('/ajax/class', 'ajax_class');
        });

        Route::prefix('/ujian')->controller(dosen\ExamController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/ajax/list', 'ajax_list');
            Route::post('/ajax/id', 'ajax_id');
            Route::get('/detail', 'detail');
            Route::post('/add', 'add');
            Route::post('/edit', 'edit');
            Route::get('/delete/{id}', 'delete');

            Route::post('/kelas/add', 'add_class');
            Route::post('/kelas/edit', 'edit_class');
            Route::get('/kelas/delete/{id}', 'delete_class');

            Route::post('/ajax/soal', 'ajax_question');
            Route::post('/soal/bank', 'bank_question');
            Route::get('/soal/form/add', 'add_question_view');
            Route::post('/soal/add', 'add_question');
            Route::get('/soal/form/edit', 'edit_question_view');
            Route::post('/soal/edit', 'edit_question');
            Route::get('/soal/delete/{id}', 'delete_question');

            Route::post('/scoring', 'scoring');
            Route::post('/publish', 'publish');
            Route::post('/ajax/correction', 'ajax_correction');
            Route::post('/ajax/class', 'ajax_class');
        });

        Route::prefix('/soal')->controller(dosen\QuestionController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/ajax/table', 'ajax_table');
            Route::post('/ajax/id', 'ajax_id');
            Route::get('/form/add', 'add_view');
            Route::get('/form/edit', 'edit_view');
            Route::post('/add', 'add');
            Route::post('/edit', 'edit');
            Route::get('/delete/{id}', 'delete');
        });

        Route::prefix('/absensi')->controller(dosen\AbsenceController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/ajax/table', 'ajax_table');
            Route::post('/ajax/schedule', 'ajax_schedule');
            Route::post('/status', 'status');
            Route::post('/note', 'note');
            Route::post('/reset', 'reset');
            Route::post('/check', 'check_status');
            Route::post('/check_move', 'check_move');
            Route::post('/start', 'start');
            Route::post('/move', 'move');
            Route::post('/move_cancel', 'move_cancel');
            Route::post('/activity', 'activity');
            Route::post('/submit', 'submit');
            Route::post('/delete', 'delete');
        });

        Route::prefix('/auth')->controller(dosen\AuthController::class)->group(function () {
            Route::post('/password', 'password_change');
            Route::get('/lang', 'lang_change');
            Route::get('/change_class_type/{bool}', 'change_class_type');
            Route::post('/online', 'online');
        });
    });
});

Route::group(['middleware' => ['auth:mahasiswa', 'class_check', 'language_manager']], function () {
    Route::prefix('mahasiswa')->group(function () {
        Route::controller(mahasiswa\DashboardController::class)->group(function () {
            Route::get('/dashboard', 'index');
            Route::get('/profil', 'profile');
            Route::get('/jadwal', 'schedule');
            Route::post('/ajax/schedule', 'ajax_schedule');
            Route::get('/absensi', 'absence');
            Route::post('/ajax/sabsence', 'ajax_absence');
            Route::post('/absence/check', 'absence');
            Route::post('/absence/do', 'submit_absence');
        });

        Route::prefix('/jadwal')->controller(mahasiswa\ScheduleController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/ajax/table', 'ajax_table');
        });

        Route::prefix('/absensi')->controller(mahasiswa\AbsenceController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/table', 'table');
        });

        Route::prefix('/elearning')->controller(mahasiswa\ElearningController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/ajax/list', 'ajax_list');
            Route::get('/detail', 'detail');

            Route::post('/discussion/send', 'send_discussion');
            Route::post('/ajax/discussion/list', 'discussion_list');
            Route::post('/ajax/discussion/id', 'discussion_id');
        });

        Route::prefix('/kuis')->controller(mahasiswa\QuizController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/ajax/list', 'ajax_list');
            Route::post('/ajax/id', 'ajax_id');

            Route::get('/do/{id}', 'do');
            Route::get('/result/{id}', 'result');
            Route::post('/ajax/answer', 'answer');
            Route::post('/ajax/file', 'file');
            Route::post('/ajax/reset', 'reset');
        });

        Route::prefix('/ujian')->controller(mahasiswa\ExamController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/ajax/list', 'ajax_list');
            Route::post('/ajax/id', 'ajax_id');

            Route::get('/do/{id}', 'do');
            Route::get('/result/{id}', 'result');
            Route::post('/ajax/answer', 'answer');
            Route::post('/ajax/file', 'file');
            Route::post('/ajax/reset', 'reset');
        });


        Route::prefix('/auth')->controller(mahasiswa\AuthController::class)->group(function () {
            Route::post('/password', 'password_change');
            Route::get('/lang', 'lang_change');
            Route::post('/online', 'online');
        });
    });
});;
