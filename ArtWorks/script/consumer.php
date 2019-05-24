<?php
/**
 * Created by PhpStorm.
 * User: zy
 * Email: zy943453722@gmail.com
 * Date: 2019/4/1
 * Time: 16:58
 */

ini_set('default_socket_timeout', 86400*7);
ini_set( 'memory_limit', '256M' );

require_once '../vendor/autoload.php';
use Pheanstalk\Pheanstalk;

class SendEmail
{
    public function dealEmail()
    {
        $beanstalkd = require '../config/beanstalkd.php';
        $consumer = new Pheanstalk($beanstalkd['mq']['host'],$beanstalkd['mq']['port']);
        while(1) {
            $job = $consumer->watch('email')->ignore('default')->reserve();
            if ($job !== false) {
                $email = (Array)json_decode($job->getData());
                //处理业务逻辑
                $this->buildEmail($email);
                $consumer->delete($job);
            }
        }
    }
    public function buildEmail($email)
    {
        $arr = require '../config/email.php';
        $transport = (new Swift_SmtpTransport($arr['email']['host'], $arr['email']['port']))
            ->setUsername($arr['email']['username'])
            ->setPassword($arr['email']['password']);
        $mailer = new Swift_Mailer($transport);
        $message = (new Swift_Message())
            ->setSubject($email['subject'])
            ->setFrom($email['from'])
            ->setTo($email['to'])
            ->setBody($email['body']);
        $mailer->send($message);
    }
}

$app = new SendEmail();
$app->dealEmail();


