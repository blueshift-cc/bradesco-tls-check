<?php

require('fpdf/fpdf.php');

class PDF extends FPDF
{
    // Simple table
    function BasicTable($header, $data)
    {
        // Header
        foreach ($header as $index => $col) {
            if ($index == 0) {
                $this->Cell(60, 7, $col, 1);
            } else {
                $this->Cell(30, 7, $col, 1);
            }
        }
        $this->Ln();

        // Data
        foreach ($data as $row) {
            foreach ($row as $index => $col) {
                if ($index == "url") {
                    $this->Cell(60, 6, $col, 1);
                } else {
                    if ($col == "Yes") {
                        $this->SetFillColor(0, 255, 0);
                    } else if ($col == "Warning") {
                        $this->SetFillColor(255, 255, 0);
                    } else {
                        $this->SetFillColor(255, 0, 0);
                    }
                    $this->Cell(30, 6, $col, 1, 0, '', true);
                }
            }
            $this->Ln();
        }
    }

    // Better table
    function ImprovedTable($header, $data)
    {
        // Column widths
        $w = array(40, 35, 40, 45);
        // Header
        for ($i = 0; $i < count($header); $i++) $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
        $this->Ln();
        // Data
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR');
            $this->Cell($w[1], 6, $row[1], 'LR');
            $this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R');
            $this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R');
            $this->Ln();
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    // Colored table
    function FancyTable($header, $data)
    {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(40, 35, 40, 45);
        for ($i = 0; $i < count($header); $i++) $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}
