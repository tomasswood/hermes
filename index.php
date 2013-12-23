<?php
/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */
// Include the main TCPDF library (search for installation path).
			require_once('tcpdf/tcpdf.php');

// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Nicola Asuni');
		$pdf->SetTitle('TCPDF Example 005');
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 005', PDF_HEADER_STRING);

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

// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

// ---------------------------------------------------------
	$pdf->AddPage();

	$pdf->SetFont('helvetica', '', 10);
	$pdf->setCellPaddings(1, 1, 1, 1);
	$pdf->setCellMargins(1, 1, 1, 1);
	$pdf->SetFillColor(255, 255, 127);

	$content = '<b>LEFT COLUMN</b> left column';
		$img = '<a href="#"><img src="http://flindle.com/img/blog.png"></a>';
	$y = $pdf->getY();


	// writeHTMLCell ( $w, $h, $x, $y, $html = '', $border = 0, $ln = 0, $fill = false, $reseth = true, $align = '', $autopadding = true )

	// Multicell test
	$pdf->writeHTMLCell ( 100, 100, 22, 22, $content, $border = 'LTRB', 0, true, true, '',true );
		$pdf->writeHTMLCell ( 100, 100, 33, 33, $content, $border = 'LTRB', 0, false, true, '',true );
		$pdf->writeHTMLCell ( 100, 100, 44, 44, $img, $border = 'LTRB', 0, false, true, '',true );



// ---------------------------------------------------------

//Close and output PDF document
		$test = $pdf->Output('saved/yourpdf.pdf', 'F');


	//============================================================+
	// END OF FILE
	//============================================================+