<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\MyPdf;

class SalesReportController extends Controller {

    public function index() {

        $customer = \DB::table('customer')->get();
        $select_customer = [];
        foreach($customer as $dt){
          $select_customer [$dt->id] = $dt->nama;
        }

        $salesperson = \DB::table('salesman')->get();
        $select_salesperson = [];
        foreach($salesperson as $dt){
          $select_salesperson [$dt->id] = $dt->nama;
        }

        return view('sales.reports.index', [
          'select_customer' => $select_customer,
          'select_salesperson' => $select_salesperson,
        ]);
    }

    public function showReport(Request $req){
      
      if($req->ck_by_salesperson && $req->ck_by_customer){
        return $this->reportByAll($req);//echo 'report by salespoerson & customer';
      }else if($req->ck_by_salesperson){
        return $this->reportBySalesperson($req);
      }else if($req->ck_by_customer){
        return $this->reportByCustomer($req);
      }else{
        return $this->reportByDate($req);
      }


    }

    public function reportByAll(Request $req){
      //generate tanggal
      $start_date = $req->start_date;
      $arr_tgl = explode('-',$start_date);
      $start_date = new \DateTime();
      $start_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $start_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $end_date = $req->end_date;
      $arr_tgl = explode('-',$end_date);
      $end_date = new \DateTime();
      $end_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $end_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $data_salesperson = \DB::table('salesman')->find($req->salesperson);
      $data_customer = \DB::table('customer')->find($req->customer);

      $data = \DB::table('view_sales')
                  ->where('salesman_id',$req->salesperson)
                  ->where('customer_id',$req->customer)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->get();

       $subtotal = \DB::table('view_sales')
                  ->where('salesman_id',$req->salesperson)
                  ->where('customer_id',$req->customer)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('subtotal');

      $disc = \DB::table('view_sales')
                  ->where('salesman_id',$req->salesperson)
                  ->where('customer_id',$req->customer)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('disc');
      
      $total = \DB::table('view_sales')
                  ->where('salesman_id',$req->salesperson)
                  ->where('customer_id',$req->customer)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('total');

      return view('sales.reports.report-by-all',[
        'data' => $data,
        'data_customer' => $data_customer,
        'data_salesperson' => $data_salesperson,
        'subtotal' => $subtotal,
        'total_disc' => $disc,
        'total' => $total,
      ])->with($req->all());

    }

     public function pdfByAll(Request $req){
      //generate tanggal
      $start_date = $req->start_date;
      $arr_tgl = explode('-',$start_date);
      $start_date = new \DateTime();
      $start_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $start_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $end_date = $req->end_date;
      $arr_tgl = explode('-',$end_date);
      $end_date = new \DateTime();
      $end_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $end_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $data_salesperson = \DB::table('salesman')->find($req->salesperson_id);
      $data_customer = \DB::table('customer')->find($req->customer_id);

      $data = \DB::table('view_sales')
                  ->where('salesman_id',$req->salesperson_id)
                  ->where('customer_id',$req->customer_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->get();

       $subtotal = \DB::table('view_sales')
                  ->where('salesman_id',$req->salesperson_id)
                  ->where('customer_id',$req->customer_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('subtotal');

      $disc = \DB::table('view_sales')
                  ->where('salesman_id',$req->salesperson_id)
                  ->where('customer_id',$req->customer_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('disc');
      
      $total = \DB::table('view_sales')
                  ->where('salesman_id',$req->salesperson_id)
                  ->where('customer_id',$req->customer_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('total');

      $pdf = new MyPdf('P','mm','A4');
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(true,15);


      $startdate = str_replace('-', '/', $req->start_date);
      $enddate = str_replace('-', '/', $req->end_date);
      GeneratePdfHeader($pdf,'Sales Report',$startdate.' - '.$enddate);

      // HEADER INFO
      $pdf->Ln(5);
      $pdf->SetFont('Arial','B',8);
      $pdf->SetTextColor(0,0,0);
      $pdf->SetX(8);
      $pdf->Cell(20,5,'Order Date ',0,0,'L',false);
      $pdf->Cell(2,5,':',0,0,'L',false);

      $pdf->SetX(22+8);
      $pdf->SetFont('Arial',null,8);
      $order_date_str = $startdate.' - '.$enddate;
      $pdf->Cell($pdf->GetStringWidth($order_date_str),5,$order_date_str,0,0,'L',false);

      $pdf->SetX($pdf->GetX() + 60);
      $pdf->SetFont('Arial','B',8);
      $pdf->SetTextColor(0,0,0);
      $pdf->Cell(20,5,'Customer',0,0,'L',false);
      $pdf->Cell(2,5,':',0,0,'L',false);

      $pdf->SetX($pdf->GetX());
      $pdf->SetFont('Arial',null,8);
      $pdf->Cell(0,5,$data_customer->nama,0,2,'L',false);     


      $pdf->SetFont('Arial','B',8);
      $pdf->SetTextColor(0,0,0);
      $pdf->SetX(8);
      $pdf->Cell(20,5,'Salesperson',0,0,'L',false);
      $pdf->Cell(2,5,':',0,0,'L',false);

      $pdf->SetX(22+8);
      $pdf->SetFont('Arial',null,8);
      $pdf->Cell(0,5,$data_salesperson->nama,0,0,'L',false);

      $pdf->Ln(5);

      // TABLE HEADER
      $separator_width = 1;
      $table_col_width = $pdf->GetPageWidth()-16;
      $table_col_header_width = $table_col_width-($separator_width * 5);
      $col_no = $table_col_header_width * 5/100;
      $col_ref = $table_col_header_width * 20/100;
      $col_order_date = $table_col_header_width * 15/100;
      // $col_salesperson = $table_col_header_width * 15/100;
      $col_subtotal = $table_col_header_width * 20/100;
      $col_disc = $table_col_header_width * 20/100;
      $col_payment_amount = $table_col_header_width * 20/100;

      $pdf->Ln(5);
      $pdf->SetX(8);
      $pdf->SetFont('Arial','B',8);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFillColor(0,0,0);
      $pdf->Cell($col_no,8,'NO',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->SetFillColor(0,128,128);
      $pdf->Cell($col_ref,8,'REF#',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      
      $pdf->SetFillColor(0,0,0);
      
      $pdf->Cell($col_order_date,8,'ORDER DATE',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);

      $pdf->Cell($col_subtotal,8,'SUBTOTAL',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);

      $pdf->Cell($col_disc,8,'DISC',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);

      $pdf->Cell($col_payment_amount,8,'TOTAL',0,2,'C',true);
      
      // GARIS BAWAH HEADER
      $pdf->SetFillColor(0,128,128);
      $pdf->Ln(1);
      $pdf->SetXY(8+$col_no+($separator_width),$pdf->GetY()-1);
      $pdf->Cell($col_ref,1,null,0,2,'C',true);
      $pdf->Ln(1);
      $pdf->SetXY(8,$pdf->GetY()-1);
      $pdf->Cell($table_col_width,1,null,0,2,'C',true);

      // TABLE CONTENT
      $pdf->Ln(1);
      $pdf->SetFont('Arial',null,7);
      $pdf->SetTextColor(0,0,0);
      

      $rownum=1;
      $payment_amount_total = 0;
      // for($i=0;$i<50;$i++){

        foreach($data as $dt){
          $pdf->SetX(8);
          $pdf->Cell($col_no,6,$rownum++,0,0,'C',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_ref,6,$dt->so_no,0,0,'C',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);
         
          $pdf->Cell($col_order_date,6,$dt->tgl_formatted,0,0,'C',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_subtotal,6,number_format($dt->subtotal,2,',','.'),0,0,'R',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_disc,6,number_format($dt->disc,2,',','.'),0,0,'R',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_payment_amount,6,number_format($dt->total,2,',','.'),0,0,'R',false);
          $pdf->Cell($separator_width,8,null,0,2,'C',false);

          // $pdf->Ln(1);

          $payment_amount_total += $dt->total;
        }
      // endd loop data sales
      // }


      // LAST ROW HORIZONTAL LINE
      $pdf->SetX(8);
      $pdf->Cell($table_col_width,0.5,null,0,2,'C',true);
      $pdf->Ln(1);
      // TOTAL
      $pdf->SetFillColor(0,0,0);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',12);
      $pdf->SetX(8+$table_col_width-($col_payment_amount + $col_disc + $col_subtotal + (2*$separator_width)));
      $pdf->Cell($col_disc,8,'TOTAL',0,0,'C', true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->SetFillColor(0,128,128);
      $pdf->Cell($col_disc + $col_payment_amount + $separator_width,8,number_format($payment_amount_total,2,',','.'),0,2,'R', true);

      $pdf->Output('I','SalesOrderAll:'.$startdate.'-'.$enddate.'.pdf');
      exit;
    }


    public function reportByDate(Request $req){
      //generate tanggal
      $start_date = $req->start_date;
      $arr_tgl = explode('-',$start_date);
      $start_date = new \DateTime();
      $start_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $start_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $end_date = $req->end_date;
      $arr_tgl = explode('-',$end_date);
      $end_date = new \DateTime();
      $end_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $end_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $data = \DB::table('view_sales')
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->get();
      $total = \DB::table('view_sales')
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('subtotal');

      $disc = \DB::table('view_sales')
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('disc');
      
      $payment_amount = \DB::table('view_sales')
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('total');

      $amount_due = \DB::table('view_sales')
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('total');

      return view('sales.reports.report-by-date',[
        'data' => $data,
        'total' => $total,
        'total_disc' => $disc,
        'total_payment_amount' => $payment_amount,
        'total_amount_due' => $amount_due,
      ])->with($req->all());

    }

    public function pdfByDate(Request $req){
      //generate tanggal
      $start_date = $req->start_date;
      $arr_tgl = explode('-',$start_date);
      $start_date = new \DateTime();
      $start_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $start_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $end_date = $req->end_date;
      $arr_tgl = explode('-',$end_date);
      $end_date = new \DateTime();
      $end_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $end_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $data = \DB::table('view_sales')
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->get();

      $pdf = new MyPdf('L','mm','A4');
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(true,15);


      $startdate = str_replace('-', '/', $req->start_date);
      $enddate = str_replace('-', '/', $req->end_date);
      GeneratePdfHeader($pdf,'Sales Report',$startdate.' - '.$enddate);

      // HEADER INFO
      $pdf->Ln(5);
      $pdf->SetFont('Arial','B',8);
      $pdf->SetTextColor(0,0,0);
      $pdf->SetX(8);
      $pdf->Cell(0,5,'Order Date : ',0,0,'L',false);

      $pdf->SetX(8+$pdf->GetStringWidth('Order Date : '));
      $pdf->SetFont('Arial',null,8);
      $pdf->Cell(0,5,$startdate.' - '.$enddate,0,0,'L',false);

      $pdf->Ln(5);

      // TABLE HEADER
      $separator_width = 1;
      $table_col_width = $pdf->GetPageWidth()-16;
      $table_col_header_width = $table_col_width-($separator_width * 6);
      $col_no = $table_col_header_width * 5/100;
      $col_ref = $table_col_header_width * 13/100;
      $col_customer = $table_col_header_width * 30/100;
      $col_order_date = $table_col_header_width * 10/100;
      $col_subtotal = $table_col_header_width * 14/100;
      $col_disc = $table_col_header_width * 14/100;
      $col_payment_amount = $table_col_header_width * 14/100;

      $pdf->Ln(5);
      $pdf->SetX(8);
      $pdf->SetFont('Arial','B',8);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFillColor(0,0,0);
      $pdf->Cell($col_no,8,'NO',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_ref,8,'REF#',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->SetFillColor(0,128,128);
      $pdf->Cell($col_customer,8,'CUSTOMER',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->SetFillColor(0,0,0);
      $pdf->Cell($col_order_date,8,'ORDER DATE',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_subtotal,8,'SUBTOTAL',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_disc,8,'DISC',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_payment_amount,8,'TOTAL',0,2,'C',true);
      // GARIS BAWAH HEADER
      $pdf->SetFillColor(0,128,128);
      $pdf->Ln(1);
      $pdf->SetXY(8+$col_no+$col_ref+(2*$separator_width),$pdf->GetY()-1);
      $pdf->Cell($col_customer,1,null,0,2,'C',true);
      $pdf->Ln(1);
      $pdf->SetXY(8,$pdf->GetY()-1);
      $pdf->Cell($table_col_width,1,null,0,2,'C',true);

      // TABLE CONTENT
      $pdf->Ln(1);
      $pdf->SetFont('Arial',null,7);
      $pdf->SetTextColor(0,0,0);
      

      $rownum=1;
      $payment_amount_total = 0;
      // for($i=0;$i<50;$i++){

        foreach($data as $dt){
        $pdf->SetX(8);
        $pdf->Cell($col_no,6,$rownum++,0,0,'C',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_ref,6,$dt->so_no,0,0,'C',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_customer,6,$dt->nama_customer,0,0,'L',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_order_date,6,$dt->tgl_formatted,0,0,'C',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_subtotal,6,number_format($dt->subtotal,2,',','.'),0,0,'R',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_disc,6,number_format($dt->disc,2,',','.'),0,0,'R',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_payment_amount,6,number_format($dt->total,2,',','.'),0,0,'R',false);
        $pdf->Cell($separator_width,8,null,0,2,'C',false);

        // $pdf->Ln(1);

        $payment_amount_total += $dt->total;
      }
      // endd loop data sales
      // }


      // LAST ROW HORIZONTAL LINE
      $pdf->SetX(8);
      $pdf->Cell($table_col_width,0.5,null,0,2,'C',true);
      $pdf->Ln(1);
      // TOTAL
      $pdf->SetFillColor(0,0,0);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',12);
      $pdf->SetX(8+$table_col_width-($col_payment_amount + $col_disc + $col_subtotal + (2*$separator_width)));
      $pdf->Cell($col_disc,8,'TOTAL',0,0,'C', true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->SetFillColor(0,128,128);
      $pdf->Cell($col_disc + $col_payment_amount + $separator_width,8,number_format($payment_amount_total,2,',','.'),0,2,'R', true);

      $pdf->Output('I','SalesOrder:'.$startdate.'-'.$enddate.'.pdf');
      exit;
    }

    public function reportByCustomer(Request $req){
      //generate tanggal
      $start_date = $req->start_date;
      $arr_tgl = explode('-',$start_date);
      $start_date = new \DateTime();
      $start_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $start_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $end_date = $req->end_date;
      $arr_tgl = explode('-',$end_date);
      $end_date = new \DateTime();
      $end_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $end_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $customer_id = $req->customer;
      $customer = \DB::table('customer')->find($customer_id);

      $data = \DB::table('view_sales')
                  ->where('customer_id',$customer_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->get();
      $subtotal = \DB::table('view_sales')
                  ->where('customer_id',$customer_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('subtotal');

      $disc = \DB::table('view_sales')
                  ->where('customer_id',$customer_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('disc');
      
      $total = \DB::table('view_sales')
                  ->where('customer_id',$customer_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('total');

      return view('sales.reports.report-by-customer',[
        'data' => $data,
        'data_customer' => $customer,
        'subtotal' => $subtotal,
        'total_disc' => $disc,
        'total' => $total,
        // 'total_amount_due' => $amount_due,
      ])->with($req->all());

    }

    public function pdfByCustomer(Request $req){
      //generate tanggal
      $start_date = $req->start_date;
      $arr_tgl = explode('-',$start_date);
      $start_date = new \DateTime();
      $start_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $start_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $end_date = $req->end_date;
      $arr_tgl = explode('-',$end_date);
      $end_date = new \DateTime();
      $end_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $end_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $customer_id = $req->customer_id;
      $customer = \DB::table('customer')->find($customer_id);

      $data = \DB::table('view_sales')
                  ->where('customer_id',$customer_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->get();

      $pdf = new MyPdf('P','mm','A4');
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(true,15);


      $startdate = str_replace('-', '/', $req->start_date);
      $enddate = str_replace('-', '/', $req->end_date);
      GeneratePdfHeader($pdf,'Sales Report',$startdate.' - '.$enddate);

      // HEADER INFO
      $pdf->Ln(5);
      $pdf->SetFont('Arial','B',8);
      $pdf->SetTextColor(0,0,0);
      $pdf->SetX(8);
      $pdf->Cell(20,5,'Order Date ',0,0,'L',false);
      $pdf->Cell(2,5,':',0,0,'L',false);

      $pdf->SetX(22+8);
      $pdf->SetFont('Arial',null,8);
      $pdf->Cell(0,5,$startdate.' - '.$enddate,0,2,'L',false);

      $pdf->SetFont('Arial','B',8);
      $pdf->SetTextColor(0,0,0);
      $pdf->SetX(8);
      $pdf->Cell(20,5,'Customer',0,0,'L',false);
      $pdf->Cell(2,5,':',0,0,'L',false);

      $pdf->SetX(22+8);
      $pdf->SetFont('Arial',null,8);
      $pdf->Cell(0,5,$customer->nama,0,0,'L',false);

      $pdf->Ln(5);

      // TABLE HEADER
      $separator_width = 1;
      $table_col_width = $pdf->GetPageWidth()-16;
      $table_col_header_width = $table_col_width-($separator_width * 6);
      $col_no = $table_col_header_width * 5/100;
      $col_ref = $table_col_header_width * 12/100;
      $col_order_date = $table_col_header_width * 12/100;
      $col_salesperson = $table_col_header_width * 26/100;
      $col_subtotal = $table_col_header_width * 15/100;
      $col_disc = $table_col_header_width * 15/100;
      $col_payment_amount = $table_col_header_width * 15/100;

      $pdf->Ln(5);
      $pdf->SetX(8);
      $pdf->SetFont('Arial','B',8);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFillColor(0,0,0);
      $pdf->Cell($col_no,8,'NO',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->SetFillColor(0,128,128);
      $pdf->Cell($col_ref,8,'REF#',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      
      $pdf->SetFillColor(0,0,0);
      
      $pdf->Cell($col_order_date,8,'ORDER DATE',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);

      $pdf->Cell($col_salesperson,8,'SALESPERSON',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);

      $pdf->Cell($col_subtotal,8,'SUBTOTAL',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);

      $pdf->Cell($col_disc,8,'DISC',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);

      $pdf->Cell($col_payment_amount,8,'TOTAL',0,2,'C',true);
      
      // GARIS BAWAH HEADER
      $pdf->SetFillColor(0,128,128);
      $pdf->Ln(1);
      $pdf->SetXY(8+$col_no+($separator_width),$pdf->GetY()-1);
      $pdf->Cell($col_ref,1,null,0,2,'C',true);
      $pdf->Ln(1);
      $pdf->SetXY(8,$pdf->GetY()-1);
      $pdf->Cell($table_col_width,1,null,0,2,'C',true);

      // TABLE CONTENT
      $pdf->Ln(1);
      $pdf->SetFont('Arial',null,7);
      $pdf->SetTextColor(0,0,0);
      

      $rownum=1;
      $payment_amount_total = 0;
      // for($i=0;$i<50;$i++){

        foreach($data as $dt){
          $pdf->SetX(8);
          $pdf->Cell($col_no,6,$rownum++,0,0,'C',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_ref,6,$dt->so_no,0,0,'C',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);
         
          $pdf->Cell($col_order_date,6,$dt->tgl_formatted,0,0,'C',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_salesperson,6,$dt->salesman,0,0,'L',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_subtotal,6,number_format($dt->subtotal,2,',','.'),0,0,'R',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_disc,6,number_format($dt->disc,2,',','.'),0,0,'R',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_payment_amount,6,number_format($dt->total,2,',','.'),0,0,'R',false);
          $pdf->Cell($separator_width,8,null,0,2,'C',false);

          // $pdf->Ln(1);

          $payment_amount_total += $dt->total;
        }
      // endd loop data sales
      // }


      // LAST ROW HORIZONTAL LINE
      $pdf->SetX(8);
      $pdf->Cell($table_col_width,0.5,null,0,2,'C',true);
      $pdf->Ln(1);
      // TOTAL
      $pdf->SetFillColor(0,0,0);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',12);
      $pdf->SetX(8+$table_col_width-($col_payment_amount + $col_disc + $col_subtotal + (2*$separator_width)));
      $pdf->Cell($col_disc,8,'TOTAL',0,0,'C', true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->SetFillColor(0,128,128);
      $pdf->Cell($col_disc + $col_payment_amount + $separator_width,8,number_format($payment_amount_total,2,',','.'),0,2,'R', true);

      $pdf->Output('I','SalesOrder:'.$customer->nama.':'.$startdate.'-'.$enddate.'.pdf');
      exit;
    }

    // REPORT BY SALESPERSON
    public function reportBySalesperson(Request $req){
      //generate tanggal
      $start_date = $req->start_date;
      $arr_tgl = explode('-',$start_date);
      $start_date = new \DateTime();
      $start_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $start_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $end_date = $req->end_date;
      $arr_tgl = explode('-',$end_date);
      $end_date = new \DateTime();
      $end_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $end_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $salesperson_id = $req->salesperson;
      $salesperson = \DB::table('salesman')->find($salesperson_id);

      $data = \DB::table('view_sales')
                  ->where('salesman_id',$salesperson_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->get();
      $subtotal = \DB::table('view_sales')
                  ->where('salesman_id',$salesperson_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('subtotal');

      $disc = \DB::table('view_sales')
                  ->where('salesman_id',$salesperson_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('disc');
      
      $total = \DB::table('view_sales')
                  ->where('salesman_id',$salesperson_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('total');

      return view('sales.reports.report-by-salesperson',[
        'data' => $data,
        'data_salesperson' => $salesperson,
        'subtotal' => $subtotal,
        'total_disc' => $disc,
        'total' => $total,
        // 'total_amount_due' => $amount_due,
      ])->with($req->all());

    }

    public function pdfBySalesperson(Request $req){
      //generate tanggal
      $start_date = $req->start_date;
      $arr_tgl = explode('-',$start_date);
      $start_date = new \DateTime();
      $start_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $start_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $end_date = $req->end_date;
      $arr_tgl = explode('-',$end_date);
      $end_date = new \DateTime();
      $end_date->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $end_date_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $salesperson_id = $req->salesperson_id;
      $salesperson = \DB::table('salesman')->find($salesperson_id);

      $data = \DB::table('view_sales')
                  ->where('salesman_id',$salesperson_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->get();

      $pdf = new MyPdf('P','mm','A4');
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(true,15);


      $startdate = str_replace('-', '/', $req->start_date);
      $enddate = str_replace('-', '/', $req->end_date);
      GeneratePdfHeader($pdf,'Sales Report',$startdate.' - '.$enddate);

      // HEADER INFO
      $pdf->Ln(5);
      $pdf->SetFont('Arial','B',8);
      $pdf->SetTextColor(0,0,0);
      $pdf->SetX(8);
      $pdf->Cell(20,5,'Order Date ',0,0,'L',false);
      $pdf->Cell(2,5,':',0,0,'L',false);

      $pdf->SetX(22+8);
      $pdf->SetFont('Arial',null,8);
      $pdf->Cell(0,5,$startdate.' - '.$enddate,0,2,'L',false);

      $pdf->SetFont('Arial','B',8);
      $pdf->SetTextColor(0,0,0);
      $pdf->SetX(8);
      $pdf->Cell(20,5,'Salesperson',0,0,'L',false);
      $pdf->Cell(2,5,':',0,0,'L',false);

      $pdf->SetX(22+8);
      $pdf->SetFont('Arial',null,8);
      $pdf->Cell(0,5,$salesperson->nama,0,0,'L',false);

      $pdf->Ln(5);

      // TABLE HEADER
      $separator_width = 1;
      $table_col_width = $pdf->GetPageWidth()-16;
      $table_col_header_width = $table_col_width-($separator_width * 6);
      $col_no = $table_col_header_width * 5/100;
      $col_ref = $table_col_header_width * 12/100;
      $col_order_date = $table_col_header_width * 12/100;
      $col_customer = $table_col_header_width * 26/100;
      $col_subtotal = $table_col_header_width * 15/100;
      $col_disc = $table_col_header_width * 15/100;
      $col_payment_amount = $table_col_header_width * 15/100;

      $pdf->Ln(5);
      $pdf->SetX(8);
      $pdf->SetFont('Arial','B',8);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFillColor(0,0,0);
      $pdf->Cell($col_no,8,'NO',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->SetFillColor(0,128,128);
      $pdf->Cell($col_ref,8,'REF#',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      
      $pdf->SetFillColor(0,0,0);
      
      $pdf->Cell($col_order_date,8,'ORDER DATE',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);

      $pdf->Cell($col_customer,8,'CUSTOMER',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);

      $pdf->Cell($col_subtotal,8,'SUBTOTAL',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);

      $pdf->Cell($col_disc,8,'DISC',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);

      $pdf->Cell($col_payment_amount,8,'TOTAL',0,2,'C',true);
      
      // GARIS BAWAH HEADER
      $pdf->SetFillColor(0,128,128);
      $pdf->Ln(1);
      $pdf->SetXY(8+$col_no+($separator_width),$pdf->GetY()-1);
      $pdf->Cell($col_ref,1,null,0,2,'C',true);
      $pdf->Ln(1);
      $pdf->SetXY(8,$pdf->GetY()-1);
      $pdf->Cell($table_col_width,1,null,0,2,'C',true);

      // TABLE CONTENT
      $pdf->Ln(1);
      $pdf->SetFont('Arial',null,7);
      $pdf->SetTextColor(0,0,0);
      

      $rownum=1;
      $payment_amount_total = 0;
      // for($i=0;$i<50;$i++){

        foreach($data as $dt){
          $pdf->SetX(8);
          $pdf->Cell($col_no,6,$rownum++,0,0,'C',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_ref,6,$dt->so_no,0,0,'C',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);
         
          $pdf->Cell($col_order_date,6,$dt->tgl_formatted,0,0,'C',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_customer,6,$dt->nama_customer,0,0,'L',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_subtotal,6,number_format($dt->subtotal,2,',','.'),0,0,'R',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_disc,6,number_format($dt->disc,2,',','.'),0,0,'R',false);
          $pdf->Cell($separator_width,8,null,0,0,'C',false);

          $pdf->Cell($col_payment_amount,6,number_format($dt->total,2,',','.'),0,0,'R',false);
          $pdf->Cell($separator_width,8,null,0,2,'C',false);

          // $pdf->Ln(1);

          $payment_amount_total += $dt->total;
        }
      // endd loop data sales
      // }


      // LAST ROW HORIZONTAL LINE
      $pdf->SetX(8);
      $pdf->Cell($table_col_width,0.5,null,0,2,'C',true);
      $pdf->Ln(1);
      // TOTAL
      $pdf->SetFillColor(0,0,0);
      $pdf->SetTextColor(255,255,255);
      $pdf->SetFont('Arial','B',12);
      $pdf->SetX(8+$table_col_width-($col_payment_amount + $col_disc + $col_subtotal + (2*$separator_width)));
      $pdf->Cell($col_disc,8,'TOTAL',0,0,'C', true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->SetFillColor(0,128,128);
      $pdf->Cell($col_disc + $col_payment_amount + $separator_width,8,number_format($payment_amount_total,2,',','.'),0,2,'R', true);

      $pdf->Output('I','SalesOrder:'.$salesperson->nama.':'.$startdate.'-'.$enddate.'.pdf');
      exit;
    }

  // END OF CODE
}
