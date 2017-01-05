<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\MyPdf;

class PurchaseReportController extends Controller {

    public function index() {

        $supplier = \DB::table('supplier')->get();
        $select_supplier = [];
        foreach($supplier as $dt){
          $select_supplier [$dt->id] = $dt->nama;
        }

        return view('purchase.reports.index', [
          'select_supplier' => $select_supplier
        ]);
    }

    public function showReport(Request $req){
      //generate tanggal
      $date_start = $req->date_start;
      $arr_tgl = explode('-',$date_start);
      $date_start = new \DateTime();
      $date_start->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $date_start_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $date_end = $req->date_end;
      $arr_tgl = explode('-',$date_end);
      $date_end = new \DateTime();
      $date_end->setDate($arr_tgl[2],$arr_tgl[1],$arr_tgl[0]);
      $date_end_str = $arr_tgl[2].'-'.$arr_tgl[1].'-'.$arr_tgl[0];

      $data = \DB::table('vier_purchase')
                  ->whereBetween('tgl',[$date_start_str,$date_end_str])
                  ->get();

      return view('purchase.reports.show-report',[
        'date_start' => $req->date_start,
        'date_end' => $req->date_end,
        'data' => $data,
      ]);
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

      $data = \DB::table('view_purchase')
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->get();
      $total = \DB::table('view_purchase')
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('subtotal');

      $disc = \DB::table('view_purchase')
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('disc');
      
      $payment_amount = \DB::table('view_purchase')
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('total');

      $amount_due = \DB::table('view_purchase')
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('total');

      return view('purchase.reports.report-by-date',[
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

      $data = \DB::table('view_purchase')
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->get();

      $pdf = new MyPdf('L','mm','A4');
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(true,15);


      $startdate = str_replace('-', '/', $req->start_date);
      $enddate = str_replace('-', '/', $req->end_date);
      GeneratePdfHeader($pdf,'Purchase Report',$startdate.' - '.$enddate);

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
      $table_col_header_width = $table_col_width-($separator_width * 8);
      $col_no = $table_col_header_width * 5/100;
      $col_ref = $table_col_header_width * 10/100;
      $col_supplier = $table_col_header_width * 19/100;
      $col_supplier_ref = $table_col_header_width * 10/100;
      $col_order_date = $table_col_header_width * 10/100;
      $col_due_date = $table_col_header_width * 10/100;
      $col_subtotal = $table_col_header_width * 12/100;
      $col_disc = $table_col_header_width * 12/100;
      $col_payment_amount = $table_col_header_width * 12/100;

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
      $pdf->Cell($col_supplier,8,'SUPPLIER',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->SetFillColor(0,0,0);
      $pdf->Cell($col_supplier_ref,8,'SUP REF#',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_order_date,8,'ORDER DATE',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_due_date,8,'DUE DATE',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_subtotal,8,'SUBTOTAL',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_disc,8,'DISC',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_payment_amount,8,'PAYMENT AMOUNT',0,2,'C',true);
      $pdf->SetFillColor(0,128,128);
      $pdf->Ln(1);
      $pdf->SetXY(8+$col_no+$col_ref+(2*$separator_width),$pdf->GetY()-1);
      $pdf->Cell($col_supplier,1,null,0,2,'C',true);
      $pdf->Ln(1);
      $pdf->SetXY(8,$pdf->GetY()-1);
      $pdf->Cell($table_col_width,1,null,0,2,'C',true);

      // TABLE CONTENT
      $pdf->Ln(1);
      $pdf->SetFont('Arial',null,8);
      $pdf->SetTextColor(0,0,0);
      

      $rownum=1;
      $payment_amount_total = 0;
      // for($i=0;$i<50;$i++){

        foreach($data as $dt){
        $pdf->SetX(8);
        $pdf->Cell($col_no,6,$rownum++,0,0,'C',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_ref,6,$dt->po_num,0,0,'C',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_supplier,6,$dt->supplier,0,0,'L',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_supplier_ref,6,$dt->no_inv,0,0,'C',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_order_date,6,$dt->tgl_formatted,0,0,'C',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_due_date,6,$dt->jatuh_tempo_formatted,0,0,'C',false);
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
      // endd loop data purchase
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

      $pdf->Output('I','PurchaseOrder:'.$startdate.'-'.$enddate.'.pdf');
      exit;
    }

    public function reportBySupplier(Request $req){
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

      $supplier_id = $req->supplier_id;
      $supplier = \DB::table('supplier')->find($supplier_id);

      $data = \DB::table('view_purchase')
                  ->where('supplier_id',$supplier_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->get();
      $total = \DB::table('view_purchase')
                  ->where('supplier_id',$supplier_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('total');

      $disc = \DB::table('view_purchase')
                  ->where('supplier_id',$supplier_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('disc');
      
      $payment_amount = \DB::table('view_purchase')
                  ->where('supplier_id',$supplier_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->sum('total');

      // $amount_due = \DB::table('view_purchase')
      //             ->where('supplier_id',$supplier_id)
      //             ->whereBetween('tgl',[$start_date_str,$end_date_str])
      //             ->sum('amount_due');

      return view('purchase.reports.report-by-supplier',[
        'data' => $data,
        'supplier' => $supplier,
        'total' => $total,
        'total_disc' => $disc,
        'total_payment_amount' => $payment_amount,
        // 'total_amount_due' => $amount_due,
      ])->with($req->all());

    }

    public function pdfBySupplier(Request $req){
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

      $supplier_id = $req->supplier_id;
      $supplier = \DB::table('supplier')->find($supplier_id);

      $data = \DB::table('view_purchase')
                  ->where('supplier_id',$supplier_id)
                  ->whereBetween('tgl',[$start_date_str,$end_date_str])
                  ->get();

      $supplier = \DB::table('supplier')->find($req->supplier_id);

      $pdf = new MyPdf('L','mm','A4');
      $pdf->AddPage();
      $pdf->SetAutoPageBreak(true,15);


      $startdate = str_replace('-', '/', $req->start_date);
      $enddate = str_replace('-', '/', $req->end_date);
      GeneratePdfHeader($pdf,'Purchase Report',$startdate.' - '.$enddate);

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
      $pdf->Cell(20,5,'Supplier',0,0,'L',false);
      $pdf->Cell(2,5,':',0,0,'L',false);

      $pdf->SetX(22+8);
      $pdf->SetFont('Arial',null,8);
      $pdf->Cell(0,5,$supplier->nama,0,0,'L',false);

      $pdf->Ln(5);

      // TABLE HEADER
      $separator_width = 1;
      $table_col_width = $pdf->GetPageWidth()-16;
      $table_col_header_width = $table_col_width-($separator_width * 8);
      $col_no = $table_col_header_width * 5/100;
      $col_ref = $table_col_header_width * 10/100;
      $col_supplier = $table_col_header_width * 19/100;
      $col_supplier_ref = $table_col_header_width * 10/100;
      $col_order_date = $table_col_header_width * 10/100;
      $col_due_date = $table_col_header_width * 10/100;
      $col_subtotal = $table_col_header_width * 12/100;
      $col_disc = $table_col_header_width * 12/100;
      $col_payment_amount = $table_col_header_width * 12/100;

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
      $pdf->Cell($col_supplier,8,'SUPPLIER',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->SetFillColor(0,0,0);
      $pdf->Cell($col_supplier_ref,8,'SUP REF#',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_order_date,8,'ORDER DATE',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_due_date,8,'DUE DATE',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_subtotal,8,'SUBTOTAL',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_disc,8,'DISC',0,0,'C',true);
      $pdf->Cell($separator_width,8,null,0,0,'C',false);
      $pdf->Cell($col_payment_amount,8,'PAYMENT AMOUNT',0,2,'C',true);
      $pdf->SetFillColor(0,128,128);
      $pdf->Ln(1);
      $pdf->SetXY(8+$col_no+$col_ref+(2*$separator_width),$pdf->GetY()-1);
      $pdf->Cell($col_supplier,1,null,0,2,'C',true);
      $pdf->Ln(1);
      $pdf->SetXY(8,$pdf->GetY()-1);
      $pdf->Cell($table_col_width,1,null,0,2,'C',true);

      // TABLE CONTENT
      $pdf->Ln(1);
      $pdf->SetFont('Arial',null,8);
      $pdf->SetTextColor(0,0,0);
      

      $rownum=1;
      $payment_amount_total = 0;
      // for($i=0;$i<50;$i++){

        foreach($data as $dt){
        $pdf->SetX(8);
        $pdf->Cell($col_no,6,$rownum++,0,0,'C',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_ref,6,$dt->po_num,0,0,'C',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_supplier,6,$dt->supplier,0,0,'L',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_supplier_ref,6,$dt->no_inv,0,0,'C',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_order_date,6,$dt->tgl_formatted,0,0,'C',false);
        $pdf->Cell($separator_width,8,null,0,0,'C',false);

        $pdf->Cell($col_due_date,6,$dt->jatuh_tempo_formatted,0,0,'C',false);
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
      // endd loop data purchase
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

      $pdf->Output('I','PurchaseOrder:'.$supplier->nama.':'.$startdate.'-'.$enddate.'.pdf');
      exit;
    }

  // END OF CODE
}
