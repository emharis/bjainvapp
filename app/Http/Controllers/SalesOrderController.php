<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SalesOrderController extends Controller {


    // fungsi tampilkan halaman sales order
    public function index() {
        $data = \DB::table('view_sales')
                ->orderBy(\DB::raw('tgl'),'desc')->get();
        
        // // create select data customer
        // $customer = \DB::table('customer')->get();
        // $select_customer = [];
        // foreach($customer as $dt ){
        //   $select_customer[$dt->id] = $dt->nama;
        // }

        $total = \DB::table('sales')
                    ->whereStatus('V')
                    ->sum('total');
        
        
        return view('sales.order.salesorder', [
            'data' => $data,
            'total' => $total,
            // 'select_customer' => $select_customer
        ]);
    }

    public function filter(Request $req){
      // create select data customer
      $customer = \DB::table('customer')->get();
      $select_customer = [];
      foreach($customer as $dt ){
        $select_customer[$dt->id] = $dt->nama;
      }

      // get data by filter
      if ($req->filter_by == 'order_date'){
        // filter by order date
        // generate date
        $awal = $req->filter_date_start;
        $arr_tgl = explode('-',$awal);
        $awal = new \DateTime();
        $awal->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
        $awal_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $akhir = $req->filter_date_end;
        $arr_tgl = explode('-',$akhir);
        $akhir = new \DateTime();
        $akhir->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
        $akhir_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

        $data = \DB::table('view_sales')
                  ->whereBetween('tgl',[$awal_str,$akhir_str])
                  // ->where('tgls','>=',$awal_str)
                  // ->where('tgl','<=',$akhir)
                  ->orderBy('tgl','desc')
                  ->get();

      }else if($req->filter_by =='customer'){
        $data = \DB::table('view_sales')
                  ->where('customer_id',$req->filter_select_customer)
                  ->orderBy('tgl','desc')
                  ->get();
      }else if($req->filter_by =='open'){
        $data = \DB::table('view_sales')
                  ->whereStatus('O')
                  ->orderBy('tgl','desc')
                  ->get();
      }else if($req->filter_by =='validated'){
        $data = \DB::table('view_sales')
                  ->whereStatus('V')
                  ->orderBy('tgl','desc')
                  ->get();
      }

      return view('sales.order.filter', [
          'data' => $data,
          'select_customer' => $select_customer,
      ])->with($req->all());
    }

    // TAMPILKAN FORM ADD SALES ORDER\
    public function add(){
        return view('sales.order.salesorderadd',[

            ]);
    }
    // END OF TAMPILKAN FORM ADD SALES ORDER

    // GET DATA CUSTOMER
    public function getCustomer(Request $req){
        $data = \DB::select('select id as data,nama as value
                from customer
                where nama like "%'.$req->get('nama').'%"');

        $data_res = ['query'=>'Unit','suggestions' => $data];
        echo json_encode($data_res);
    }
    // END OF GET DATA CUSTOMER

    // GET DATA SALESPERSON
    public function getSalesperson(Request $req){
        $data = \DB::select('select id as data,nama , kode, concat("[",kode,"] - ",nama) as value
                from salesman
                where nama like "%'.$req->get('nama').'%"');

        $data_res = ['query'=>'Unit','suggestions' => $data];
        echo json_encode($data_res);
    }
    // END OF GET DATA SALESPERSON

    // GET DATA PRODUCT
    public function getProduct(Request $req){

         $data = \DB::select('select id as data,concat("[",kode,"] ",kategori," ",nama) as value,kode, unit_price, quantity_on_hand
                from view_barang
                where quantity_on_hand > 0 and unit_price > 0 and concat("[",kode,"] ",kategori," ",nama) like "%'. $req->get('nama') .'%"' ) ;


        $data_res = ['query'=>'Unit','suggestions' => $data];
        echo json_encode($data_res);
    }
    // END OF GET DATA PRODUCT

    // INSERT SALES ORDER
    public function insert(Request $req){
        // echo 'input sales order';
        return \DB::transaction(function()use($req){
            $so_master = json_decode($req->so_master);
            $so_barang = json_decode($req->so_barang)->barang;

            // Get SO Counter
            $so_prefix = Appsetting('so_prefix');
            $so_counter = Appsetting('so_counter');
            // Generate SO Number
            $so_number = "SO" . "/".date('Y/m')."/" . $so_counter++;

            // generate tanggal
            $order_date = $so_master->order_date;
            $arr_tgl = explode('-',$order_date);
            $fix_order_date = new \DateTime();
            $fix_order_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
            $fix_order_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

            // insert into table jual
            $so_id = \DB::table('sales')->insertGetId([
                            'so_no' => $so_number,
                            // 'no_inv' => $sales_inv_no,
                            'tgl' => $fix_order_date,
                            'customer_id' => $so_master->customer_id,
                            'salesman_id' => $so_master->salesperson_id,
                            'subtotal' => $so_master->subtotal,
                            'disc' => $so_master->disc,
                            'total' => $so_master->total,
                            'status' => 'O',
                            'user_id' => \Auth::user()->id,
                            'note' => $req->note
                        ]);

            // insert into table jual_barang
            foreach($so_barang as $dt){
                \DB::table('sales_detail')->insert([
                        'sales_id' => $so_id,
                        'barang_id' => $dt->id,
                        'qty' => $dt->qty,
                        'cogs' => \DB::raw('sp_cogs_of_barang_by_periode(' . $dt->id . ',"' . $fix_order_date_str . '")'),
                        'std_unit_price' => $dt->unit_price,
                        'unit_price' => $dt->sup_price,
                        'subtotal' => $dt->subtotal,
                        'user_id' => \Auth::user()->id,
                    ]);
            }

            // update sales_counter
            UpdateAppsetting('so_counter',$so_counter);

            return redirect('sales/order/edit/' . $so_id);
        });
    }
    // END OF INSERT SALES ORDER

    // CANCEL VALIDATED SALEES ORDER
    public function cancelOrder(Request $req){
      return \DB::transaction(function()use($req){

        // update status ke cancel
        // perubahan stok telah di proses melalui mysql trigger
        \DB::table('sales')
            ->whereId($req->sales_order_id)
            ->update([
                'status' => 'C'
            ]);

        // $jual = \DB::table('jual')
        //         ->find($req->sales_order_id);
        // $jual_barang = \DB::table('jual_barang')
        //                 ->where('sales_id',$jual->id)->get();
        // $stok_moving = \DB::table('stok_moving')
        //                 ->where('sales_id',$jual->id)
        //                 ->get();
        // // restore stock
        // foreach($stok_moving as $dt){
        //   \DB::table('stok')
        //         ->where('id',$dt->stok_id)
        //         ->update([
        //           'current_stok' => \DB::raw('current_stok + ' . $dt->jumlah)
        //         ]);
        // }

        // // delete stok moving
        // \DB::table('stok_moving')
        //     ->where('sales_id',$jual->id)
        //     ->delete();

        // // delete customer invoice
        // \DB::table('customer_invoice')
        //     ->where('sales_id',$jual->id)
        //     ->delete();

        // // delete jual
        // \DB::table('jual')
        //     ->delete($jual->id);

        return redirect('sales/order');

        // // $stok_moving = \DB::table('stok_moving')->where()
      });
    }
    // END OF CANCEL VALIDATED SALEES ORDER

    // TAMPILKAN FORM EDIT SALES ORDER
    public function edit($id){
        $so_master = \DB::table('view_sales')->find($id);
        $so_barang = \DB::table('view_sales_detail')
                        // ->join('view_stok_barang','view_sales_detail.barang_id','=','view_stok_barang.id')
                        // ->select('view_sales_detail.*','view_stok_barang.stok')
                        ->where('sales_id',$so_master->id)->get();

        if($so_master->status == 'O'){
            return view('sales/order/salesorderedit',[
                'so_master' => $so_master,
                'so_barang' => $so_barang,
            ]);
        }
        else if($so_master->status == ('V' || 'C') ){
            $invoice_num = \DB::table('customer_invoice')->where('sales_id',$so_master->id)->count();

            // open view validate
            return view('sales/order/salesordervalidated',[
                    'so_barang' => $so_barang,
                    'so_master' => $so_master,
                    'invoice_num' => $invoice_num
                ]);
        }


    }
    // END OF TAMPILKAN FORM EDIT SALES ORDER

    // UPDATE SALES ORDER
    public function update(Request $req){
        // echo 'input sales order';
        return \DB::transaction(function()use($req){
            $so_master = json_decode($req->so_master);
            $so_barang = json_decode($req->so_barang)->barang;

            // echo json_encode($so_barang) . '<br>';

            // generate tanggal
            $order_date = $so_master->order_date;
            $arr_tgl = explode('-',$order_date);
            $fix_order_date = new \DateTime();
            $fix_order_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

            // Update Po Master / table Beli
            \DB::table('sales')
                ->where('id',$so_master->id)
                ->update([
                        'tgl' => $fix_order_date,
                        'customer_id' => $so_master->customer_id,
                        'salesman_id' => $so_master->salesperson_id,
                        'subtotal' => $so_master->subtotal,
                        'disc' => $so_master->disc,
                        'total' => $so_master->total,
                        'note' => $so_master->note
                    ]);

            // Delete data barang sebelumnya
            \DB::table('sales_detail')
                ->where('sales_id',$so_master->id)
                ->delete();

            // Insert data barang baru
            foreach($so_barang as $dt){
                \DB::table('sales_detail')->insert([
                        'sales_id' => $so_master->id,
                        'barang_id' => $dt->id,
                        'qty' => $dt->qty,
                        'std_unit_price' => $dt->unit_price,
                        'unit_price' => $dt->sup_price,
                        'subtotal' => $dt->subtotal,
                        'user_id' =>  \Auth::user()->id,
                    ]);
            }

            return redirect('sales/order/edit/' . $so_master->id);
        });
    }
    // END OF UPDATE SALES ORDER

    // VALIDATE SALES ORDER
    public function validateSo(Request $req){
        return \DB::transaction(function()use($req){
            // update sales master status / jual status
            \DB::table('sales')
                ->where('id',$req->so_master_id)
                ->update([
                        'status' => 'V'
                    ]);

            // $so_master = \DB::table('jual')->find($req->so_master_id);
            // $so_barang = \DB::table('jual_barang')
            //                 ->where('sales_id',$req->so_master_id)
            //                 ->get();

            // // update stok barang & stok_moving
            // foreach($so_barang as $dt){
            //     echo 'update_stok_barang_id : ' . $dt->id . '<br>';
            //     // stokMovingJual($dt->barang_id,$dt->qty);
            //     $stok = \DB::table('stok')
            //         ->where('barang_id',$dt->barang_id )
            //         ->where('current_stok','>',0 )
            //         ->orderBy('tgl','asc')
            //         ->get();

            //     $qty_for_sell = $dt->qty;
            //     $jual_barang_id = $dt->id;
            //     foreach($stok as $st){
            //         if($st->current_stok >= $qty_for_sell){
            //             // inputkan ke tabel stok moving
            //             \DB::table('stok_moving')
            //                 ->insert([
            //                         'stok_id' => $st->id,
            //                         'jumlah' => $qty_for_sell,
            //                         'tipe' => 'O',
            //                         'sales_id' => $req->so_master_id,
            //                         'jual_barang_id' => $jual_barang_id,
            //                         'user_id' => \Auth::user()->id
            //                     ]);

            //             // kurangi stok current
            //             \DB::table('stok')
            //                 ->where('id',$st->id)
            //                 ->update([
            //                         'current_stok' => \DB::raw('current_stok - ' . $qty_for_sell)
            //                     ]);

            //             break;
            //         }else{
            //             $stok_qty_on_db = $st->current_stok;
            //             // kurangi table stok
            //             \DB::table('stok')
            //                 ->where('id',$st->id)
            //                 ->update([
            //                         'current_stok' => 0
            //                     ]);
            //             // input ke table stok moving
            //             \DB::table('stok_moving')
            //                 ->insert([
            //                         'stok_id' => $st->id,
            //                         'jumlah' => $stok_qty_on_db,
            //                         'tipe' => 'O',
            //                         'sales_id' => $req->so_master_id,
            //                         'user_id' => \Auth::user()->id
            //                     ]);
            //             // kurangi qty_for_sell
            //             $qty_for_sell = $qty_for_sell - $stok_qty_on_db;
            //         }
            //     }
            // }

            // // CREATE INVOICE FROM SALES ORDER
            // $invoice = $this->createInvoice($so_master->id);


            // // get invoice item number
            // $invoice_item_num = \DB::table('appsetting')
            //                         ->where('name','invoice_item_number')
            //                         ->first()->value;

           

            //     // INPUTKAN DAFTAR BARANG DARI SALES ORDER KE INVOICE
            //                         echo '===================================================== <br/>';
            //     $itemnum = 1;
            //     $subtotal=0;
            //     foreach($so_barang as $sb){
            //         echo '[PARENT] cresate_invoice_barang_id  : ' . $sb->id . ' : ';
            //         if($itemnum++ <= ($invoice_item_num )){
            //             echo 'cresate_invoice_barang_id  : ' . $sb->barang_id . '<br>';
            //             // input data ke detil invoice
            //             \DB::table('customer_invoice_detail')
            //             ->insert([
            //                     'customer_invoice_id' => $invoice->id,
            //                     'barang_id' => $sb->barang_id,
            //                     'user_id' => \Auth::user()->id
            //                 ]);
            //             $subtotal += $sb->qty * $sb->unit_price;
            //         }else{
            //             echo 'cresate_invoice_barang_id  : ' . $sb->barang_id . '<br>';
            //             // Update Invoice
            //             \DB::table('customer_invoice')
            //                 ->where('id',$invoice->id)
            //                 ->update([
            //                         'subtotal' => $subtotal,
            //                         'total' => $subtotal,
            //                         'amount_due' => $subtotal,
            //                     ]);

            //             // create invoice baru
            //             $invoice = $this->createInvoice($so_master->id);

            //             // input data ke detil invoice
            //             \DB::table('customer_invoice_detail')
            //             ->insert([
            //                     'customer_invoice_id' => $invoice->id,
            //                     'barang_id' => $sb->barang_id,
            //                     'user_id' => \Auth::user()->id
            //                 ]);
            //             $subtotal = $sb->qty * $sb->unit_price;

            //             // clear subtotal
            //             // $subtotal = 0;
            //             $itemnum=1;
            //         }

            //         // Update Invoice terakhir yang ter-create dan bagian ini inputkan discount
            //             \DB::table('customer_invoice')
            //                 ->where('id',$invoice->id)
            //                 ->update([
            //                         'subtotal' => $subtotal,
            //                         'disc' => $so_master->disc,
            //                         'total' => $subtotal -  $so_master->disc,
            //                         'amount_due' => $subtotal -  $so_master->disc,

            //                     ]);
            //     }
            

            // END OF CREATE INVOICE FROM SALES ORDER

            return redirect()->back();

        });
    }

    public function createInvoice($so_master_id){
        $so_master = \DB::table('jual')->find($so_master_id);

        // create invoice baru
        // Get invoice counter
        $invoice_counter = \DB::table('appsetting')
                        ->whereName('invoice_counter')
                        ->first()->value;

        // Generate invoice number id
        $salesperson = \DB::table('salesman')->find($so_master->salesman_id);
        $invoice_number_id = $salesperson->kode . '/' . date('dm') . '0' . $invoice_counter;

        // update Invoice Counter
        \DB::table('appsetting')
            ->whereName('invoice_counter')
            ->update([
                    'value' => \DB::raw('value + 1')
                ]);

        // Generate Due Date
        $due_date_interval = \DB::table('appsetting')
                                ->whereName('customer_jatuh_tempo')
                                ->first()->value;
        $due_date = new \DateTime();
        $due_date = $due_date->modify('+' . $due_date_interval . ' days');

        // Create new invoice to table customer_invoice
        $invoice_id = \DB::table('customer_invoice')
        ->insertGetId([
                'no_inv' => $invoice_number_id,
                'invoice_date' => new \DateTime(),
                'due_date' => $due_date,
                'sales_id' => $so_master->id,
                'status' => 'O',
                'user_id' => \Auth::user()->id,
                // 'total' => $so_master->total,
                // 'amount_due' => $so_master->total,
            ]);

        $invoice  = \DB::table('customer_invoice')->find($invoice_id);

        return $invoice;
    }

    // OPEN INVOICE
    public function toInvoice($so_master_id){
        $so_master = \DB::table('view_sales')
                        ->find($so_master_id);
        $so_barang = \DB::table('view_sales_detail')
                        ->where('sales_id',$so_master_id)
                        // ->select('kode_barang','nama_barang','satuan','berat','qty','unit_price','subtotal')
                        ->get();


        // get invoice
        if (\DB::table('customer_invoice')->where('sales_id',$so_master->id)->count() > 1){
            // tampilkan lebih dari 1 invoice
            $cust_inv = \DB::table('view_customer_invoice')
                    ->where('sales_id',$so_master->id)
                    ->get();

            return view('sales/order/salesorderinvoicelist',[
                    'so_master' => $so_master,
                    'so_barang' => $so_barang,
                    'cust_invoice' => $cust_inv,
                ]);
        }else{
            // tampilkan 1 invoice
            $cust_inv = \DB::table('customer_invoice')
                    ->where('sales_id',$so_master->id)
                    ->select('customer_invoice.*',
                                \DB::raw("date_format(`invoice_date`,'%d-%m-%Y') as invoice_date_formatted"),
                                \DB::raw("date_format(`due_date`,'%d-%m-%Y') as due_date_formatted")
                            )
                    ->first();

            // get payments
            $payments = \DB::table('customer_invoice_payment')
                            ->where('customer_invoice_id',$cust_inv->id)
                            ->select('customer_invoice_payment.*',
                                    \DB::raw("date_format(`payment_date`,'%d-%m-%Y') as payment_date_formatted")
                                )
                            ->get();

            return $this->showInvoice($so_master,$cust_inv,$so_barang,$payments);
        }


        // // =======================================================================================


    }
    // END OF OPEN INVOICE

    // SHOW INVOICE FOR MULTI INVOICE
    public function showInvoiceMulti($invoice_id){
        $cust_invoice = \DB::table('customer_invoice')
                    ->where('id',$invoice_id)
                    ->select('customer_invoice.*',
                                \DB::raw("date_format(`invoice_date`,'%d-%m-%Y') as invoice_date_formatted"),
                                \DB::raw("date_format(`due_date`,'%d-%m-%Y') as due_date_formatted")
                            )
                    ->first();
        $so_master = \DB::table('view_sales')->find($cust_invoice->sales_id);
        $barang = \DB::table('view_sales_detail')
                        ->where('sales_id',$so_master->id)
                        ->whereRaw('view_sales_detail.barang_id in (select barang_id from customer_invoice_detail where customer_invoice_id = ' . $invoice_id . ')')
                        ->select('nama_barang','satuan','berat','qty','unit_price','subtotal')
                        ->get();
        $payments =  \DB::table('customer_invoice_payment')
                            ->where('customer_invoice_id',$invoice_id)
                            ->select('customer_invoice_payment.*',
                                    \DB::raw("date_format(`payment_date`,'%d-%m-%Y') as payment_date_formatted")
                                )
                            ->get();
        return  $this->showInvoice($so_master,$cust_invoice,$barang,$payments,true);
    // END SHOW INVOICE FOR MULTI INVOICE
    }

    // PRIVATE FUNCTION SHOW INVOICE
    public function showInvoice($so_master,$cust_invoice,$barang,$payments,$multi_invoice = false){
        return view('sales/order/salesorderinvoice',[
                    'so_master' => $so_master,
                    'barang' => $barang,
                    'cust_inv' => $cust_invoice,
                    'payments' => $payments,
                    'multi_invoice' => $multi_invoice,
                ]);
    }
    // END OF PRIVATE FUNCTION SHOW INVOICE


    // REGISTER PAYMENT
    public function regPayment($cust_inv_id){
        // echo 'Register Payment SO';
        $cust_inv = \DB::table('customer_invoice')->find($cust_inv_id);
        $so_master = \DB::table('view_sales')->find($cust_inv->sales_id);

        return view('sales/order/payment',[
                'cust_inv' => $cust_inv,
                'so_master' => $so_master,
            ]);
    }
    // END OF REGISTER PAYMENT

    // SAVE PAYMENT
    public function savePayment(Request $req){
        // insert into payment
        return \DB::transaction(function()use($req){
            // create payment_number
            $payment_counter = \DB::table('appsetting')->whereName('customer_payment_counter')->first()->value;
            $payment_prefix = \DB::table('appsetting')->whereName('customer_payment_prefix')->first()->value;

            $payment_number = $payment_prefix  . '/' . date('Y') . '/000'. $payment_counter;

            // update counter
            \DB::table('appsetting')
                    ->whereName('customer_payment_counter')
                    ->update([
                            'value' => \DB::raw('value + 1')
                        ]);

            $payment_date = $req->payment_date;
            $arr_tgl = explode('-',$payment_date);
            $fix_payment_date = new \DateTime();
            $fix_payment_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);

            // input payment
            \DB::table('customer_invoice_payment')
            ->insert([
                    'customer_invoice_id' => $req->customer_inv_id,
                    'payment_amount' => $req->payment_amount,
                    'payment_date' => $fix_payment_date,
                    'payment_number' => $payment_number,
                ]);

            // Update Status Invoice
            \DB::table('customer_invoice')
                ->where('id',$req->customer_inv_id)
                ->whereRaw('amount_due - ' . $req->payment_amount . ' = 0')
                ->update([
                        'status' => 'P'
                    ]);

            // update invoice , amount due
            \DB::table('customer_invoice')
                ->where('id',$req->customer_inv_id)
                ->update([
                        'amount_due' => \DB::raw('amount_due - ' . $req->payment_amount)
                    ]);

                // cek if multi invoice
                $invoice_doc_num = \DB::table('customer_invoice')->selectRaw('select * from customer_invoice where sales_id = (select sales_id from customer_invoice where id = ' . $req->customer_inv_id . ')')->count();
                $so_master_id = \DB::table('customer_invoice')->find($req->customer_inv_id)->sales_id;

                if($invoice_doc_num > 1){
                    return redirect('sales/order/show-invoice-multi/' . $req->customer_inv_id);
                }else{
                    return redirect('sales/order/invoice/' . $req->so_master_id);
                }


            // return redirect()->back();
        });

    }
    // END OF SAVE PAYMENT

    // DELETE INVOICE PAYMENT
    public function deletePayment(Request $req){
        // get payment
            $payment = \DB::table('customer_invoice_payment')
                        ->find($req->payment_id);

            // kembalikan payment_amount
            \DB::table('customer_invoice')
                ->where('id',$payment->customer_invoice_id)
                ->update([
                        'amount_due' => \DB::raw('amount_due + ' . $payment->payment_amount),
                        'status' => 'O'
                    ]);

            // delete payment from database
            \DB::table('customer_invoice_payment')
                ->where('id',$req->payment_id)
                ->delete();

            return redirect()->back();
    }
    // END OF DELETE INVOICE PAYMENT

    // FUNGSI DELETE SALES ORDER
    public function delete($id){
        return \DB::transaction(function()use($id){
            // hapus dari database sales order
            \DB::table('sales')
                ->delete($id);

            return redirect()->back();
        });
    }
    // END OF FUNGSI DELETE SALES ORDER

}
