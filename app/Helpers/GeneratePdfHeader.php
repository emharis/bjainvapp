<?php

if (!function_exists('GeneratePdfHeader')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function GeneratePdfHeader(&$pdf, $header_titel, $header_subtitle)
    {
    	// $logo_cetak = \DB->table('appsetting')->whereName('logo_cetak')->first()->value;
    	$company_name = strtoupper(Appsetting('company_name'));
    	$alamat_1 = Appsetting('alamat_1');
    	$alamat_2 = Appsetting('alamat_2');
    	$telp = Appsetting('telp');
    	$email = Appsetting('email');

    	// image/logo
    	// $pdf->Image('img/' .     $logo_cetak,8,8,40);
    	// company name
    	$pdf->SetXY(8,8);
    	$pdf->SetTextColor(0,0,0);
    	$pdf->SetFont('Arial','B',12);
    	$pdf->Cell(0,4,$company_name,0,2,'L',false);
    	$pdf->SetFont('Arial',null,8);
    	$pdf->Cell(0,4,$alamat_1,0,2,'L',false);
    	$pdf->Cell(0,4,$alamat_2,0,2,'L',false);
    	$pdf->Cell(0,4,'T. ' . $telp . ' | ' . 'E. ' . $email ,0,2,'L',false);
        // $pdf->Ln(3);
        
        // Line di bawah header
        $pdf->SetDrawColor(0,128,128);
        $pdf->SetX(8);
        $pdf->SetLineWidth(0.4);
        $pdf->Cell($pdf->GetPageWidth()-16,2,null,'B',0,'L',false);
        // $pdf->Cell(0,1,'--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------',0,0,'L',false);
        $last_y = $pdf->GetY();

        // TEXT header titel
        $pdf->SetXY(8,12);
        $pdf->SetFont('Arial',null,18);
        $pdf->SetTextColor(0,128,128);
        $pdf->Cell($pdf->GetPageWidth()-16,8,$header_titel,0,0,'R',false);
        $pdf->Ln(6);
        $pdf->SetX(8);
        $pdf->SetFont('Arial',null,10);
        $pdf->Cell($pdf->GetPageWidth()-16,8,$header_subtitle,0,0,'R',false);

        $pdf->SetXY(0,$last_y);
        $pdf->SetFont('Arial',null,8);
        $pdf->Ln(1);
    }
}
