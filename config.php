<?php

// database credintals
$db_name = 'beeSurvey'; // mysql database name
$db_prefix = 'survey_'; // tables name is like "$db_prefix".surveyID
$db_host = ''; // mysql database host
$db_port = 3306; // mysql database host port
$db_username = ''; // mysql database username
$db_password = ''; // mysql database password
$db_ipaddresses = 'ip_addresses'; // ipaddresses table to save which ips have submited

// localizations
$error['duplicate participant'] = 'شما قبلا شرکت کرده‌اید!';
$error['db error'] = 'در پایگاه‌داده‌ها مشکلی پیش آمده‌است، بعدا تلاش کنید!';

// time for anti-cheat system
$timeDiff = 3 * 24 * 60 * 60;

?>