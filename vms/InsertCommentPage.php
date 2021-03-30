<?php
namespace vms;

use api\v1\UserAPI;
class InsertCommentPage
{
    public function __construct($params = null)
    {
        UserAPI::insertComment($_POST['user_id'],$_POST['content'],$_POST['post_id'],$_POST['comment_id']);
    }
}