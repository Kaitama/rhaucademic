<?php

namespace App\Http\Controllers;

use App\Tuition;
use App\Student;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TuitionsImport;


class TuitionController extends Controller
{
	public function import(Request $request)
	{
		$import = new TuitionsImport;
		Excel::import($import, $request->file('excel'));
		$total = $import->getRowCount();
		$fails = $import->getFailCount();
		$success = $total - $fails;
		$sbks = $import->failEntry();
		// dd($sbks);
		$sbks = implode(', ', $sbks);
		
		if($success > 0) {
			return back()->with('success', $success . ' data dari total ' . $total .' berhasil diupload. ' . $fails . ' gagal, kemungkinan duplikat atau stambuk tidak valid ('. $sbks .').');
		} else {
			return back()->with('error', $fails . ' data ('. $sbks .') gagal diupload, kemungkinan duplikat atau stambuk tidak valid.');
		}
	}
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index(Request $request)
	{
		$month = $request->get('month');
		$year = $request->get('year');
		$tuitions = null;
		//
		if($month && $year) {
			$tuitions = Tuition::where('formonth', $month)->where('foryear', $year)->paginate(50);
		}elseif ($month) {
			$tuitions = Tuition::where('formonth', $month)->latest()->paginate(50);
		} elseif($year) {
			$tuitions = Tuition::where('foryear', $year)->latest()->paginate(50);
		} else {
			$tuitions = null;
		}
		// dd($tuitions);
		return view('dashboard.tuition.index', ['tuitions' => $tuitions]);
	}
	

	// perbaiki ini kok gak muncul tunggakannya
	public function arrears(Request $request)
	{
		$month = $request->get('month');
		$year = $request->get('year');
		$arrears = null;
		if($month == date('m') && $year == date('Y') && date('d') >= 28 || $month < date('m') && $year <= date('Y') && $year != null){
			$arrears = Student::doesnthave('tuition','or' ,function($query) use($month, $year) {
				$query->where('formonth', $month)->where('foryear', $year);
			})->get();
			$arrears->month = $month;
			$arrears->year = $year;
		} 
		return view('dashboard.tuition.arrears', ['arrears' => $arrears]);
	}
	
	/**
	* Show the form for creating a new resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function create()
	{
		//
	}
	
	/**
	* Store a newly created resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
	public function store(Request $request)
	{
		//
		$this->validate($request, [
			'paydate' => 'required',
			'student_id' => 'required',
			'formonth' => 'required',
			'foryear' => 'required|digits:4|integer',
			'nominal' => 'required|numeric'
		], [
			'paydate.required'	=> 'Tanggal pembayaran tidak boleh kosong.',
			'student_id.required'	=> 'Stambuk tidak boleh kosong.',
			'formonth.required' => 'Pembayaran untuk bulan harus dipilih.',
			'foryear.required'	=> 'Tahun tidak boleh kosong.',
			'foryear.digits'		=> 'Tahun tidak valid.',
			'foryear.integer'		=> 'Tahun tidak valid.',
			'nominal.required'	=> 'Nominal pembayaran tidak boleh kosong.',
			'nominal.numeric'		=> 'Nominal hanya terdiri dari angka.'
			]
		);
		
		$ids = explode(',', $request->student_id);
		$count = 0;
		foreach ($ids as $id) {
			$check = Tuition::where('student_id', $id)->where('foryear', $request->foryear)->where('formonth', $request->formonth)->first();
			if(!$check)
			{
				$date = explode('/', $request->paydate);
				$date = $date[2] . '-' . $date[1] . '-' . $date[0];
				Tuition::create([
					'student_id'	=> $id,
					'paydate'			=> $date,
					'formonth'		=> $request->formonth,
					'foryear'			=> $request->foryear,
					'nominal'			=> $request->nominal
					]
				);
				$count++;
			} 
			
		}
		if($count == 0) return back()->with('error', 'Tidak ada data pembayaran yang disimpan, kemungkinan duplikat.');
		return back()->with('success', 'Pembayaran uang sekolah berhasil disimpan.');
	}
	
	/**
	* Display the specified resource.
	*
	* @param  \App\Tuition  $tuition
	* @return \Illuminate\Http\Response
	*/
	public function show(Tuition $tuition)
	{
		//
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Tuition  $tuition
	* @return \Illuminate\Http\Response
	*/
	public function edit(Tuition $tuition)
	{
		//
	}
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\Tuition  $tuition
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, Tuition $tuition)
	{
		//
		$this->validate($request, [
			'paydate' => 'required',
			'formonth' => 'required',
			'foryear' => 'required|digits:4|integer',
			'nominal' => 'required|numeric'
		], [
			'paydate.required'	=> 'Tanggal pembayaran tidak boleh kosong.',
			'formonth.required' => 'Pembayaran untuk bulan harus dipilih.',
			'foryear.required'	=> 'Tahun tidak boleh kosong.',
			'foryear.digits'		=> 'Tahun tidak valid.',
			'foryear.integer'		=> 'Tahun tidak valid.',
			'nominal.required'	=> 'Nominal pembayaran tidak boleh kosong.',
			'nominal.numeric'		=> 'Nominal hanya terdiri dari angka.'
			]);
			$date = explode('/', $request->paydate);
			$date = $date[2] . '-' . $date[1] . '-' . $date[0];
			Tuition::find($request->id)->update([
				'paydate'			=> $date,
				'formonth'		=> $request->formonth,
				'foryear'			=> $request->foryear,
				'nominal'			=> $request->nominal
				]);
				
				return back()->with('success', 'Data pembayaran berhasil diubah.');
				
			}
			
			/**
			* Remove the specified resource from storage.
			*
			* @param  \App\Tuition  $tuition
			* @return \Illuminate\Http\Response
			*/
			public function destroy(Tuition $tuition, Request $request)
			{
				//
				$tuition->find($request->id)->delete();
				return back()->with('success', 'Data pembayaran berhasil dihapus.');
			}
		}