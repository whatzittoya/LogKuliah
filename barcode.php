<?php
require __DIR__ . '/vendor/autoload.php';

use chillerlan\QRCode\{QRCode, QROptions};
use Dompdf\Dompdf;


function createQrCode($kode, $nama, $ruangan, $waktu, $tim, $jumlah)
{
    $url = 'https://docs.google.com/forms/d/e/1FAIpQLSf6l79oD8Yf5JTCJkTwFvQA2LZMsqOz__Hc7HXOiBYt4xKMKw/viewform?usp=pp_url&entry.934449838=' . $kode . '&entry.1600400562=' . $nama . '&entry.1845471044=' . $ruangan . '&entry.773385565=' . $waktu . '&entry.1396613136=' . $tim . '&entry.1338077576=' . $jumlah;
    $page = "";
    $page = $page . '<center>';
    $page = $page . '<h4>' . $nama . '</h4>';
    $page = $page . '<h4>Ruangan: ' . $ruangan . '</h4>';
    $page = $page . '<h4>' . $waktu . '</h4>';
    // quick and simple:
    $page = $page . '<img src="' . (new QRCode)->render($url) . '" alt="QR Code"  width="50%"/>';
    $page = $page . '</center>';
    return $page;
};
$html = "<html><body>";

if (isset($_GET['all'])) {
    // 2. Configure Google Sheets API credentials JSON
    putenv('GOOGLE_APPLICATION_CREDENTIALS=credentials.json');

    // 3. Create the API client instance
    $client = new Google_Client();
    $client->useApplicationDefaultCredentials();

    $client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
    $service = new Google_Service_Sheets($client);

    // 4.Define Google sheet ID and column range to import
    $spreadsheetId = '1HrLUvu4LqKf1CSSRMolGniv0iF-PiZngDrivilj0fr0';
    $spreadId = '1Ym_v7b1Pycqd3btHQd0TyxwLUoURGEW46pGvDV6FQII';
    $range = 'Sheet1!A1:I';

    // 5. Read Google spreadsheet data
    $responseData = $service->spreadsheets_values->get($spreadId, $range);
    $dataArray = $responseData->getValues();
    $i = 0;
    foreach ($dataArray as $row) {
        if ($i == 0) {
            $i++;
            continue;
        }
        $kode = $row[1];
        $nama = $row[2];
        $ruangan = $row[5];
        $waktu = $row[6];
        $tim = $row[7];
        $jumlah = $row[8];
        $html = $html . createQrCode($kode, $nama, $ruangan, $waktu, $tim, $jumlah);
    }
} else {
    $kode = $_GET['kode'];
    $nama = $_GET['nama'];
    $ruangan = $_GET['ruangan'];
    $waktu = $_GET['waktu'];
    $tim = $_GET['tim'];
    $jumlah = $_GET['jumlah'];
    $html = $html . createQrCode($kode, $nama, $ruangan, $waktu, $tim, $jumlah);
}
$html = $html . "</body></html>";


// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();
