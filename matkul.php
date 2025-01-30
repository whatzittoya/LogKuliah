<?php
require __DIR__ . '/vendor/autoload.php';

// 2. Configure Google Sheets API credentials JSON
putenv('GOOGLE_APPLICATION_CREDENTIALS=credentials.json');

// 3. Create the API client instance
$client = new Google_Client();
$client->useApplicationDefaultCredentials();
?>
<?php
$client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);
$service = new Google_Service_Sheets($client);

// 4.Define Google sheet ID and column range to import
$spreadsheetId = '1HrLUvu4LqKf1CSSRMolGniv0iF-PiZngDrivilj0fr0';
$spreadId = '1Ym_v7b1Pycqd3btHQd0TyxwLUoURGEW46pGvDV6FQII';
$range = 'Sheet1!A1:I';

// 5. Read Google spreadsheet data
$responseData = $service->spreadsheets_values->get($spreadId, $range);
$dataArray = $responseData->getValues();
?>

<html>

<head>
    <title>Daftar Mata Kuliah</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>

<body>
    <p><a href="barcode.php?all=1" class="btn btn-primary">All barcode</a></p>
    <?php

    echo '<table class="table table-striped">';
    $i = 0;
    foreach ($dataArray as $row) {
        if ($i == 0) {
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<th>' . $cell . '</th>';
            }
            echo '<th>Aksi</th>';
            echo '</tr>';
        } else {
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<td>' . $cell . '</td>';
            }


            echo '<td><a href="barcode.php?kode=' . $row[1] . '&nama=' . $row[2] . '&ruangan=' . $row[5] . '&waktu=' . $row[6] . '&tim=' . $row[7] . '&jumlah=' . $row[8] . '" class="btn btn-primary">Barcode</a> ';
            echo '</tr>';
        }
        $i++;
    }
    echo '</table>';
    ?>

</body>

</html>