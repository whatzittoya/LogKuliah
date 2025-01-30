<?php
require __DIR__ . '/vendor/autoload.php';
$a = 1;


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
$range = 'Sheet1!A1:I';

// 5. Read Google spreadsheet data
$responseData = $service->spreadsheets_values->get($spreadsheetId, $range);
$dataArray = $responseData->getValues();
?>

<html>

<head>
    <title>Daftar Mata Kuliah</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>

<body>
    <?php
    // 6. Display data in HTML table
    echo '<table class="table table-striped">';
    foreach ($dataArray as $row) {
        echo '<tr>';
        foreach ($row as $cell) {
            if (strpos($cell, 'http') !== false) {
                echo '<td><a href="' . $cell . '" target="_blank">' . $cell . '</a></td>';
            } else {
                echo '<td>' . $cell . '</td>';
            }
        }
        echo '</tr>';
    }
    echo '</table>';

    ?>
</body>

</html>