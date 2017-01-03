<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class StockValController extends Controller {

    // fungsi index pertama kali view dijalankan
    public function index() {
        $data = \DB::table('view_stock_valuation')
                        ->get();
        // $total_value = \DB::table('view_stock_valuation')
                        // ->sum('value_by_cogs');

        $total_value = \DB::table('view_stock_valuation')
                        ->sum('value_by_cogs');


        return view('inventory.stock_val.index', [
            'data' => $data,
            'total_value' => $total_value,
        ]);
    }


}
