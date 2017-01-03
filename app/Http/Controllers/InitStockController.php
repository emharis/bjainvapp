<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class InitStockController extends Controller {

    public function index() {
        $data = \DB::table('init_stock')->orderBy('created_at','desc')->get();
        return view('inventory.initstock.index', [
            'data' => $data 
        ]);
    }

    public function add(){
        $barang = \DB::table('view_barang')->get();

        return view('inventory.initstock.add',[
                'barang' => $barang
            ]);
    }

    public function insert(Request $req){
        return \DB::transaction(function()use($req){
            // generate date
            $tanggal = $req->tanggal;
            $arr_tgl = explode('-',$tanggal);
            $tanggal = new \DateTime();
            $tanggal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
            $tanggal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

            $barang = json_decode($req->barang);

            $init_stock_id = \DB::table('init_stock')
                                ->insertGetId([
                                        'tanggal' => $tanggal,
                                        'user_id' => \Auth::user()->id
                                    ]);

            foreach($barang->barang as $dt){
                \DB::table('init_stock_detail')->insert([
                        'init_stock_id' => $init_stock_id,
                        'barang_id' => $dt->id,
                        'quantity' => $dt->qty,
                        'unit_cost' => $dt->unit_cost,
                        'user_id' => \Auth::user()->id
                    ]);
            }

            return redirect('inventory/init-stock');
            
        });

    }

    public function edit($init_stock_id){
        $data = \DB::table('init_stock')->select('init_stock.*',\DB::raw('date_format(init_stock.tanggal,"%d-%m-%Y") as tanggal_formatted'))->find($init_stock_id);
        $data->init_stock_detail = \DB::table('init_stock_detail')
                                    ->join('barang','init_stock_detail.barang_id','=','barang.id')
                                    ->join('kategori','barang.kategori_id','=','kategori.id')
                                    ->select('init_stock_detail.*','barang.kode','barang.nama',\DB::raw('kategori.nama as kategori'))
                                    ->where('init_stock_id',$init_stock_id)->get();

        return view('inventory.initstock.edit',[
                'data'=>$data
            ]);
    }

    public function delete($init_stock_id){
        \DB::table('init_stock')->delete($init_stock_id);

        return redirect('inventory/init-stock');
    }

}
