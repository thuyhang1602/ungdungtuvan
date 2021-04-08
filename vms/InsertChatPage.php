<?php
namespace vms;

use api\v1\UserAPI;
class InsertChatPage
{
    public function __construct($params = null)
    {
        session_start();
        if(isset($_POST['submit'])){
            UserAPI::insertChat($_SESSION['unique_id'],$_POST['message'],$_POST['incoming_id'],$_FILES['file-input']);
        }else{
            header('Location: /');
        }
    }
}