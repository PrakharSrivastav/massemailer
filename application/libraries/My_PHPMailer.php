<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class My_PHPMailer {

    private $mail;

    public function __construct($config) {
        try {

            //print_r($config);
            require_once ('PHPMailer.php');
            require_once ('SMTP.php');

            $this->mail = new PHPMailer();
//            $this->mail->clearAllRecipients();
            if (isset($config['is_smtp']) && $config['is_smtp'])
                $this->mail->isSMTP();
            if (isset($config['is_html']) && $config['is_html'])
                $this->mail->isHTML(true);
            if (isset($config['smtp_host']) && $config['smtp_host'] != '')
                $this->mail->Host = $config['smtp_host'];
            else
                throw new Exception("PHPMailer: SMTP HOST Not provided", 1);
            if (isset($config['smtp_debug']) && $config['smtp_debug'] != '')
                $this->mail->SMTPDebug = (int) $config['smtp_debug'];
            else
                $this->mail->SMTPDebug = 0;
            if (isset($config['smtp_auth']) && $config['smtp_auth'] != '')
                $this->mail->SMTPAuth = TRUE; //boolval($config['smtp_auth']);
            if (isset($config['smtp_port']) && $config['smtp_port'] != '')
                $this->mail->Port = $config['smtp_port'];
            else
                throw new Exception("PHPMailer: SMTP PORT Not provided", 1);
            if (isset($config['smtp_user']) && $config['smtp_user'] != '')
                $this->mail->Username = $config['smtp_user'];
            else
                throw new Exception("PHPMailer :SMTP USER Not provided", 1);
            if (isset($config['smtp_pass']) && $config['smtp_pass'] != '')
                $this->mail->Password = $config['smtp_pass'];
            else
                throw new Exception("PHPMailer :SMTP PASSWORD Not provided", 1);
            if (isset($config['smtp_sec']) && $config['smtp_sec'] != '')
                $this->mail->SMTPSecure = $config['smtp_sec'];
            else
                $this->mail->SMTPSecure = '';
            if (isset($config['smtp_sub']) && $config['smtp_sub'] != '')
                $this->mail->Subject = $config['smtp_sub'];
            else
                throw new Exception("PHPMailer :EMAIL SUBJECT Not provided", 1);
            if (isset($config['smtp_body']) && $config['smtp_body'] != '')
                $this->mail->Body = $config['smtp_body'];
            else
                throw new Exception("PHPMailer :EMAIL CONTENT Not provided", 1);
            if (isset($config['smtp_alt_body']) && $config['smtp_alt_body'] != '')
                $this->mail->AltBody = $config['smtp_alt_body'];
            if (isset($config['smtp_msg_body']) && $config['smtp_msg_body'] != '')
                $this->mail->msgHTML($config['smtp_msg_body']);

//            var_dump(isset($config['smtp_to']));
//            var_dump(is_array($config['smtp_to']));
//            var_dump(count($config['smtp_to']));
            // set To address
            if (isset($config['smtp_to']) && is_array($config['smtp_to']) && count($config['smtp_to']) > 0) {
//                $this->mail->clearAddresses();
//                var_dump($config['smtp_to']);
                foreach ($config['smtp_to'] as $to_address) {
                    $email = $to_address['email'];
                    $name = '';
                    if (isset($to_address['name'])) {
                        $name = $to_address['name'];
                    }
                    $this->mail->addAddress($email, $name);
                }
            } else
                throw new Exception("PHPMailer :SMTP REVIPIENT Not provided", 1);

            // set From address
            if (isset($config['smtp_from']) && is_array($config['smtp_from']) && count($config['smtp_from']) > 0) {
                foreach ($config['smtp_from'] as $from_address) {
                    $email = $from_address['email'];
                    $name = '';
                    if (isset($from_address['name'])) {
                        $name = $from_address['name'];
                    }
                    $this->mail->setFrom($email, $name);
                }
            } else
                $this->mail->From($config['smtp_user'], "");

            // set reply to address
            if (isset($config['smtp_reply_to']) && is_array($config['smtp_reply_to']) && count($config['smtp_reply_to']) > 0) {
                foreach ($config['smtp_reply_to'] as $reply_to) {
                    $email = $reply_to['email'];
                    $name = '';
                    if (isset($reply_to['name'])) {
                        $name = $reply_to['name'];
                    }
                    $this->mail->addReplyTo($email, $name);
                }
            } else
                $this->mail->addReplyTo($config['smtp_user'], "");

            // set	cc address
            if (isset($config['smtp_cc']) && is_array($config['smtp_cc']) && count($config['smtp_cc']) > 0) {
                foreach ($config['smtp_cc'] as $cc) {
                    $email = $cc['email'];
                    $name = '';
                    if (isset($cc['name'])) {
                        $name = $cc['name'];
                    }
                    $this->mail->addCC($email, $name);
                }
            }

            // set	bcc address
            if (isset($config['smtp_bcc']) && is_array($config['smtp_bcc']) && count($config['smtp_bcc']) > 0) {
                foreach ($config['smtp_bcc'] as $bcc) {
                    $email = $bcc['email'];
                    $name = '';
                    if (isset($bcc['name'])) {
                        $name = $bcc['name'];
                    }
                    $this->mail->addBCC($email, $name);
                }
            }

            // add attachments
            if (isset($config['smtp_attachment']) && is_array($config['smtp_attachment']) && count($config['smtp_attachment']) > 0) {
                foreach ($config['smtp_attachment'] as $attachment) {
                    if (file_exists($attachment['path'])) {
                        $this->mail->addAttachment($attachment['path'], $attachment['path']);
                    } else
                        throw new Exception("PHPMailer : INVALID ATTACHMENT Path: " . $attachment['path'] . " name: " . $attachment['name'], 1);
                }
            }

            if (isset($config['headers']) && is_array($config['headers']) && count($config['headers']) > 0) {
                foreach ($config['headers'] as $header) {
                    $this->mail->addCustomHeader($header);
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function send_email() {
        if ($this->mail->send()) {
            return true;
        } else {
            throw new Exception("PHPMailer : Error sending email. Error is " . $this->mail->ErrorInfo, 1);
        }
    }

//    public function clear(){
//        $this->mail->ClearAddresses();
//    }
}
