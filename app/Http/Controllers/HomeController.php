<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Helpers\MyPdf;

class HomeController extends Controller
{
	public function index(){

		$mpdf = new MyPdf();
		
		return view('home');
	}
}
