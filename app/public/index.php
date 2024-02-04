<?php
setlocale(LC_CTYPE, 'en_US');

require('pdf.php');

$urls = [
    "https://banco.bradesco",
    "https://www.bradescard.com.br",
    "https://financiamentos.bradesco",
    "https://www.2bcapital.com.br",
    "https://www.bradespar.com.br",
    "https://cidadetran.bradesco",
    "https://www.bradescocorretora.com.br",
    "https://www.bradescofornecedores.com.br",
    "https://newsletter.bradesco",
    "https://expresso.bradesco",
    "https://www.bradescoseguranca.com.br",
    "https://brades.co",
    "https://www.bradescobbi.com.br",
    "https://bemdtvm.bradesco",
    "https://bradescocelular.com.br",
    "https://custodia.bradesco",
    "https://www.bradescoesportes.com.br",
    "https://bradescofeiras.com.br",
    "https://www.acordosoperacionais.com.br",
    "https://cultura.bradesco",
    "https://www.bradescoeuropa.eu",
    "https://inovabra.com.br",
    "https://investimentos.bradesco",
    "https://www.unibrad.com.br",
    "https://wspf.banco.bradesco",
    "https://wspj.bradescopessoajuridica.com.br",
    "https://institucional.bradesco.com.br",
];

function checkSupportedTLS($url)
{
    $protocols = [
        'TLS1.0' => ['protocol' => CURL_SSLVERSION_MAX_TLSv1_0, 'sec' => false],
        'TLS1.1' => ['protocol' => CURL_SSLVERSION_MAX_TLSv1_1, 'sec' => false],
        'TLS1.2' => ['protocol' => CURL_SSLVERSION_TLSv1_2, 'sec' => true],
        'TLS1.3' => ['protocol' => CURL_SSLVERSION_TLSv1_3, 'sec' => true],

        // 'TLS1.0' => ['protocol' => CURL_SSLVERSION_MAX_TLSv1_0, 'sec' => false],
        // 'TLS1.1' => ['protocol' => CURL_SSLVERSION_MAX_TLSv1_1, 'sec' => false],
        // 'TLS1.2' => ['protocol' => CURL_SSLVERSION_MAX_TLSv1_2, 'sec' => true],
        // 'TLS1.3' => ['protocol' => CURL_SSLVERSION_MAX_TLSv1_3, 'sec' => true],
    ];

    $results = [];

    foreach ($protocols as $name => $value) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSLVERSION, $value['protocol']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:123.0) Gecko/20100101 Firefox/123.0');

        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/cacert.pem');
        curl_setopt($ch, CURLOPT_CAPATH, __DIR__ . '/cacert.pem');
        //curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/cert_new_g2.pem');
        //curl_setopt($ch, CURLOPT_CAPATH, __DIR__ . '/cert_new_g2.pem');

        $response = curl_exec($ch) !== false;

        array_push($results, ['value' => $name, 'result' => $response]);
    }
    return $results;
}

$data = [];

foreach ($urls as $index => $url) {
    $results = checkSupportedTLS($url);
    array_push($data, [
        'url' => $url,
        'TLS1.0' => ($results[0]["result"] ? 'Warning' : 'No'),
        'TLS1.1' => ($results[1]["result"] ? 'Warning' : 'No'),
        'TLS1.2' => ($results[2]["result"] ? 'Yes' : 'No'),
        'TLS1.3' => ($results[3]["result"] ? 'Yes' : 'No')
    ]);
}

$pdf = new PDF();
// Column headings
$header = array('URL', 'TLS1.0', 'TLS1.1', 'TLS1.2', 'TLS1.3');

$pdf->AddPage();
$pdf->SetFont('Arial', '', 16);
$pdf->Cell(40, 10, 'TLS Report for Bradesco Services');
$pdf->Ln();

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, 'Generated on ' . date("Y/m/d h:i:sa"));
$pdf->Ln();

$pdf->SetFont('Arial', '', 8);
$pdf->BasicTable($header, $data);

//$pdf->AddPage();
//$pdf->ImprovedTable($header, $data);

//$pdf->AddPage();
//$pdf->FancyTable($header, $data);

$pdf->Output('I', 'report.pdf');
