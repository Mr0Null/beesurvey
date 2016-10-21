<?php

// loads config file
include('config.php');


// gets ip address of client for anti-cheat system
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

$data = $_POST['data'];
$surveyID = $_POST['ID'];
$surveyTable = $db_prefix . $surveyID;

$db_surveys = mysqli_connect(
    $db_server,
    $db_username,
    $db_password,
    $db_name
);

function checkIP($ip, $surveyID) { // checks whether any votes with this ip for this survey exists for more than 3 days or not
    global $db_ipaddresses, $db_surveys, $timeDiff;
    $query = "SELECT * FROM $db_ipaddresses WHERE ip_address = '$ip' AND surveyID = '$surveyID'";
    $now = time();
    $result = mysqli_query($db_surveys, $query);
    while($row = mysqli_fetch_assoc($result)) {
        if ($now - $row['submited'] < $timeDiff) {
            return 0;
        }
    }
    return 1;
}

function insertIP($ip, $time, $surveyID) { // inserts data into $db_ipaddresses table
    global $db_ipaddresses, $db_surveys;
    $query = "INSERT INTO $db_ipaddresses (ip_address, surveyID, submited) VALUES ('$ip', $surveyID, $time)";
    if (mysqli_query($db_surveys, $query)) {
        return mysqli_insert_id($db_surveys);
    }
    return 0;
}

function getRecord($data) { // normalizes data into values and fields strings!
    $retData['fields'] = "";
    $retData['values'] = "";
    $isFirst = 1;
    foreach ($data as $curr) {
        if (isset($curr['questionID']) && isset($curr['result'])) {
            if ($isFirst) {
                $retData['fields'] .= '`' . $curr['questionID'] . '`';
                $retData['values'] .= "'" . $curr['result'] . "'";
                $isFirst = 0;
            }else{
                $retData['fields'] .= ', `' . $curr['questionID'] . '`';
                $retData['values'] .= ", '" . $curr['result'] . "'";
            }
        }
    }
    return $retData;
}

function insertSurvey($data, $ipAddressID) { // inserts data into $surveyTable
    global $surveyTable, $db_surveys, $ret;
    $record = getRecord($data);
    $query = "INSERT INTO $surveyTable ( " . $record['fields'] . " ) VALUES ( " . $record['values'] . " )";
    if (mysqli_query($db_surveys, $query)) {
        return 1;
    }
    $ret['boz'] = mysqli_error($db_surveys);
    $ret['fil'] = $record['fields'];
    $ret['val'] = $record['values'];
    return 0;
}

if (!$db_surveys) {
    $ret['status'] = $error['db error']; // database connection error!
}else {
    $ipStatus = checkIP($ip, $surveyID);
    if ($ipStatus == 0) {
        $ret['status'] = $error['duplicate participant']; // duplicate participant!
    }else if ($ipStatus == -1) {
        $ret['status'] = $error['db error'];
    }else{
        $ipAddressID = insertIP($ip, time(), $surveyID);
        if ($ipAddressID > 0) {
            if (insertSurvey($data, $ipAddressID)) {
                $ret['status'] = 'success';
            }else{
                $ret['status'] = $error['db error'];
            }

        }else{
            $ret['status'] = $error['db error'];
        }
    }
    mysqli_close($db_surveys);
}

header('Content-type: application/json');

echo json_encode($ret);

?>