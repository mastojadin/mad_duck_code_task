<?php

// the idea is that cron fires this one hourly
// check if timezone done_at for the tasks fit hte cretaria

// put this in your crontab file ( or do the schedule task on Win, or ??? for Mac )
// 59 * * * * /var/www/html/MY_DIR/sending_email.php checkcron=OK
// cron execute on every 59 minute on hour

$finished_today = false;

if (isset($argv[1]) && $argv[1] == "checkcron=OK") {
    require __DIR__ . '/../vendor/autoload.php';
    $cron = 'App\Cron\Emails';
    $cron = new $cron;
    
    // $fake_check_for_sending_emails = '2022-12-06';
    // $cron->send_emails($fake_check_for_sending_emails);
    
    $cron->send_emails();
}
