<?php
namespace App\Lib;
use PHPMailer\PHPMailer\PHPMailer;
use Cake\Mailer\Email;
use Cake\Mailer\Mailer;
use Cake\View\ViewBuilder;
use Cake\Mailer\TransportFactory;

class EmailService {

    const SMTP_USERNAME = 'quoootes@gmail.com';
    const SMTP_PASSWORD = 'akigbhlpyeurvwba';

    private static $mailer = null;

    private static function getMailer () {

        if (self::$mailer) {
            return self::$mailer;
        }

        $mailer = new PHPMailer();
        $mailer->IsSMTP();
        $mailer->Mailer = "smtp";
        $mailer->SMTPDebug  = false;
        $mailer->SMTPAuth   = TRUE;
        $mailer->SMTPSecure = "tls";
        $mailer->Port       = 587;
        $mailer->Host       = "smtp.gmail.com";
        $mailer->Username   = self::SMTP_USERNAME;
        $mailer->Password   = self::SMTP_PASSWORD;

        self::$mailer = $mailer;

        return self::$mailer;
    }

    public static function send ($to=null, $subject='', $content='', $data=[], $preview=true) {

        // $builder = new ViewBuilder();
        // $builder->setLayout('default');
        // $builder->disableAutoLayout();
        // $builder->setTemplate('/email/html/contact_form');
        // $view = $builder->build([]);
        // $htmlContent = $view->render();

        // echo $htmlContent; die;

        $mailer = new Mailer('default');
        $mailer
            ->setTransport('gmail')
            ->setFrom(['no-reply@quoootes.com' => 'Quoootes'])
            ->setTo($to)
            ->setEmailFormat('html')
            ->setSubject($subject)
            ->viewBuilder()
            ->disableAutoLayout()
            ->setTemplate('/Email/html/contact_form');

        // return $this->render('/Email/html/contact_form');

        if ($preview) {
            echo $mailer->viewBuilder()->build()->render();
        }
        die;

            $mailer->deliver();

        var_dump($mailer);
        die;
        return false;

        $mailer = self::getMailer();

        $mailer->IsHTML(true);
        $mailer->AddAddress($to);
        $mailer->SetFrom("support@quoootes.com", "Quoootes");
        $mailer->AddReplyTo("support@quoootes.com", "Quoootes");
        $mailer->Subject = $subject;

        $mailer->MsgHTML($content);

        if(!$mailer->Send()) {
            return false;
        }

        return true;
    }

    public static function sendContacForm ($data=[], $preview = false) {

        $mailer = new Mailer('default');
        $mailer
            ->setTransport('gmail')
            ->setFrom(['quoootes@gmail.com' => 'Quoootes'])
            ->setReplyTo($data['email'], $data['name'])
            ->setTo('quoootes@gmail.com', 'Quoootes')
            ->setEmailFormat('html')
            ->setSubject($data['topic'])
            ->setViewVars($data)
            ->viewBuilder()
            ->disableAutoLayout()
            ->setTemplate('/Email/html/contact_form');

            if ($preview) {
                echo $mailer->viewBuilder()->build()->render();
                die;
            }

            try {
                $mailer->deliver();
            } catch (\Exception $e) {
                echo $e->getMessage(); die;
                return false;
            }
        // $mailer = self::getMailer();

        // $mailer->IsHTML(true);
        // $mailer->AddAddress("support@quoootes.com", "Quoootes");
        // $mailer->SetFrom("support@quoootes.com", "Quoootes");
        // $mailer->AddReplyTo($reply_to, $reply_to_name);
        // $mailer->Subject = $subject;

        // $mailer->MsgHTML($content);

        // if(!$mailer->Send()) {
        //     return false;
        // }

        return true;
    }

}