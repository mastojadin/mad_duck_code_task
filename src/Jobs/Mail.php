<?php namespace App\Jobs;

use App\Core\Config;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mail {
    private string $email_to;
    private string $email_from;
    private string $subject;
    private string $message;

    private function __construct($email_to, $email_from, $subject, $message)
    {
        $this->email_to = $email_to;
        $this->email_from = $email_from;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * sends email
     * @param string $email_to
     * @param string $email_from
     * @param string $subject
     * @param string $message
     * 
     * @return bool
     */
    public static function send_email(string $email_to, string $email_from, string $subject, string $message): bool
    {
        $m = new Mail($email_to, $email_from, $subject, $message);
        return $m->real_send_email();
    }

    /**
     * sends email
     * but for real
     * 
     * @return bool
     */
    private function real_send_email(): bool
    {
        $vars = Config::get_me('vars');
        $host = $vars['mail_host'];
        $port = $vars['mail_port'];
        $username = $vars['mail_username'];
        $password = $vars['mail_password'];

        $mail = new PHPMailer(true);

        try {
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = $host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $username;
            $mail->Password   = $password;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = $port;
        
            //Recipients
            $mail->setFrom($this->email_from);
            $mail->addAddress($this->email_to);
        
            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body    = $this->message;
        
            $mail->send();
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}