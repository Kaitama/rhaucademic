<?php

// use App\User;
use App\Student;

/**
 * Index
 */
Breadcrumbs::for('dashboard.index', function ($trail) {
	$trail->push(env('APP_NAME'), route('dashboard.index'));
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
/**
 * Dormroom
 */
Breadcrumbs::for('dormroom.index', function ($trail) {
	$trail->parent('dashboard.index');
	$trail->push('Asrama', route('dormroom.index'));
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