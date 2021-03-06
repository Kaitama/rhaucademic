<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Student;
use App\Studentprofile;
use App\Classroom;
use App\Dormroom;
use App\Tuition;
use App\Achievement;
use App\Permit;
use App\Offense;
use App\Organization;
use App\Extracurricular;
use App\Carrousel;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use Illuminate\Http\Request;
use Image;
use Storage;
use ZipArchive;


class StudentController extends Controller
{
	public function import(Request $request) 
	{
		
		
		Excel::import(new StudentsImport(), $request->file('excel'));
		
		return back()->with('success', 'Data santri berhasil di import.');
	}
	
	public function deactivate(Request $request)
	{
		$id = $request->idtodeactivate;
		if($request->permanent){
			$std = Student::find($id)->update([
				'classroom_id'	=> null,
				'dormroom_id' => null,
				'status' => $request->status,
				'description'	=> $request->description,
				]
			);
			$orgs = Organization::all();
			foreach($orgs as $org){
				$org->student()->wherePivot('isactive', true)->updateExistingPivot($id, ['outdate'=> date('Y-m-d'), 'isactive' => false]);
			}
			$exts = Extracurricular::all();
			foreach($exts as $ext){
				$ext->student()->wherePivot('isactive', true)->updateExistingPivot($id, ['outdate'=> date('Y-m-d'), 'isactive' => false]);
			}
		} else {
			Student::find($id)->update([
				'status' => $request->status,
				'description'	=> $request->description,
				]
			);
		}
		return back()->with('success', 'Santri berhasil dinonaktifkan.');
	}
	public function activate(Request $request)
	{
		$id = $request->idtoactivate;
		Student::find($id)->update([
			'status' => 1,
			'description' => null,
			]
		);
		return back()->with('success', 'Santri berhasil diaktifkan.');
	}
	
	public function filtering(Request $request)
	{
		$classrooms = Classroom::all();
		$status = $request->statfilter;
		$students = Student::where('status', $status)->paginate(20);
		return view('dashboard.student.index', ['students' => $students, 'classrooms' => $classrooms]);
	}
	
	public function search(Request $request)
	{
		$classrooms = Classroom::all();
		$string = $request->s;
		if(is_numeric($string)){
			$students = Student::where('stambuk','like',"%".$string."%")->with('classroom')->with('dormroom')->paginate(20);
		} else {
			$students = Student::where('name','like',"%".$string."%")->with('classroom')->with('dormroom')->paginate(20);
		}
		return view('dashboard.student.index', ['students' => $students, 'classrooms' => $classrooms]);
	}
	
	public function downloadbarcode()
	{
		$students = Student::all();
		if($students == null)
		{
			return back();
		}
		$zip_file = public_path('barcode_santri.zip'); 
		$zip = new ZipArchive();
		$zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		
		$filename = array();
		foreach($students as $student)
		{
			$barcode = \DNS1D::getBarcodePNG($student->stambuk, "C128", 3, 64);
			Storage::disk('barcode')->put($student->stambuk . '.png', base64_decode($barcode));
			$file = $student->stambuk . '.png';
			
			$zip->addFile(public_path('barcode/' . $student->stambuk . '.png'), $file);
			array_push($filename, $file);
		}
		$zip->close();
		Storage::disk('barcode')->delete($filename);
		
		return response()->download($zip_file);
	}
	
	public function updatephoto(Request $request)
	{
		$student = Student::find($request->id);
		$oldphoto = $student->photo;
		$photo = null;
		if($request->hasFile('photo'))
		{
			$file = $request->file('photo');
			$photo = $file->store('/');
			$thumb = Image::make($file)->fit(400, 400, function ($constraint) 
			{
				$constraint->aspectRatio();$constraint->upsize();
			})->crop(400, 400);
			$thumb->save(public_path('assets/img/student/') . $photo);
			if($oldphoto){
				$ori = public_path('assets/img/originals').'/'.$oldphoto;
				$thb = public_path('assets/img/student').'/'.$oldphoto;
				if(file_exists($ori)) unlink($ori);
				if(file_exists($thb)) unlink($thb);
			}
		} 
		$student->update(['photo' => $photo]);
		return back()->with('success', 'Foto santri berhasil diubah.');
	}
	
	public function index()
	{
		//
		$classrooms = Classroom::all();
		$students = Student::where('status', 1)->latest()->paginate(20);
		return view('dashboard.student.index', ['students' => $students, 'classrooms' => $classrooms]);
	}
	
	public function create()
	{
		//
		$classrooms = Classroom::all();
		$dormrooms = Dormroom::all();
		return view('dashboard.student.create', ['classrooms' => $classrooms->sortBy('level'), 'dormrooms' => $dormrooms->sortBy('building')]);
	}
	
	public function store(Request $request)
	{
		//
		$this->validate($request, [
			'stambuk' 		=> 'required|unique:students',
			'name'				=> 'required',
			'nik'					=> 'required|numeric',
			'nokk'				=> 'required|numeric',
			'birthplace'	=> 'required',
			'birthdate'		=> 'required'
		],
		[
			'stambuk.required'		=> 'Stambuk tidak boleh kosong.',
			'stambuk.unique'			=> 'Stambuk sudah terdaftar pada santri lain.',
			'name.required'				=> 'Nama santri tidak boleh kosong.',
			'nik.required'				=> 'NIK tidak boleh kosong.',
			'nik.numeric'					=> 'NIK hanya terdiri dari angka.',
			'nokk.required'				=> 'Nomor KK tidak boleh kosong.',
			'nokk.numeric'				=> 'Nomor KK hanya terdiri dari angka.',
			'birthplace.required'	=> 'Tempat lahir tidak boleh kosong.',
			'birthdate.required'	=> 'Tanggal lahir tidak boleh kosong.',
			]
		);
		$bd = explode('/', $request->birthdate);
		$bd = $bd[2] . '-' . $bd[1] . '-' . $bd[0];
		// photo
		$photo = null;
		if($request->hasFile('photo'))
		{
			$file = $request->file('photo');
			$photo = $file->store('/');
			$thumb = Image::make($file)->fit(400, 400, function ($constraint) 
			{
				$constraint->aspectRatio();$constraint->upsize();
			})->crop(400, 400);
			$thumb->save(public_path('assets/img/student/') . $photo);
		} 
		// end photo
		$student = Student::create([
			'stambuk' 		=> $request->stambuk,
			'name'				=> $request->name,
			'nik'					=> $request->nik,
			'nokk'				=> $request->nokk,
			'classroom_id'=> $request->classroom_id,
			'dormroom_id'	=> $request->dormroom_id,
			'birthplace'	=> $request->birthplace,
			'birthdate'		=> $bd,
			'gender'			=> $request->gender,
			'photo'				=> $photo,
			]
		);
		Studentprofile::create([
			'student_id' => $student->id
			]
		);
		return back()->with('success', 'Data santri berhasil disimpan.');
	}
	
	
	private	$hobbies = [
		'MEMBACA','BERBURU','BERDAGANG','BERENANG','BERKEMAH','BERSEPEDA','BISNIS','BLOGGING','BOLA VOLI','BOWLING','BULUTANGKIS','CATUR','DESAIN GRAFIS','ELEKTRONIK','FASHION','FOTOGRAFI','FUTSAL','GO KART','GOLF','KALIGRAFI','KANO','KARATE','KERAJINAN (HANDICRAFT)','KOLEKTOR','KOMPUTER','KULINER DAN MEMASAK','MELUKIS','MEMANCING','MENARI','MENEMBAK','MENGAJI','MENULIS','MENUNGGANG KUDA','MENYELAM','MEREKAM VIDEO','MODIFIKASI MOTOR','MOUNTAINEERING','MUSIK','OLAHRAGA MENEMBAK','OTOMOTIF','PANJAT TEBING','PARALAYANG','PECINTA BATU','PERTANIAN','PROGRAMMING','SEPAKBOLA','SKATEBOARDING','SNORKELING','SURFING','TENIS','TINJU','TRAVELING'
	];
	private	$wishes = [
		'TIDAK BEKERJA','IBU RUMAH TANGGA','AKUNTAN','ANGGOTA BPK','ANGGOTA DPD','ANGGOTA DPRD','ANGGOTA KABINET/KEMENTERIAN','ANGGOTA MAHKAMAH KONSTITUSI','APOTEKER','ARSITEK','BIDAN','BUPATI','BURUH HARIAN LEPAS','BURUH NELAYAN/PERIKANAN','BURUH PETERNAKAN','BURUH TANI/PERKEBUNAN','DOKTER','DOSEN','DUTA BESAR','GUBERNUR','GURU','IMAM MESJID','INDUSTRI','JURU MASAK','KARYAWAN BUMD','KARYAWAN BUMN','KARYAWAN SWASTA','KEPALA DESA','KEPOLISIAN RI','KONSTRUKSI','KONSULTAN','MEKANIK','NELAYAN/PERIKANAN','NOTARIS','PANDAI BESI','PEDAGANG','PEDAGANG','PEGAWAI NEGERI SIPIL', 'APARATUR SIPIL NEGARA', 'PELAJAR/MAHASISWA','PELAUT','PEMBANTU RUMAH TANGGA','PENATA BUSANA','PENATA RAMBUT','PENATA RIAS','PENELITI','PENGACARA','PENJAHIT','PENSIUNAN','PENTERJEMAH','PENYIAR RADIO','PENYIAR TELEVISI','PERANCANG BUSANA','PERANGKAT DESA','PERAWAT','PETANI/PEKEBUN','PETERNAK','PIALANG','PILOT','PRESIDEN','PROMOTOR ACARA','PSIKIATER/PSIKOLOG','SECURITY','SENIMAN','SOPIR','TABIB','TENTARA NASIONAL INDONESIA','TRANSPORTASI','TUKANG BATU','TUKANG CUKUR','TUKANG GIGI','TUKANG KAYU','TUKANG LISTRIK','USTADZ/MUBALIGH','WAKIL BUPATI','WAKIL GUBERNUR','WAKIL PRESIDEN','WAKIL WALIKOTA','WALIKOTA','WARTAWAN','WIRASWASTA'
	];
	private $educations = [
		'SD','MI','SMP','MTS','MA','SMA','PESANTREN','D1','D2','D3','S1','S2','S3'
	];
	private $religions = [
		'ISLAM', 'KRISTEN', 'BUDDHA', 'HINDU'
	];
	
	private $donaters = [
		'PAMAN','BIBI','KAKEK','NENEK','ABANG','DERMAWAN'
	];
	
	private $reasons = [
		'EKONOMI','AKADEMIK','KESEHATAN','TUGAS ORANGTUA','TIDAK BETAH'
	];
	
	public function show($stambuk)
	{
		//
		
		if(Auth::user()->student){
			if(Auth::user()->student->stambuk != $stambuk && Auth::user()->level == 9) return redirect()->route('dashboard.index');
		}
		
		$classrooms = Classroom::all();
		$dormrooms = Dormroom::all();
		$student = Student::where('stambuk', $stambuk)->first();
		$tuitions = Tuition::where('student_id', $student->id)->simplePaginate(10, ['*'], 'tuitions');
		$achievements = Achievement::where('student_id', $student->id)->simplePaginate(10, ['*'], 'achievements');
		$permits = Permit::where('student_id', $student->id)->simplePaginate(10, ['*'], 'permits');
		$offenses = Offense::where('student_id', $student->id)->simplePaginate(10, ['*'], 'offenses');
		$organizations = $student->organization()->wherePivot('student_id', $student->id)->simplePaginate(10, ['*'], 'organizations');
		$extracurriculars = $student->extracurricular()->wherePivot('student_id', $student->id)->simplePaginate(10, ['*'], 'extracurriculars');
		$carousels = Carrousel::limit(5)->get();
		
		if(!$student) return redirect()->route('student.index');
		return view('dashboard.student.profile', [
			'student' => $student, 
			'dormrooms' => $dormrooms->sortBy('building'), 
			'classrooms' => $classrooms->sortBy('level'), 
			'hobbies' => $this->hobbies, 
			'wishes' => $this->wishes, 
			'educations' => $this->educations, 
			'religions' => $this->religions, 
			'donaters' => $this->donaters, 
			'reasons' => $this->reasons, 
			'tuitions' => $tuitions,
			'achievements' => $achievements,
			'permits' => $permits,
			'offenses' => $offenses,
			'organizations' => $organizations,
			'extracurriculars' => $extracurriculars,
			'carousels' => $carousels,
			]
		);
	}
	
	
	public function edit($id)
	{
		//
		$student = Student::find($id);
		$classrooms = Classroom::all();
		$dormrooms = Dormroom::all();
		return view('dashboard.student.edit', ['student' => $student, 'classrooms' => $classrooms, 'dormrooms' => $dormrooms]);
	}
	
	public function update(Request $request)
	{
		//
		$this->validate($request, [
			'stambuk' 		=> 'required|unique:students,stambuk,' . $request->id,
			'name'				=> 'required',
			'nik'					=> 'required|numeric',
			'nokk'				=> 'required|numeric',
			'birthplace'	=> 'required',
			'birthdate'		=> 'required'
		],
		[
			'stambuk.required'		=> 'Stambuk tidak boleh kosong.',
			'stambuk.unique'			=> 'Stambuk sudah terdaftar pada santri lain.',
			'name.required'				=> 'Nama santri tidak boleh kosong.',
			'nik.required'				=> 'NIK tidak boleh kosong.',
			'nik.numeric'					=> 'NIK hanya terdiri dari angka.',
			'nokk.required'				=> 'Nomor KK tidak boleh kosong.',
			'nokk.numeric'				=> 'Nomor KK hanya terdiri dari angka.',
			'birthplace.required'	=> 'Tempat lahir tidak boleh kosong.',
			'birthdate.required'	=> 'Tanggal lahir tidak boleh kosong.',
			]
		);
		$bd = explode('/', $request->birthdate);
		$bd = $bd[2] . '-' . $bd[1] . '-' . $bd[0];
		$std = Student::find($request->id);
		$olds = $std->stambuk;
		$std->update([
			'stambuk' 		=> $request->stambuk,
			'name'				=> $request->name,
			'nik'					=> $request->nik,
			'nokk'				=> $request->nokk,
			'classroom_id'=> $request->classroom_id,
			'dormroom_id'	=> $request->dormroom_id,
			'birthplace'	=> $request->birthplace,
			'birthdate'		=> $bd,
			'gender'			=> $request->gender,
			]
		);
		if($olds != $request->stambuk) {
			return redirect()->route('student.profile', $request->stambuk)->with('success', 'Data santri berhasil diubah');
		}
		
		return back()->with('success', 'Data santri berhasil diubah.');
	}
	
	public function destroy(Request $request)
	{
		//
		$student = Student::find($request->id);
		
		$tuition = Tuition::where('student_id', $request->id)->get();
		$permit = Permit::where('student_id', $request->id)->get();
		$offense = Offense::where('student_id', $request->id)->get();
		$achiev = Achievement::where('student_id', $request->id)->get();
		$organizations = $student->organization()->wherePivot('student_id', $student->id)->get();
		$extracurriculars = $student->extracurricular()->wherePivot('student_id', $student->id)->get();

		if($tuition->isNotEmpty() || $permit->isNotEmpty() || $offense->isNotEmpty() || $achiev->isNotEmpty() || $organizations->isNotEmpty() || $extracurriculars->isNotEmpty()){
			return back()->with('error', 'Tidak dapat menghapus santri karena telah memiliki riwayat. Silahkan EDIT atau Nonaktifkan santri!');
		}
		
		if($student->photo){
			$ori = public_path('assets/img/originals').'/'.$student->photo;
			$thumb = public_path('assets/img/student').'/'.$student->photo;
			if(file_exists($ori)) unlink($ori);
			if(file_exists($thumb)) unlink($thumb);
		}
		$student->delete();
		return back()->with('success', 'Santri berhasil dihapus.');
	}
	
	public function jsonsearch($s)
	{
		$results = Student::where('stambuk','like','%'.$s.'%')->orWhere('name','like','%'.$s.'%')->where('status', true)->get();
		// $results = Student::all();
		$data = array();
		foreach ($results as $res) {
			$data[] = ['name' => $res->stambuk . ' - ' . $res->name, 'value' => $res->id, 'text' => $res->name, 'stambuk' => $res->stambuk, 'url' => route('student.profile', $res->stambuk)];
		}
		return response()->json(['success' => true, 'results' => $data]);
	}
}