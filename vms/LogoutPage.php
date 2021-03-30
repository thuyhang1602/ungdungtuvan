<?php
namespace vms;

use api\v1\UserAPI;
class LogoutPage
{
    public $rows;
    public function __construct($params = null)
    {
        session_start();
        $this->rows = UserAPI::updateById($params[0]);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        if(isset($_SESSION['unique_id'])){
            if($this->rows->status){
                session_unset();
                session_destroy();
                session_start();
                $_SESSION['logout'] = "<div class='success-text'>Đăng xuất thành công <span class='close'>&times;</span></div>";
                header("Location: /");
            }
        }else{  
            header("Location: /");
        }
    }
}