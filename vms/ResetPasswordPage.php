<?php
namespace vms;
class ResetPasswordPage
{
    public function __construct($params = null){}

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        if (isset($_POST['submit'])) {
            $mail = new \mail\PHPMailer();
            $mail->isSMTP();
            $mail->SMTPDebug  = 1;  
            $mail->SMTPAuth   = TRUE;
            $mail->SMTPSecure = "ssl";
            $mail->Port       = 465;
            $mail->Host       = "smtp.gmail.com";
            $mail->Username   = "ungdungtuvan@gmail.com";
            $mail->Password   = "Thuyhang@99";
            $mail ->CharSet = "UTF-8"; 
            $mail->isHTML(true);
            $mail->addAddress($_POST['email']);
            $mail->setFrom("nguyenhuuluan17@gmail.com","Ứng dụng tư vấn sinh viên");
            $mail->Subject = "Reset password";
            $content = '<html>
                <body>
                    <center>
                        <p>
                        <a href="http://localhost/reset/' . $_POST['email'] . '" 
                        style="background-color:#ffbe00; color:#000000; display:inline-block; padding:12px 40px 12px 40px; text-align:center; text-decoration:none;" 
                        target="_blank">Reset password</a>
                        </p>
                    </center>
                </body>
            </html>';
            $mail->MsgHTML($content);
            $mail->send();
            header("Location: /");
        }
    }
}