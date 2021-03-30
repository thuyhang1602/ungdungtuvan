<?php
namespace vms;

use api\v1\UserAPI;
class InsertChatPage
{
    public function __construct($params = null)
    {
        session_start();
        if(isset($_SESSION['unique_id'])){
            UserAPI::insertChat($_SESSION['unique_id'],$_POST['data'],$_POST['incoming_id'],$_POST['imageUpload']);
        }else{
            header('Location: /');
        }
    }
}