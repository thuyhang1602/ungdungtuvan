<?php
namespace vms;

use api\v1\UserAPI;
class SearchPage
{
    public $rows;
    public $data;
    public function __construct($params = null)
    {
        if(!empty($_POST['key'])){
            $this->rows = UserAPI::search($_POST['unique_id'],$_POST['key']);
            $this->data = UserAPI::getOutput($_POST['unique_id'],$this->rows->message);
        }
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
       if(isset($this->rows->message)){
            if(count($this->rows->message) > 0){
                echo $this->data;
            }else{
                    $output = 'No user found related to your search term';
                    echo $output;
            }
       }else{
            $output = 'No user found related to your search term';
            echo $output;
       }
    }
}