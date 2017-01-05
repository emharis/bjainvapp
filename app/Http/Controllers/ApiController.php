<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiController extends Controller {

    public function getPoInvoice(){
        // get po_master
        $po_master = \DB::table('view_beli')->find($id);
        // get po_barang
        $po_barang = \DB::table('view_beli_barang')
                        ->where('beli_id',$po_master->id)
                        ->get();
        // get supplier_bill
        $sup_bill = \DB::table('supplier_bill')
                    ->where('beli_id',$po_master->id)
                    ->select('supplier_bill.*',
                                \DB::raw("date_format(`bill_date`,'%d-%m-%Y') as bill_date_formatted"),
                                \DB::raw("date_format(`due_date`,'%d-%m-%Y') as due_date_formatted")
                            )
                    ->first();

        // get payments
        $payments = \DB::table('supplier_bill_payment')
                        ->where('supplier_bill_id',$sup_bill->id)
                        ->select('supplier_bill_payment.*',
                                \DB::raw("date_format(`tanggal`,'%d-%m-%Y') as payment_date_formatted")
                            )
                        ->get();

        return [
            'po_master' => $po_master,
            'po_barang' => $po_barang,
            'sup_bill' => $sup_bill,
            'payments' => $payments
        ];
    }

    // Delete data payment Customer Invoice
    public function deleteCustomerPayment(Request $req){
        \DB::transaction(function()use($req){
            // get payment
            $payment = \DB::table('customer_invoice_payment')
                ->find($req->payment_id);

            // update status customer_invoice
            // \DB::table('customer_invoice')
            //     ->where('id',$payment->customer_invoice_id)
            //     ->update([
            //             'status' => 'O',
            //             'amount_due' => \DB::raw('amount_due + ' . $payment->payment_amount)
            //         ]);

            // delete datta payment customer
            \DB::table('customer_invoice_payment')->delete($req->payment_id);
        });
        
    }

    /**
     * Simpan data Payment Customer
     **/
    public function regCustomerPayment(Request $req){
        \DB::transaction(function()use($req){
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

            // return redirect()->back();
        });
    }


    /**
     * Simpan data payment Supplier
     **/
    public function regSupplierPayment(Request $req){
        \DB::transaction(function()use($req){
            // create payment_number
            $sup_payment_counter = \DB::table('appsetting')->whereName('supplier_payment_counter')->first()->value;
            $sup_payment_prefix = \DB::table('appsetting')->whereName('supplier_payment_prefix')->first()->value;

            $sup_payment_number = $sup_payment_prefix  . '/' . date('Y') . '/000'. $sup_payment_counter;

            // update counter
            \DB::table('appsetting')
                    ->whereName('supplier_payment_counter')
                    ->update([
                            'value' => \DB::raw('value + 1')
                        ]);

            $sup_payment_date = $req->payment_date;
            $arr_tgl = explode('-',$sup_payment_date);
            $fix_payment_date = new \DateTime();
            $fix_payment_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);     

            // input payment
            \DB::table('supplier_bill_payment')
            ->insert([
                    'supplier_bill_id' => $req->supplier_bill_id,
                    'total' => $req->payment_amount,
                    'tanggal' => $fix_payment_date,
                    'payment_number' => $sup_payment_number,
                ]);    

            // Update Status Invoice
            \DB::table('supplier_bill')
                ->where('id',$req->supplier_bill_id)
                ->whereRaw('amount_due - ' . $req->payment_amount . ' = 0')
                ->update([
                        'status' => 'P'
                    ]);

            // update invoice , amount due
            \DB::table('supplier_bill')
                ->where('id',$req->supplier_bill_id)
                ->update([
                        'amount_due' => \DB::raw('amount_due - ' . $req->payment_amount)
                    ]);

            // return redirect()->back();
        });
    }

    public function cetakSalesOrderInvoice($invoice_id){
        $data = \DB::table('view_customer_invoice')->find($invoice_id);
        $data_barang = \DB::table('view_sales_detail')
                        ->where('sales_id',$data->sales_id)
                        ->get();

        $tmpdir = sys_get_temp_dir();   # ambil direktori temporary untuk simpan file.
        $file =  tempnam($tmpdir, 'ctk');  # nama file temporary yang akan dicetak
        $handle = fopen($file, 'w');
        $condensed = Chr(27) . Chr(33) . Chr(4);
        $bold1 = Chr(27) . Chr(69);
        $bold0 = Chr(27) . Chr(70);
        $initialized = chr(27).chr(64);
        $condensed1 = chr(15);
        $condensed0 = chr(18);
        $header_space = "                     "  ;
        $header_space_mid = "                                                              ";
        $left_space_row_barang = "";
        $Data  = $initialized;
        $Data .= $condensed1;

        // HEADER
        $Data .= "\n";
        $Data .= $header_space . $data->no_inv . $header_space_mid . $data->nama_customer .  "\n";
        $Data .= $header_space.$data->invoice_date_formatted . $header_space_mid . $data->alamat ."\n";
        $Data .= $header_space.$data->due_date_formatted."\n";
        // $Data .= "  ".$bold1."Tanggulangin, Sidoarjo 61272".$bold0."      |\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";

        $nama_barang = "                                  ";
        $satuan = "        ";
        $berat = "        ";
        $qty = "             ";
        $harga = "              ";
        $harga_unit = "                "; // harga * bebrat
        $jumlah = "                   "; // harga_unit * qty

        $rownum=1;
        foreach($data_barang as $dt){
            $ctk_nama_barang = substr($dt->kategori ." " . $dt->nama_barang,0,34);
            $ctk_nama_barang=$ctk_nama_barang . substr($nama_barang,0,strlen($nama_barang)-strlen($ctk_nama_barang));

            $ctk_satuan = substr($dt->satuan ,0,15);
            $ctk_satuan=$ctk_satuan . substr($satuan,0,strlen($satuan)-strlen($ctk_satuan));

            $ctk_berat = $dt->berat;
            $ctk_berat= substr($berat,0,strlen($berat)-strlen($ctk_berat)) . $ctk_berat ;

            $ctk_qty = $dt->qty;
            $ctk_qty= substr($qty,0,strlen($qty)-strlen($ctk_qty)) . $ctk_qty ;

            $ctk_harga = number_format($dt->unit_price/$dt->berat,2,',','.');
            $ctk_harga= substr($harga,0,strlen($harga)-strlen($ctk_harga)) .  $ctk_harga ;

            $ctk_harga_unit = number_format($dt->unit_price,2,',','.');
            $ctk_harga_unit= substr($harga_unit,0,strlen($harga_unit)-strlen($ctk_harga_unit)) . $ctk_harga_unit;

            $ctk_jumlah = number_format($dt->unit_price * $dt->qty,2,',','.');
            $ctk_jumlah= substr($jumlah,0,strlen($jumlah)-strlen($ctk_jumlah)) .$ctk_jumlah ;

            $Data .= $left_space_row_barang . $rownum++ 
                    . "   ". $ctk_nama_barang
                    . "  ". $ctk_satuan
                    . "  ". $ctk_berat 
                    . "  ". $ctk_qty
                    . "  ". $ctk_harga 
                    . "  ". $ctk_harga_unit 
                    . "  ". $ctk_jumlah 
                    ." \n";
            // $rownum++;
        }

        $row_barang_max = 11;
        for($i=0; $i<=($row_barang_max-count($data_barang)); $i++){
            $Data .= " \n";
        }

        $note_space = "                                                                                     ";
        $note_space_2 = "                                                                                     ";
        $note_line_1 = substr($data->note,0,85) . substr($note_space,0, 85-strlen(substr($data->note,0,85)) );

        $note_line_2 =  substr($data->note,85,85) . substr($note_space,0, 85-strlen(substr($data->note,85,85)) );
        $note_length = "   " . $nama_barang . $satuan . $berat . $qty . $harga ;

        // JUMLAH
        $ctk_total_jumlah = number_format($data->subtotal,2,',','.');
        $ctk_total_jumlah= substr($jumlah,0,strlen($jumlah)-strlen($ctk_total_jumlah)) .$ctk_total_jumlah ;
        $Data .=  $note_length .$harga_unit . "            " . $ctk_total_jumlah ." \n";

        // DISC
        $ctk_disc = number_format($data->disc,2,',','.');
        $ctk_disc= substr($jumlah,0,strlen($jumlah)-strlen($ctk_disc)) .$ctk_disc ;
        $Data .= $note_line_1 . substr($note_length,0, $note_length-strlen($note_line_1) ) .  $harga_unit . "       " . $ctk_disc ." \n";

        // GRAND TOTAL
        $ctk_grand_total = number_format($data->total,2,',','.');
        $ctk_grand_total= substr($jumlah,0,strlen($jumlah)-strlen($ctk_grand_total)) .$ctk_grand_total ;
        $Data .= $note_line_2 . substr($note_length,0, $note_length-strlen($note_line_2) ) . $harga_unit . "       ". $ctk_grand_total ." \n";
        
        $Data .= " \n";
        $Data .= " \n";
        $Data .= " \n";
        $Data .= " \n";
        $Data .= " \n";

        // TERTANDA CUSTOMER/PELANGGAN
        $nama_customer = $data->nama_customer;
        $col_customer = "  " . $nama_barang;
        $ttd_customer_outer_space = substr($col_customer,0,(strlen($col_customer) - strlen($nama_customer))/2);
        $ttd_customer = $ttd_customer_outer_space . $nama_customer . $ttd_customer_outer_space;
        $Data .= $ttd_customer;

        // TERTANDA ADMIN
        $user = \DB::table('users')->find($data->user_id);
        $nama_admin = $user->name;
        $col_admin = "  " . $satuan.$berat;
        $ttd_admin_outer_space = substr($col_admin,0,(strlen($col_admin) - strlen($nama_customer))/2);
        $ttd_admin = $ttd_admin_outer_space . $nama_admin . $ttd_admin_outer_space;
        $Data .= "  " . $ttd_admin;

        $invoice_bottom_spacer = Appsetting('invoice_bottom_spacer');

        for($i=0;$i<$invoice_bottom_spacer;$i++){
            $Data .= " \n";            
        }
        

        // echo $Data;

        fwrite($handle, $Data);
        fclose($handle);
        copy($file, Appsetting('printer_address'));  # Lakukan cetak
        unlink($file);
    }


// ==================================================================================
// end of code
}
