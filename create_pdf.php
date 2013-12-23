<?php
    $data = json_decode($_POST['data'], true);

	// Include the main TCPDF library (search for installation path).
	require_once('tcpdf/tcpdf.php');

	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Peter Jeff');
	$pdf->SetTitle('Demo PDF Gen');
	$pdf->SetSubject('Flindle.com');
	$pdf->SetKeywords('PDjefF');

	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Page Heading', 'Header Substring');

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// ---------------------------------------------------------
	$pdf->AddPage();

	$pdf->SetFont('helvetica', '', 10);
	$pdf->setCellPaddings(1, 1, 1, 1);
	$pdf->setCellMargins(1, 1, 1, 1);
	$pdf->SetFillColor(125, 255, 125);

	// writeHTMLCell ( $w, $h, $x, $y, $html = '', $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '', $autopadding = true )
	//============================================================+
	// CONVERT: $data->PHP->PDF
	//============================================================+
	foreach($data as $d)
	{
		$pdf->writeHTMLCell ( $d['width'], $d['height'], $d['left'], $d['top'], '.', $border = 'LTRB', 0, true, true, '',true );
	}

	// ---------------------------------------------------------

	//Close and output PDF document
	$test = $pdf->Output('saved/yourpdf.pdf', 'F');



