<?php
namespace vms;
use vms\templates\ContainerTemplate;
use api\v1\UserAPI;

class SendMailPage
{
    public $recepient;
    public $datas;
    public function __construct($params = null)
    {
        session_start();
        $this->datas = UserAPI::getUserById($_SESSION['unique_id']);
        $this->recepient = $params[0];
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        if (isset($_POST['submit'])) {
            $mail = new \mail\PHPMailer();
            $mail->isSMTP();
            $mail->Mailer = "smtp";
            $mail->SMTPDebug  = 1;  
            $mail->SMTPAuth   = TRUE;
            $mail->SMTPSecure = "STARTTLS";
            $mail->Port       = 587;
            $mail->Host       = "smtp.gmail.com";
            $mail->Username   = $this->datas->message[0]["email"];
            $mail->Password   = $_POST['password'];
            $mail ->CharSet = "UTF-8"; 
            $mail->isHTML(true);
            $mail->addAddress($this->recepient);
            $mail->setFrom($this->datas->message[0]["email"],$this->datas->message[0]["firstname"]." ".$this->datas->message[0]["lastname"]);
            $mail->Subject = $_POST['subject'];
            $mail->Body = $_POST['content'];
            $mail->send();
            header("Location: /conver");
        }
    }
}