<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CustomerInvoiceController extends Controller {


    // fungsi tampilkan halaman purchase order
    public function index() {
        $data = \DB::table('view_customer_invoice')->orderBy('invoice_date','desc')->get();
        return view('invoices.ci.invoice', [
            'data' => $data,
        ]);
    }

    public function show($id){
        $data = \DB::table('view_customer_invoice')->find($id);
        $barang = \DB::table('view_sales_order_products')
                        ->where('jual_id',$data->jual_id)
                        ->whereRaw('view_sales_order_products.barang_id in (select barang_id from customer_invoice_detail where customer_invoice_id = ' . $id . ')')
                        // ->select('nama_full as nama','qty','harga_salesman','subtotal')
                        ->get();
        $payments = \DB::table('customer_invoice_payment')
                            ->where('customer_invoice_id',$id)
                            ->select('customer_invoice_payment.*',
                                    \DB::raw("date_format(`payment_date`,'%d-%m-%Y') as payment_date_formatted")
                                )
                            ->get();
        // echo json_encode($data);
        return view('invoices.ci.show-invoice',[
                'data' => $data,
                'barang' => $barang,
                'payments' => $payments
            ]);
    }

    public function registerPayment($invoice_id){
        $cust_invoice = \DB::table('view_customer_invoice')->find($invoice_id);
        
        return view('invoices.ci.reg-payment',[
                'data' => $cust_invoice
            ]);
    }


}
