<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permit;
Use App\User;

class DatareportController extends Controller
{
	//
	public function permit(Request $request)
	{
		$datefrom = $request->get('datefrom');
		$dateto		= $request->get('dateto');
		$option		= $request->get('option');
		$data = null;
		if ($option == 1) {
			$data = Permit::where('datefrom', '>=', $this->convertDate($datefrom))->where('datefrom', '<=', $this->convertDate($dateto))->get();
		} elseif ($option == 2) {
			$data = Permit::where('datefrom', '>=', $this->convertDate($datefrom))->where('datefrom', '<=', $this->convertDate($dateto))->where('checkout', '!=', null)->where('checkin', null)->get();
		} elseif ($option == 3) {
			$data = Permit::where('datefrom', '>=', $this->convertDate($datefrom))->where('datefrom', '<=', $this->convertDate($dateto))->where('checkin', '!=', null)->get();
		} else {
			$data = null;
		}
		
		if($data){
			foreach ($data as $d) {
				$d->inby = $d->checkedin_by ? User::where('id', $d->checkedin_by)->pluck('name')->first() : '-';
				$d->outby = $d->checkedout_by ? User::where('id', $d->checkedout_by)->pluck('name')->first() : '-';
			}
		}
		
		return view('dashboard.report.permit', ['data' => $data]);
	}
	
	
	private function convertDate($d)
	{
		$dt = explode('/', $d);
		return $dt[2] . '-' . $dt[1] . '-' . $dt[0];
	}
}