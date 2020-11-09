<?php

namespace App\Http\Controllers;

use App\Student;
use App\Studentprofile;
use App\Classroom;
use App\Dormroom;
use App\Tuition;
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
		Excel::import(new StudentsImport, $request->file('excel'));
		
		return back()->with('success', 'Data santri berhasil di import.');
	}
	
	public function search(Request $request)
	{
		$string = $request->s;
		if(is_numeric($string)){
			$students = Student::where('stambuk','like',"%".$string."%")->with('classroom')->with('dormroom')->paginate(20);
		} else {
			$students = Student::where('name','like',"%".$string."%")->with('classroom')->with('dormroom')->paginate(20);
		}
		return view('dashboard.student.index', ['students' => $students]);
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
		$students = Student::where('status', 1)->with('dormroom')->with('classroom')->paginate(20);
		return view('dashboard.student.index', ['students' => $students]);
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
		$classrooms = Classroom::all();
		$dormrooms = Dormroom::all();
		$student = Student::where('stambuk', $stambuk)->with('studentprofile')->with('classroom')->with('tuition')->first();
		if(!$student) return redirect()->route('student.index');
		return view('dashboard.student.profile', ['student' => $student, 'dormrooms' => $dormrooms->sortBy('building'), 'classrooms' => $classrooms->sortBy('level'), 'hobbies' => $this->hobbies, 'wishes' => $this->wishes, 'educations' => $this->educations, 'religions' => $this->religions, 'donaters' => $this->donaters, 'reasons' => $this->reasons]);
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
		Student::find($request->id)->update([
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
		return back()->with('success', 'Data santri berhasil diubah.');
	}
	
	public function destroy(Request $request)
	{
		//
		$student = Student::find($request->id);
		if($student->photo){
			$ori = public_path('assets/img/originals').'/'.$student->photo;
			$thumb = public_path('assets/img/student').'/'.$student->photo;
			if(file_exists($ori)) unlink($ori);
			if(file_exists($thumb)) unlink($thumb);
		}
		$student->delete();
		return back()->with('success', 'Santri berhasil dihapus.');
	}
}