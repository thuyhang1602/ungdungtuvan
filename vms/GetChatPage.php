<?php
namespace vms;

use api\v1\UserAPI;
class GetChatPage
{
    public $rows;
    public function __construct($params = null)
    {
        session_start();
        if(isset($_SESSION['unique_id'])){
            $this->rows = UserAPI::getChat($_SESSION['unique_id'],$_POST['incoming_id']);
        }else{
            header('Location: /');
        }
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
       $output = "";
       if(count($this->rows->message) > 0){
            foreach($this->rows->message as $row){   
                if($row['outgoing_msg_id'] === $_SESSION['unique_id']){
                    $output .= '<div class="chat outgoing"><div class="details"><p>'. $row['msg'] .'</p></div></div>';
                }else{
                    $output .= '<div class="chat incoming"><img src="../images/'.$row['img'].'" alt=""><div class="details"><p>'. $row['msg'] .'</p></div></div>';
                }
            }
            echo $output;
        }else{
            $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
            echo $output;
        }
        echo "<div></div>";
    }
}