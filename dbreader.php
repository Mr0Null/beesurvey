<?php

include('config.php');

$db_surveys = mysqli_connect(
    $db_server,
    $db_username,
    $db_password,
    $db_name
);

header('Content-Type: text/html; charset=utf-8');
echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> ';
$table = 'survey_1';
$query = "SELECT * FROM $table";
$res = mysqli_query($db_surveys, $query);
$suc = 0;
$tot = 0;
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
    if ($row['6'] == 'Yes') $suc++;
    echo "<br>";
    $tot++;
}

echo "<br><br><br>the chance of being successful is : " . 100.0 * $suc / $tot . '%';

?>