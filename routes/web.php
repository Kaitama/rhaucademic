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
	Route::group(['middleware' => ['role_or_permission:developer|m pengasuhan']], function () {
		// MENU PRESTASI
		Route::get('/achievement', [AchievementController::class, 'index'])->name('achievement.index');
		// MENU PELANGGARAN
		Route::get('/offense', [OffenseController::class, 'index'])->name('offense.index');
		// MENU PERIZINAN
		Route::get('/permit', [PermitController::class, 'index'])->name('permit.index');
		Route::get('/permit/show/{id}', [PermitController::class, 'show'])->name('permit.show');
		
		// CRUD PENGASUHAN
		Route::group(['middleware' => ['role_or_permission:developer|c pengasuhan|u pengasuhan|d pengasuhan']], function () {
			// prestasi
			Route::post('/achievement/store', [AchievementController::class, 'store'])->name('achievement.store');
			Route::post('/achievement/update', [AchievementController::class, 'update'])->name('achievement.update');
			Route::post('/achievement/destroy', [AchievementController::class, 'destroy'])->name('achievement.destroy');
			
			// pelanggaran
			Route::post('/offense/store', [OffenseController::class, 'store'])->name('offense.store');
			Route::post('/offense/update', [OffenseController::class, 'update'])->name('offense.update');
			Route::post('/offense/destroy', [OffenseController::class, 'destroy'])->name('offense.destroy');
			
			// perizinan
			Route::post('/permit/store', [PermitController::class, 'store'])->name('permit.store');
			Route::post('/permit/update', [PermitController::class, 'update'])->name('permit.update');
			Route::post('/permit/destroy', [PermitController::class, 'destroy'])->name('permit.destroy');
		});
	});
	
	
	// MENU KEUANGAN GROUP
	Route::group(['middleware' => ['role_or_permission:developer|m keuangan']], function () {
		Route::get('/tuition', [TuitionController::class, 'index'])->name('tuition.index');
		Route::post('/excel/export/tuition', [ExcelController::class, 'exporttuition'])->name('excel.export.tuition');
		// tunggakan
		Route::get('/arrears', [TuitionController::class, 'arrears'])->name('arrears.index');
		// update delete uang sekolah
		Route::group(['middleware' => ['role_or_permission:developer|c keuangan|u keuangan|d keuangan']], function () {
			Route::get('/excel/download/template/tuition', [ExcelController::class, 'downloadtemplatetuition'])->name('excel.template.tuition');
			Route::post('/excel/upload/data/tuition', [TuitionController::class, 'import'])->name('excel.data.tuition');
			Route::post('/tuition/store', [TuitionController::class, 'store'])->name('tuition.store');
			Route::post('/tuition/update', [TuitionController::class, 'update'])->name('tuition.update');
			Route::post('/tuition/destroy', [TuitionController::class, 'destroy'])->name('tuition.destroy');
		});
	});
	
	// MENU BASIS DATA GROUP
	Route::group(['middleware' => ['role_or_permission:developer|administrator|m basdat']], function () {
		
		// MENU KELAS
		Route::get('/classroom', [ClassroomController::class, 'index'])->name('classroom.index');
		Route::post('/classroom/store', [ClassroomController::class, 'store'])->name('classroom.store');
		Route::post('/classroom/update', [ClassroomController::class, 'update'])->name('classroom.update');
		Route::post('/classroom/destroy', [ClassroomController::class, 'destroy'])->name('classroom.destroy');
		Route::post('/classroom/building/store', [BuildingController::class, 'store'])->name('classroom.building.store');
		Route::post('/classroom/building/update', [BuildingController::class, 'update'])->name('classroom.building.update');
		Route::post('/classroom/building/destroy', [BuildingController::class, 'destroy'])->name('classroom.building.destroy');
		
		// MENU ASRAMA
		Route::get('/dormroom', [DormroomController::class, 'index'])->name('dormroom.index');
		Route::post('/dormroom/store', [DormroomController::class, 'store'])->name('dormroom.store');
		Route::post('/dormroom/update', [DormroomController::class, 'update'])->name('dormroom.update');
		Route::post('/dormroom/destroy', [DormroomController::class, 'destroy'])->name('dormroom.destroy');
		Route::post('/dormroom/building/store', [BuildingController::class, 'store'])->name('dormroom.building.store');
		Route::post('/dormroom/building/update', [BuildingController::class, 'update'])->name('dormroom.building.update');
		Route::post('/dormroom/building/destroy', [BuildingController::class, 'destroy'])->name('dormroom.building.destroy');
		
		// MENU ORGANISASI
		Route::get('/organization', [OrganizationController::class, 'index'])->name('organization.index');
		Route::post('/organization/store', [OrganizationController::class, 'store'])->name('organization.store');
		Route::get('/organization/show/{id}', [OrganizationController::class, 'show'])->name('organization.show');
		Route::post('/organization/inactivate', [OrganizationController::class, 'inactivate'])->name('organization.inactivate');
		Route::post('/organization/activate', [OrganizationController::class, 'activate'])->name('organization.activate');
		Route::post('/organization/deactivate', [OrganizationController::class, 'deactivate'])->name('organization.deactivate');
		Route::post('/organization/editstudents/{id}', [OrganizationController::class, 'editstudents'])->name('organization.editstudents');
		Route::post('/organization/toggleisactive/{id}', [OrganizationController::class, 'toggleisactive'])->name('organization.toggleisactive');
		Route::post('/organization/update', [OrganizationController::class, 'update'])->name('organization.update');
		Route::post('/organization/destroy', [OrganizationController::class, 'destroy'])->name('organization.destroy');
		Route::post('/organization/addstudents', [OrganizationController::class, 'addstudents'])->name('organization.addstudents');
		
		// MENU EKSTRAKURIKULER
		Route::get('/extracurricular', [ExtracurricularController::class, 'index'])->name('extracurricular.index');
		Route::post('/extracurricular/store', [ExtracurricularController::class, 'store'])->name('extracurricular.store');
		Route::get('/extracurricular/show/{id}', [ExtracurricularController::class, 'show'])->name('extracurricular.show');
		Route::post('/extracurricular/toggle', [ExtracurricularController::class, 'toggle'])->name('extracurricular.toggle');
		Route::post('/extracurricular/addstudents', [ExtracurricularController::class, 'addstudents'])->name('extracurricular.addstudents');
		Route::post('/extracurricular/toggleisactive', [ExtracurricularController::class, 'toggleisactive'])->name('extracurricular.toggleisactive');
		Route::post('/extracurricular/update', [ExtracurricularController::class, 'update'])->name('extracurricular.update');
		
		
		// MENU PEGAWAI
		Route::get('/staff', [PegawaiController::class, 'index'])->name('pegawai.index');
		Route::post('/staff/store', [PegawaiController::class, 'store'])->name('pegawai.store');
		Route::post('/staff/update', [PegawaiController::class, 'update'])->name('pegawai.update');
		Route::post('/staff/resetpassword', [PegawaiController::class, 'resetpassword'])->name('pegawai.resetpassword');
		Route::post('/staff/destroy', [PegawaiController::class, 'destroy'])->name('pegawai.destroy');
		
		
		// MENU SANTRI
		
		Route::get('/student', [StudentController::class, 'index'])->name('student.index');
		Route::post('/student/deactivate', [StudentController::class, 'deactivate'])->name('student.deactivate');
		Route::post('/student/activate', [StudentController::class, 'activate'])->name('student.activate');
		Route::post('/excel/upload/data/student', [StudentController::class, 'import'])->name('excel.data.student');
		Route::post('/student/update/photo', [StudentController::class, 'updatephoto'])->name('student.update.photo');
		Route::get('/student/download/barcode', [StudentController::class, 'downloadbarcode'])->name('student.download.barcode');
		// update profile
		Route::post('/student/update/primer', [StudentController::class, 'update'])->name('student.update');
		Route::post('/student/update/secondary', [StudentprofileController::class, 'update'])->name('student.secondary');
		Route::post('/student/destroy', [StudentController::class, 'destroy'])->name('student.destroy');
		Route::get('/excel/download/template/student', [ExcelController::class, 'downloadtemplatestudent'])->name('excel.template.student');
		Route::get('/student/search', [StudentController::class, 'search'])->name('student.search');
		Route::get('/student/create', [StudentController::class, 'create'])->name('student.create');
		Route::post('/student/store', [StudentController::class, 'store'])->name('student.store');
	});
	
	// USER MENU
	Route::get('/user/settings', [UserController::class, 'settings'])->name('user.settings');
	Route::post('/user/settings/updatepict', [UserController::class, 'updatepict'])->name('user.updatepict');
	Route::post('/user/settings/updatelogin', [UserController::class, 'updatelogin'])->name('user.updatelogin');
	Route::post('/user/settings/updatepassword', [UserController::class, 'updatepassword'])->name('user.updatepassword');
	
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