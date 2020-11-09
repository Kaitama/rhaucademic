<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Client;

class ClientController extends Controller
{
	//
	public function check()
	{
		return response()->json(['data' => 'success']);
	}
	
}