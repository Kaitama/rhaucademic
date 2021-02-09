<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DormroomController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\TuitionController;
use App\Http\Controllers\StudentprofileController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\OffenseController;
use App\Http\Controllers\PermitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\DatareportController;
use App\Http\Controllers\ExtracurricularController;
use App\Http\Controllers\CarrouselController;
use App\Http\Controllers\UserlogController;



Route::get('/', function () {
	// return view('welcome');
	return redirect()->route('dashboard.index');
});


Auth::routes(['register' => false]);

/**
* DASHBOARD ROUTING
*/
Route::middleware('auth')->prefix('dashboard')->group(function () {
	// DASHBOARD
	Route::get('/', 'DashboardController@index')->name('dashboard.index');
	
	// PROFILE SANTRI
	Route::get('/student/p/{stambuk}', [StudentController::class, 'show'])->name('student.profile');
	// TAMPIL KELAS
	Route::get('/classroom/show/{id}', [ClassroomController::class, 'show'])->name('classroom.show');
	// TAMPIL ASRAMA
	Route::get('/dormroom/show/{id}', [DormroomController::class, 'show'])->name('dormroom.show');
	
	// SEARCH USER
	Route::get('search/staffs/{query}', [UserController::class, 'searchstaffs'])->name('search.staffs');
	
	// SEARCH STUDENT
	Route::get('search/students/{query}', [StudentController::class, 'jsonsearch'])->name('search.students');
	
	// VALIDASI PERMIT
	Route::get('/permit/validate/{hash}', [PermitController::class, 'validating'])->name('permit.validating');
	Route::post('/permit/validate/checkout', [PermitController::class, 'checkout'])->name('permit.checkout');
	Route::post('/permit/validate/checkin', [PermitController::class, 'checkin'])->name('permit.checkin');
	
	// LAPORAN
	Route::get('/report-permit', [DatareportController::class, 'permit'])->name('report.permit');
	
	// PENGASUHAN
	
	// MENU PRESTASI
	Route::get('/achievement', [AchievementController::class, 'index'])->name('achievement.index')
	->middleware('role_or_permission:developer|r prestasi');
	Route::post('/achievement/store', [AchievementController::class, 'store'])->name('achievement.store')
	->middleware('role_or_permission:developer|c prestasi');
	Route::post('/achievement/update', [AchievementController::class, 'update'])->name('achievement.update')
	->middleware('role_or_permission:developer|u prestasi');
	Route::post('/achievement/destroy', [AchievementController::class, 'destroy'])->name('achievement.destroy')
	->middleware('role_or_permission:developer|d prestasi|global delete');
	
	// MENU PELANGGARAN
	Route::get('/offense', [OffenseController::class, 'index'])->name('offense.index')
	->middleware('role_or_permission:developer|r pelanggaran');
	Route::post('/offense/store', [OffenseController::class, 'store'])->name('offense.store')
	->middleware('role_or_permission:developer|c pelanggaran');
	Route::post('/offense/update', [OffenseController::class, 'update'])->name('offense.update')
	->middleware('role_or_permission:developer|u pelanggaran');
	Route::post('/offense/destroy', [OffenseController::class, 'destroy'])->name('offense.destroy')
	->middleware('role_or_permission:developer|d pelanggaran|global delete');
	Route::get('/excel/download/template/offense', [ExcelController::class, 'downloadtemplateoffense'])->name('excel.template.offense')
	->middleware('role_or_permission:developer|c pelanggaran');
	Route::post('/excel/upload/data/offense', [OffenseController::class, 'import'])->name('excel.data.offense')
	->middleware('role_or_permission:developer|c pelanggaran');
	Route::post('/excel/export/offense', [ExcelController::class, 'exportoffense'])->name('excel.export.offense')
	->middleware('role_or_permission:developer|r pelanggaran');
	
	// MENU PERIZINAN
	Route::get('/permit', [PermitController::class, 'index'])->name('permit.index')
	->middleware('role_or_permission:developer|r perizinan');
	Route::get('/permit/show/{id}', [PermitController::class, 'show'])->name('permit.show')
	->middleware('role_or_permission:developer|c perizinan');
	Route::post('/permit/store', [PermitController::class, 'store'])->name('permit.store')
	->middleware('role_or_permission:developer|c perizinan');
	Route::post('/permit/update', [PermitController::class, 'update'])->name('permit.update')
	->middleware('role_or_permission:developer|u perizinan');
	Route::post('/permit/destroy', [PermitController::class, 'destroy'])->name('permit.destroy')
	->middleware('role_or_permission:developer|d perizinan|global delete');
	
	
	// MENU ASRAMA
	Route::get('/dormroom', [DormroomController::class, 'index'])->name('dormroom.index')
	->middleware('role_or_permission:developer|r asrama');
	Route::post('/dormroom/store', [DormroomController::class, 'store'])->name('dormroom.store')
	->middleware('role_or_permission:developer|c asrama');
	Route::post('/dormroom/update', [DormroomController::class, 'update'])->name('dormroom.update')
	->middleware('role_or_permission:developer|u asrama');
	Route::post('/dormroom/destroy', [DormroomController::class, 'destroy'])->name('dormroom.destroy')
	->middleware('role_or_permission:developer|d asrama|global delete');
	Route::post('/dormroom/addstudents', [DormroomController::class, 'addstudents'])->name('dormroom.addstudents')
	->middleware('role_or_permission:developer|c asrama|u asrama');
	Route::post('/dormroom/removestudent', [DormroomController::class, 'removestudent'])->name('dormroom.removestudent')
	->middleware('role_or_permission:developer|c asrama|u asrama|global delete');
	
	// MENU ORGANISASI
	Route::get('/organization', [OrganizationController::class, 'index'])->name('organization.index')
	->middleware('role_or_permission:developer|r organisasi');
	Route::post('/organization/store', [OrganizationController::class, 'store'])->name('organization.store')
	->middleware('role_or_permission:developer|c organisasi');
	Route::get('/organization/show/{id}', [OrganizationController::class, 'show'])->name('organization.show')
	->middleware('role_or_permission:developer|r organisasi');
	Route::post('/organization/inactivate', [OrganizationController::class, 'inactivate'])->name('organization.inactivate')
	->middleware('role_or_permission:developer|u organisasi');
	Route::post('/organization/activate', [OrganizationController::class, 'activate'])->name('organization.activate')
	->middleware('role_or_permission:developer|u organisasi');
	Route::post('/organization/deactivate', [OrganizationController::class, 'deactivate'])->name('organization.deactivate')
	->middleware('role_or_permission:developer|u organisasi');
	Route::post('/organization/editstudents/{id}', [OrganizationController::class, 'editstudents'])->name('organization.editstudents')
	->middleware('role_or_permission:developer|u organisasi');
	Route::post('/organization/toggleisactive/{id}', [OrganizationController::class, 'toggleisactive'])->name('organization.toggleisactive')
	->middleware('role_or_permission:developer|u organisasi');
	Route::post('/organization/update', [OrganizationController::class, 'update'])->name('organization.update')
	->middleware('role_or_permission:developer|u organisasi');
	Route::post('/organization/destroy', [OrganizationController::class, 'destroy'])->name('organization.destroy')
	->middleware('role_or_permission:developer|d organisasi|global delete');
	Route::post('/organization/addstudents', [OrganizationController::class, 'addstudents'])->name('organization.addstudents')
	->middleware('role_or_permission:developer|u organisasi');
	
	// MENU EKSTRAKURIKULER
	Route::get('/extracurricular', [ExtracurricularController::class, 'index'])->name('extracurricular.index')
	->middleware('role_or_permission:developer|r ekskul');
	Route::post('/extracurricular/store', [ExtracurricularController::class, 'store'])->name('extracurricular.store')
	->middleware('role_or_permission:developer|c ekskul');
	Route::get('/extracurricular/show/{id}', [ExtracurricularController::class, 'show'])->name('extracurricular.show')
	->middleware('role_or_permission:developer|r ekskul');
	Route::post('/extracurricular/toggle', [ExtracurricularController::class, 'toggle'])->name('extracurricular.toggle')
	->middleware('role_or_permission:developer|u ekskul');
	Route::post('/extracurricular/addstudents', [ExtracurricularController::class, 'addstudents'])->name('extracurricular.addstudents')
	->middleware('role_or_permission:developer|u ekskul');
	Route::post('/extracurricular/toggleisactive', [ExtracurricularController::class, 'toggleisactive'])->name('extracurricular.toggleisactive')
	->middleware('role_or_permission:developer|u ekskul');
	Route::post('/extracurricular/update', [ExtracurricularController::class, 'update'])->name('extracurricular.update')
	->middleware('role_or_permission:developer|u ekskul');
	
	
	// MENU KEUANGAN
	Route::get('/tuition', [TuitionController::class, 'index'])->name('tuition.index')
	->middleware('role_or_permission:developer|m keuangan');
	Route::post('/excel/export/tuition', [ExcelController::class, 'exporttuition'])->name('excel.export.tuition')
	->middleware('role_or_permission:developer|m keuangan');
	Route::get('/excel/download/template/tuition', [ExcelController::class, 'downloadtemplatetuition'])->name('excel.template.tuition')
	->middleware('role_or_permission:developer|c keuangan');
	Route::post('/excel/upload/data/tuition', [TuitionController::class, 'import'])->name('excel.data.tuition')
	->middleware('role_or_permission:developer|c keuangan');
	Route::post('/tuition/store', [TuitionController::class, 'store'])->name('tuition.store')
	->middleware('role_or_permission:developer|c keuangan');
	Route::post('/tuition/update', [TuitionController::class, 'update'])->name('tuition.update')
	->middleware('role_or_permission:developer|u keuangan');
	Route::post('/tuition/destroy', [TuitionController::class, 'destroy'])->name('tuition.destroy')
	->middleware('role_or_permission:developer|d keuangan');
	// tunggakan
	Route::get('/arrears', [TuitionController::class, 'arrears'])->name('arrears.index')
	->middleware('role_or_permission:developer|m keuangan');
	
	// MENU SANTRI
	Route::get('/student', [StudentController::class, 'index'])->name('student.index')
	->middleware('role_or_permission:developer|r santri');
	Route::get('/student/search', [StudentController::class, 'search'])->name('student.search')
	->middleware('role_or_permission:developer|r santri');
	Route::get('/student/filter', [StudentController::class, 'filtering'])->name('student.filter')
	->middleware('role_or_permission:developer|r santri');
	
	// MENU BASIS DATA GROUP
	Route::group(['middleware' => ['role_or_permission:developer|administrator|m basdat']], function () {
		// MENU KELAS
		Route::get('/classroom', [ClassroomController::class, 'index'])->name('classroom.index');
		Route::post('/classroom/store', [ClassroomController::class, 'store'])->name('classroom.store');
		Route::post('/classroom/update', [ClassroomController::class, 'update'])->name('classroom.update');
		Route::post('/classroom/destroy', [ClassroomController::class, 'destroy'])->name('classroom.destroy');
		Route::post('/classroom/addstudents', [ClassroomController::class, 'addstudents'])->name('classroom.addstudents');
		Route::post('/classroom/removestudent', [ClassroomController::class, 'removestudent'])->name('classroom.removestudent');
		
		// MENU SANTRI
		Route::post('/student/deactivate', [StudentController::class, 'deactivate'])->name('student.deactivate');
		Route::post('/student/activate', [StudentController::class, 'activate'])->name('student.activate');
		Route::post('/excel/upload/data/student', [StudentController::class, 'import'])->name('excel.data.student');
		Route::post('/student/update/photo', [StudentController::class, 'updatephoto'])->name('student.update.photo');
		Route::get('/student/download/barcode', [StudentController::class, 'downloadbarcode'])->name('student.download.barcode');
		Route::post('/student/export/excel', [ExcelController::class, 'exportstudents'])->name('excel.export.students');
		// update profile
		Route::post('/student/update/primer', [StudentController::class, 'update'])->name('student.update');
		Route::post('/student/update/secondary', [StudentprofileController::class, 'update'])->name('student.secondary');
		Route::post('/student/destroy', [StudentController::class, 'destroy'])->name('student.destroy');
		Route::get('/excel/download/template/student', [ExcelController::class, 'downloadtemplatestudent'])->name('excel.template.student');
		Route::get('/student/create', [StudentController::class, 'create'])->name('student.create');
		Route::post('/student/store', [StudentController::class, 'store'])->name('student.store');
		
		// MENU PEGAWAI
		Route::get('/staff', [PegawaiController::class, 'index'])->name('pegawai.index');
		Route::post('/staff/store', [PegawaiController::class, 'store'])->name('pegawai.store');
		Route::post('/staff/update', [PegawaiController::class, 'update'])->name('pegawai.update');
		Route::post('/staff/resetpassword', [PegawaiController::class, 'resetpassword'])->name('pegawai.resetpassword');
		Route::post('/staff/destroy', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
		Route::post('/user/mobile/destroy', [UserController::class, 'mobiledestroy'])->name('user.mobiledestroy');
		
		// MENU BANNER PENGUMUMAN
		Route::get('/carrousel', [CarrouselController::class, 'index'])->name('carrousel.index');
		Route::post('/carrousel/store', [CarrouselController::class, 'store'])->name('carrousel.store');
		Route::post('/carrousel/destroy', [CarrouselController::class, 'destroy'])->name('carrousel.destroy');
	});
	
	// USER MENU
	Route::get('/user/settings', [UserController::class, 'settings'])->name('user.settings');
	Route::post('/user/settings/updatepict', [UserController::class, 'updatepict'])->name('user.updatepict');
	Route::post('/user/settings/updatelogin', [UserController::class, 'updatelogin'])->name('user.updatelogin');
	Route::post('/user/settings/updatepassword', [UserController::class, 'updatepassword'])->name('user.updatepassword');
	
	// ACTIVITY LOG
	Route::get('/logs', [UserlogController::class, 'index'])->name('logs.index')
	->middleware('role_or_permission:developer');
	
	// DEVELOPER MENU
	Route::group(['middleware' => ['role:developer']], function () {
		// ROLES
		Route::get('/role', [RoleController::class, 'index'])->name('role.index');
		Route::post('/role/store', [RoleController::class, 'store'])->name('role.store');
		Route::get('/role/destroy/{id}', [RoleController::class, 'destroy'])->name('role.destroy');
		Route::post('/role/assign', [RoleController::class, 'assign'])->name('role.assign');
		// PERMISSIONS
		Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');
		Route::post('/permission/store', [PermissionController::class, 'store'])->name('permission.store');
		Route::get('/permission/destroy/{id}', [PermissionController::class, 'destroy'])->name('permission.destroy');
		Route::post('/permission/assign', [PermissionController::class, 'assign'])->name('permission.assign');
	});
});