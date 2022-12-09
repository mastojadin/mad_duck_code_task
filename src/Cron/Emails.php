<?php namespace App\Cron;

use App\Core\DB;
use App\Jobs\Mail;

class Emails {
    public function send_emails($fake_date = false)
    {
        if (!$fake_date) {
            $today_start = gmdate('Y-m-d') . ' 00:00:00';
            $today_end = gmdate('Y-m-d') . ' 23:59:59';
        } else {
            $today_start = gmdate('Y-m-d', strtotime($fake_date)) . ' 00:00:00';
            $today_end = gmdate('Y-m-d', strtotime($fake_date)) . ' 23:59:59';
        }

        $query = "
            SELECT
                u.id as uid,
                u.email as email,
                u.timezone as timezone,
                l.id as lid,
                l.title as list_title,
                t.id as tid,
                t.title as task_title
            FROM users u
            LEFT JOIN lists l ON l.user_id = u.id
            LEFT JOIN tasks t ON t.list_id = l.id
            WHERE 1=1
                AND ADDTIME(t.done_at, CONCAT((-1*u.timezone), ':00:00')) BETWEEN :today_start AND :today_end
        ";
        $params = [
            ":today_start" => $today_start,
            ":today_end" => $today_end,
        ];
        $res = DB::do_my_query($query, $params);

        foreach ($res as $key => $value) {
            $for_midnight_check = !$fake_date ? date('Y-m-d H:i:s') : $fake_date;
            if (!$this->check_if_midnight_according_to_timezone($for_midnight_check, $value['timezone'])) {
                unset($res[$key]);
            }
        }

        $emails = $this->transform_to_nice($res);

        var_dump($emails);
        exit;

        foreach ($emails as $email => $values) {
            $email_to = $email;
            $email_from = 'Mad_Duck_Code_Task@gmail.com';
            $subject = 'Finished Tasks';
            $message = $this->create_html_message($values);

            $check = Mail::send_email($email_to, $email_from, $subject, $message);
            // do something id check is false
        }
    }

    private function transform_to_nice(array $data): array
    {
        $to_return = [];

        foreach ($data as $one) {
            if (!isset($to_return[$one['email']])) {
                $to_return[$one['email']] = [];
            }
            if (!isset($to_return[$one['email']][$one['lid']])) {
                $to_return[$one['email']][$one['lid']] = [];
            }

            $to_return[$one['email']][$one['lid']] = array($one['list_title'], $one['task_title']);
        }

        return $to_return;
    }

    private function check_if_midnight_according_to_timezone($date, $timezone)
    {
        $first_date_comparison = strtotime($date) + 60*$timezone + 60*60; // adding hour to init date + timezone
        $second_date_comparison = strtotime($date) + 60*$timezone + 60*60*24; // adding day to init date + timezone

        // if its the same day then its midnight
        // i think
        // ...
        return date('Y-m-d', $first_date_comparison) == date('Y-m-d', $second_date_comparison);
    }

    private function create_html_message(array $data): string
    {
        $to_return  = '';
        $to_return .= 'Tasks You Finished Today Are As Followed';
        $to_return .= '<br>';
        foreach ($data as $lid => $values) {
            $to_return .= 'Task => ' . $values[1] . ' From ' . $values[0] . ' Lists';
            $to_return .= '<br>';
        }
        $to_return .= '<hr>';
        $to_return .= 'Congratulations :)';

        return $to_return;
    }
}