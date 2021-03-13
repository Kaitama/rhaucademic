<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsSampleExport;
use App\Exports\StudentsExport;
use App\Exports\TuitionsSampleExport;
use App\Exports\TuitionsExport;
use App\Exports\OffensesExport;
use App\Exports\AchievementsExport;
use App\Exports\OffensesSampleExport;

use App\Tuition;
use App\Classroom;

class ExcelController extends Controller
{
	//
	public function downloadtemplatestudent()
	{
		return (new StudentsSampleExport())->download('TEMPLATE_DATA_SANTRI.xlsx');
	}
	
	public function downloadtemplatetuition()
	{
		return Excel::download(new TuitionsSampleExport, 'TEMPLATE_PEMBAYARAN_UANG_SEKOLAH.xlsx');
	}
	
	public function downloadtemplateoffense()
	{
		return Excel::download(new OffensesSampleExport, 'TEMPLATE_PELANGGARAN_SANTRI.xlsx');
	}
	
	public function exporttuition(Request $request)
	{
		return Excel::download(new TuitionsExport($request->formonth, $request->foryear), date('d-m-Y') . '-UANG-SEKOLAH.xlsx');	
	}

	public function exportoffense(Request $request)
	{
		return Excel::download(new OffensesExport($request->startdate, $request->enddate), 'DATA-PELANGGARAN-' . date('d-m-Y') . '.xlsx');
	}
	
	public function exportachievement(Request $request)
	{
		return Excel::download(new AchievementsExport($request->startdate, $request->enddate), 'DATA-PRESTASI-' . date('d-m-Y') . '.xlsx');
	}
	
	public function exportstudents(Request $request)
	{
		$c = Classroom::find($request->c);
		return Excel::download(new StudentsExport($request->c), 'SANTRI_' . $c->name . '_' . time() . '.xlsx');
	}

	
}