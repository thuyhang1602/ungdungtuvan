<?php
namespace vms;
use api\v1\UserAPI;

class DeletePostPage
{
    public $post_id;
    public function __construct($params = null)
    {
        $this->post_id = $params[0];
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        UserAPI::deletePostById($this->post_id);
    }
}