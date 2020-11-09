<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsSampleExport;
use App\Exports\TuitionsSampleExport;
use App\Exports\TuitionsExport;

use App\Tuition;

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
	
	public function exporttuition(Request $request)
	{
		
		return Excel::download(new TuitionsExport($request->formonth, $request->foryear), date('d-m-Y') . '-UANG-SEKOLAH.xlsx');
		
	}
}