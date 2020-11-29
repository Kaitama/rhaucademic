<?php

// use App\User;
use App\Student;
use App\Classroom;
use App\Dormroom;
use App\Organization;
use App\Extracurricular;

/**
* Index
*/
Breadcrumbs::for('dashboard.index', function ($trail) {
	$trail->push(env('APP_NAME'), route('dashboard.index'));
});

/**
* Report Permit
*/
Breadcrumbs::for('report.permit', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Laporan Perizinan', route('report.permit'));
});

/**
* Achievement
*/
Breadcrumbs::for('achievement.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Prestasi', route('achievement.index'));
});

/**
* Offense
*/
Breadcrumbs::for('offense.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Pelanggaran', route('offense.index'));
});

/**
* Permit
*/
Breadcrumbs::for('permit.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Perizinan', route('permit.index'));
});

/**
* Tuition
*/
Breadcrumbs::for('tuition.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Uang Sekolah', route('tuition.index'));
});

/**
* Arrears
*/
Breadcrumbs::for('arrears.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Tunggakan Uang Sekolah', route('arrears.index'));
});

/**
* Pegawai
*/
Breadcrumbs::for('pegawai.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Pegawai', route('pegawai.index'));
});
Breadcrumbs::for('pegawai.create', function ($trail) {
	$trail->parent('pegawai.index');
	$trail->push('Tambah', route('pegawai.create'));
});
Breadcrumbs::for('pegawai.edit', function ($trail, $id) {
	// $usr = User::findOrFail($id);
	$trail->parent('pegawai.index');
	$trail->push('Ubah', route('pegawai.edit', $id));
	// $trail->push($usr->name, route('pegawai.edit', $usr));
});
/**
* Classroom
*/
Breadcrumbs::for('classroom.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Kelas', route('classroom.index'));
});
Breadcrumbs::for('classroom.show', function ($trail, $id) {
	$class = Classroom::find($id);
	if(auth()->user()->can('m basdat')){
		$trail->parent('classroom.index');
	} else {
		$trail->parent('dashboard.index');
	}
	$trail->push($class->name, route('classroom.show', $id));
});
/**
* Dormroom
*/
Breadcrumbs::for('dormroom.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Asrama', route('dormroom.index'));
});
Breadcrumbs::for('dormroom.show', function ($trail, $id) {
	$class = Dormroom::find($id);
	if(auth()->user()->can('m basdat')){
		$trail->parent('dormroom.index');
	} else {
		$trail->parent('dashboard.index');
	}
	$trail->push($class->name, route('dormroom.show', $id));
});
/**
* Extracurricular
*/
Breadcrumbs::for('extracurricular.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Ekstrakurikuler', route('extracurricular.index'));
});
Breadcrumbs::for('extracurricular.show', function ($trail, $id) {
	$ext = Extracurricular::find($id);
	$trail->parent('extracurricular.index');
	$trail->push($ext->name, route('extracurricular.show', $ext));
});
/**
* Organization
*/
Breadcrumbs::for('organization.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Organisasi', route('organization.index'));
});
Breadcrumbs::for('organization.show', function ($trail, $id) {
	$org = Organization::find($id);
	$trail->parent('organization.index');
	$trail->push($org->name, route('organization.show', $org));
});
/**
* Student
*/
Breadcrumbs::for('student.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Santri', route('student.index'));
});
Breadcrumbs::for('student.profile', function ($trail, $stambuk) {
	$student = Student::where('stambuk', $stambuk)->first();
	if(auth()->user()->can('m basdat')){
		$trail->parent('student.index');
	} else {
		$trail->parent('dashboard.index');
	}
	$trail->push($student->name, route('student.profile', $student));
});
Breadcrumbs::for('student.create', function ($trail) {
	$trail->parent('student.index');
	$trail->push('Tambah', route('student.create'));
});
Breadcrumbs::for('student.search', function ($trail) {
	$trail->parent('student.index');
	$trail->push('Cari', route('student.search'));
});
Breadcrumbs::for('student.filter', function ($trail) {
	$trail->parent('student.index');
	$trail->push('Filter', route('student.filter'));
});

/**
* Carrousel
*/
Breadcrumbs::for('carrousel.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Banner Informasi', route('carrousel.index'));
});


/**
* User Settings
*/
Breadcrumbs::for('user.settings', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Settings', route('user.settings'));
});


/**
* Role
*/
Breadcrumbs::for('role.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Roles', route('role.index'));
});
/**
* Permission
*/
Breadcrumbs::for('permission.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Permissions', route('permission.index'));
});