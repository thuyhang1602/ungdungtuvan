<?php
namespace vms;

use api\v1\UserAPI;
class GetCommentPage
{
    public $rows;
    public $user;
    public $data;
    public function __construct($params = null)
    {
        $this->rows = UserAPI::getComment();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        if(is_array($this->rows->message)){
            if(count($this->rows->message) > 0){
                echo json_encode($this->rows->message);
            }
        }
        
    }
}