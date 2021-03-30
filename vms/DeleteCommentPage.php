<?php
namespace vms;
use api\v1\UserAPI;

class DeleteCommentPage
{
    public $post_id;
    public function __construct($params = null)
    {
        $this->comment_id = $params[0];
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        session_start();
        UserAPI::deleteCommentById($_SESSION['unique_id'],$this->comment_id);
    }
}