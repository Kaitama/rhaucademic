<?php

namespace App\Http\Controllers;

use App\Permit;
use App\Student;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class PermitController extends Controller
{
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		//
		$permits = Permit::latest()->paginate(10);
		
		return view('dashboard.nurture.permit', ['permits' => $permits]);
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
			'student'	 		=> 'required',
			'datefrom' 		=> 'required',
			'dateto'			=> 'required',
			'description'	=> 'required',
			'signdate'		=> 'required',
			'timeto'			=> 'required'
		], [
			'signdate.required'		=> 'Tanggal surat tidak boleh kosong.',
			'student.required'		=> 'Nama santri tidak boleh kosong.',
			'description.required'=> 'Keperluan tidak boleh kosong.',
			'datefrom.required'		=> 'Tanggal mulai tidak boleh kosong.',
			'dateto.required'			=> 'Tanggal berakhir tidak boleh kosong.',
			'timeto.required'			=> 'Jam berakhir tidak boleh kosong.'
			]
		);
		
		$sign = base64_encode(Hash::make(Auth::id() .'-'. $request->student));
		Permit::create([
			'student_id'	=> $request->student,
			'user_id'			=> Auth::id(),
			'datefrom'		=> $this->dateFormat($request->datefrom),
			'dateto'			=> $this->dateFormat($request->dateto) . ' ' . $request->timeto,
			'description'	=> $request->description,
			'signdate'		=> $this->dateFormat($request->signdate),
			'signature'		=> $sign
			]
		);
		
		return back()->with('success', 'Surat izin berhasil dibuat.');
	}
	
	/**
	 * VALIDATING PERMIT
	 */
	public function validating(Permit $permit, $hash)
	{
		$data = $permit->where('signature', $hash)->first();
		if($data){
			$data->dayfrom = $this->getDayName($data->datefrom);
			$data->textfrom = date('d', strtotime($data->datefrom)) . ' ' . $this->getMonthName($data->datefrom) . ' ' .date('Y', strtotime($data->datefrom));
			$data->dayto = $this->getDayName($data->dateto);
			$data->textto = date('d', strtotime($data->dateto)) . ' ' . $this->getMonthName($data->dateto) . ' ' .date('Y', strtotime($data->dateto));
			if(date('Y-m-d H:i:s') <= $data->dateto) $data->active = true; else $data->active = false;
			return view('dashboard.nurture.permitvalidate', ['data' => $data]);
		} else {
			return abort(404);
		}
		
		// if(date('Y-m-d H:i:s') <= $data->dateto) $active = true; else $active = false;
		// if($active) 
		// {

		// }
		
	}
	
	/**
	* Display the specified resource.
	*
	* @param  \App\Permit  $permit
	* @return \Illuminate\Http\Response
	*/
	public function show(Permit $permit, $id)
	{
		//
		$data = $permit->find($id);
		if($data)
		{
			
			$data->dayfrom = $this->getDayName($data->datefrom);
			$data->dayto = $this->getDayName($data->dateto);
			$data->monthsign = $this->getMonthName($data->signdate);
			$data->monthfrom = $this->getMonthName($data->datefrom);
			$data->monthto = $this->getMonthName($data->dateto);
			
			$pdf = PDF::loadview('dashboard.nurture.permitview',['data' => $data]);
			return $pdf->stream('SURAT_IZIN_' . $data->student['stambuk'] . '.pdf');
			
		} else {
			return abort(404);
		}
	}
	
	/**
	* Show the form for editing the specified resource.
	*
	* @param  \App\Permit  $permit
	* @return \Illuminate\Http\Response
	*/
	public function edit(Permit $permit)
	{
		//
	}
	
	/**
	* Update the specified resource in storage.
	*
	* @param  \Illuminate\Http\Request  $request
	* @param  \App\Permit  $permit
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, Permit $permit)
	{
		//
	}
	
	/**
	* Remove the specified resource from storage.
	*
	* @param  \App\Permit  $permit
	* @return \Illuminate\Http\Response
	*/
	public function destroy(Permit $permit, Request $request)
	{
		//
		$permit->find($request->id)->delete();
		return back()->with('success', 'Data perizinan berhasil dihapus.');
	}
	
	
	private function dateFormat($date)
	{
		$format = explode('/', $date);
		return $format[2] . '-' . $format[1] . '-' . $format[0];
	}
	
	private function getDayName($date)
	{
		$day_en = date('D', strtotime($date));
		switch ($day_en) 
		{
			case 'Mon':
				return 'Senin'; 
			break;
			case 'Tue':
				return 'Selasa'; 
			break;
			case 'Wed':
				return 'Rabu'; 
			break;
			case 'Thu':
				return 'Kamis'; 
			break;
			case 'Fri':
				return 'Jum\'at'; 
			break;
			case 'Sat':
				return 'Sabtu'; 
			break;
			default:
			return 'Minggu'; break;
		}
	}
	
	private function getMonthName($date)
	{
		$format = explode('-', $date);
		$mo = $format[1] - 1;
		$bln = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
		return $bln[$mo];
	}
}