<?php
namespace api\v1;

use libs\Mysqllib;
use db\Database;
use models\ResponseModel;
class UserAPI {

    public static function checkExistEmail($email){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $query = sprintf("SELECT * FROM users WHERE email='%s'", $conn->real_escape_string($email));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        if (count($res->message) === 1) {
            return new ResponseModel(false);
        }
        return new ResponseModel(true);
    }

    public static function save($user){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $uppercase = preg_match('@[A-Z]@', $user->password);
        $lowercase = preg_match('@[a-z]@', $user->password);
        $number    = preg_match('@[0-9]@', $user->password);

        if(!$uppercase || !$lowercase || !$number || strlen($user->password) < 8) {
            return "Invalid password";
        }
        $password_hash = password_hash($user->password, PASSWORD_DEFAULT);
        $firstname = $conn->real_escape_string($user->firstname);
        $lastname = $conn->real_escape_string($user->lastname);
        $position = $conn->real_escape_string($user->position);
        $email = $conn->real_escape_string($user->email);
        $major = $conn->real_escape_string($user->major);
        $school = $conn->real_escape_string($user->school);
        $sex = $conn->real_escape_string($user->sex);
        $isVerify = "unverify";
        $school_year =  $conn->real_escape_string($user->school_year);
        $baseUrl = substr(dirname(__FILE__),0,strpos(dirname(__FILE__),'api'));
        if(isset($user->image)){
            $fileImg = $user->image;
            $img_name = $fileImg['name'];
            $img_type = $fileImg['type'];
            $tmp_name = $fileImg['tmp_name'];
            
            $img_explode = explode('.',$img_name);
            $img_ext = end($img_explode);

            $extensions = ["jpeg", "png", "jpg"];
            if(in_array($img_ext, $extensions) === true){
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if(in_array($img_type, $types) === true){
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if(move_uploaded_file($tmp_name,str_replace('\\', '/', $baseUrl)."/images/".$new_img_name)){
                        $ran_id = rand(time(), 100000000);
                        $status = "Active now";
                        // Query
                        $insert_query = sprintf("INSERT INTO `users`(`unique_id`, `firstname`, `lastname`, `position`, `email`, `password`, `img`, `status`, `major`, `school`, `sex`, `auth`, `school_year`) VALUES ( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')", 
                            $ran_id,
                            $firstname,
                            $lastname,
                            $position,
                            $email,
                            $password_hash,
                            $new_img_name,
                            $status,
                            $major,
                            $school,
                            $sex,
                            $isVerify,
                            $school_year
                        );
                        $res = Mysqllib::mysql_post_data_from_query($conn, $insert_query);
                        if($res->status){
                            $mail = new \mail\PHPMailer();
                            $mail->isSMTP();
                            // $mail->SMTPDebug  = 1;  
                            $mail->SMTPAuth   = true;
                            $mail->SMTPSecure = "STARTTLS";
                            $mail->Port       = 587;
                            $mail->Host       = "smtp.gmail.com";
                            $mail->Username   = "ungdungtuvan@gmail.com";
                            $mail->Password   = "Thuyhang@99";
                            $mail ->CharSet = "UTF-8"; 
                            $mail->isHTML(true);
                            $mail->addAddress($email);
                            $mail->setFrom("ungdungtuvan@gmail.com","Ứng dụng tư vấn sinh viên");
                            $mail->Subject = "Verify email";
                            $content = '<html>
                                <body>
                                    <center>
                                        <p>
                                        <a href="https://devf.tech/verify/' . $email . '" 
                                        style="background-color:#ffbe00; color:#000000; display:inline-block; padding:12px 40px 12px 40px; text-align:center; text-decoration:none;" 
                                        target="_blank">Veirfy email</a>
                                        </p>
                                    </center>
                                </body>
                            </html>';
                            $mail->MsgHTML($content);
                            $mail->send();
                            header("Location: /");
                        }
                    }
                }else{
                    return "Invalid type";
                }
            }else{
                return "Invalid extension";
            }
        }
        return $res;
    }

    public static function login($email,$password,$data){
         // Connect db
         $conn_resp = Database::connect_db();
         if(!$conn_resp->status) {
             return $conn_resp;
         }
         $conn = $conn_resp->message;
         $status = "Active now";
         $query = sprintf("UPDATE users SET status='%s' WHERE email='%s'",$conn->real_escape_string($status),$conn->real_escape_string($email));
         $result = Mysqllib::mysql_post_data_from_query($conn,$query);
         if($result->status){
            $query = sprintf("SELECT unique_id,password FROM users WHERE email='%s'", $conn->real_escape_string($email));
            $res = Mysqllib::mysql_get_data_from_query($conn, $query);
            if(isset($res->message[0]["password"])){
                if (password_verify($conn->real_escape_string($password), $res->message[0]["password"])) {
                     session_start();
                    $_SESSION['unique_id'] = $res->message[0]['unique_id'];
                    return new ResponseModel(true);
                }
            }
        }
         return new ResponseModel(false);
    }

    public static function getUserById($id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT * FROM users WHERE unique_id='%s'", $conn->real_escape_string($id));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updateById($id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $status = "Offline now";
        $query = sprintf("UPDATE users SET status='%s' WHERE unique_id='%s'",$conn->real_escape_string($status),$conn->real_escape_string($id));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function search($id,$key){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $query = sprintf(
            "SELECT * FROM users WHERE NOT unique_id='%s' AND (firstname LIKE '%s' OR lastname LIKE '%s') LIMIT 5", 
            $conn->real_escape_string($id), 
            '%'.$conn->real_escape_string($key).'%',
            '%'.$conn->real_escape_string($key).'%'
        );
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getOutput($outgoing_id,$rows){
        $output = "";
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        foreach($rows as $row){
            $query = sprintf(
                "SELECT * FROM messages WHERE (incoming_msg_id = '%s'
                OR outgoing_msg_id = '%s') AND (outgoing_msg_id = '%s' 
                OR incoming_msg_id = '%s') ORDER BY msg_id DESC", 
                $conn->real_escape_string($row['unique_id']), 
                $conn->real_escape_string($row['unique_id']),
                $conn->real_escape_string($outgoing_id),
                $conn->real_escape_string($outgoing_id)
            );
            $res = Mysqllib::mysql_get_data_from_query($conn, $query);
            (count($res->message) > 0) ? $result = $res->message[0]['msg'] : $result ="No message available";
            (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
            if(isset($res->message['outgoing_msg_id'])){
                ($outgoing_id == $res->message['outgoing_msg_id']) ? $you = "You: " : $you = "";
            }else{
                $you = "";
            }
            ($row['status'] == "Offline now") ? $offline = "offline" : $offline = "";
             $output .= '<a href="/chat/'. $row['unique_id'] .'">
                    <div class="content">
                    <img src="../../images/'. $row['img'] .'" alt="">
                    <div class="details">
                        <span>'. $row['firstname']. " " . $row['lastname'] . " (" . $row['position'] . ")".'</span>
                        <p>'. $you . $msg .'</p>
                    </div>
                    </div>
                    <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
             </a>';
        }
        return $output;
    }

    public static function getUserChatById($user_id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $query = sprintf(
            "SELECT * FROM users WHERE unique_id='%s'", 
            $conn->real_escape_string($user_id)
        );
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function getUserByPosition($outgoing_id,$position){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $query = sprintf(
            "SELECT * FROM users WHERE NOT unique_id='%s'AND position = '%s' ORDER BY user_id DESC", 
            $conn->real_escape_string($outgoing_id),
            $position
        );
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function insertChat($outgoing_id,$data,$incoming_id,$image){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $baseUrl = substr(dirname(__FILE__),0,strpos(dirname(__FILE__),'api'));
        if(!empty($data)){
            if(!empty($image["name"])){
                $img_name = $image['name'];
                $img_type = $image['type'];
                $tmp_name = $image['tmp_name'];
                
                $img_explode = explode('.',$img_name);
                $img_ext = end($img_explode);
    
                $extensions = ["jpeg", "png", "jpg"];
                if(in_array($img_ext, $extensions) === true){
                    $types = ["image/jpeg", "image/jpg", "image/png"];
                    if(in_array($img_type, $types) === true){
                        $time = time();
                        $new_img_name = $time.$img_name;
                        if(move_uploaded_file($tmp_name,str_replace('\\', '/', $baseUrl)."/images/image-message/".$new_img_name)){
                            // Query
                            $query = sprintf(
                                "INSERT INTO messages(incoming_msg_id, outgoing_msg_id, msg, image_message) VALUES ('%s','%s','%s','%s')", 
                                $conn->real_escape_string($incoming_id),
                                $conn->real_escape_string($outgoing_id),
                                $conn->real_escape_string($data),
                                $new_img_name
                            );
                        }
                    }
                }
            }else{
                $query = sprintf(
                    "INSERT INTO messages(incoming_msg_id, outgoing_msg_id, msg) VALUES ('%s','%s','%s')", 
                    $conn->real_escape_string($incoming_id),
                    $conn->real_escape_string($outgoing_id),
                    $conn->real_escape_string($data)
                );
            }
            Mysqllib::mysql_post_data_from_query($conn, $query);
        }else{
            $img_name = $image['name'];
            $img_type = $image['type'];
            $tmp_name = $image['tmp_name'];
            
            $img_explode = explode('.',$img_name);
            $img_ext = end($img_explode);

            $extensions = ["jpeg", "png", "jpg"];
            if(in_array($img_ext, $extensions) === true){
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if(in_array($img_type, $types) === true){
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if(move_uploaded_file($tmp_name,str_replace('\\', '/', $baseUrl)."/images/image-message/".$new_img_name)){
                        // Query
                        $query = sprintf(
                            "INSERT INTO messages(incoming_msg_id, outgoing_msg_id, image_message) VALUES ('%s','%s','%s')", 
                            $conn->real_escape_string($incoming_id),
                            $conn->real_escape_string($outgoing_id),
                            $new_img_name
                        );
                        Mysqllib::mysql_post_data_from_query($conn, $query);
                    }
                }
            }
        }
        header('Location: /chat/'.$incoming_id);
    }

    public static function getChat($outgoing_id,$incoming_id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $query = sprintf(
            "SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
             WHERE (outgoing_msg_id = '%s' AND incoming_msg_id = '%s')
             OR (outgoing_msg_id = '%s' AND incoming_msg_id = '%s') ORDER BY msg_id
            ", 
            $conn->real_escape_string($outgoing_id),
            $conn->real_escape_string($incoming_id),
            $conn->real_escape_string($incoming_id ),
            $conn->real_escape_string($outgoing_id)
        );
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function update($user,$id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $firstname = $conn->real_escape_string($user->firstname);
        $lastname = $conn->real_escape_string($user->lastname);
        $major = $conn->real_escape_string($user->major);
        $school = $conn->real_escape_string($user->school);
        $sex = $conn->real_escape_string($user->sex);

        $baseUrl = substr(dirname(__FILE__),0,strpos(dirname(__FILE__),'api'));
        if(isset($user->image)){
            $fileImg = $user->image;
            $img_name = $fileImg['name'];
            $img_type = $fileImg['type'];
            $tmp_name = $fileImg['tmp_name'];
            
            $img_explode = explode('.',$img_name);
            $img_ext = end($img_explode);

            $extensions = ["jpeg", "png", "jpg"];
            if(in_array($img_ext, $extensions) === true){
                $types = ["image/jpeg", "image/jpg", "image/png"];
                if(in_array($img_type, $types) === true){
                    $time = time();
                    $new_img_name = $time.$img_name;
                    if(move_uploaded_file($tmp_name,str_replace('\\', '/', $baseUrl)."/images/".$new_img_name)){
                        // Query
                        $update_query = sprintf("UPDATE `users` SET `firstname`='%s',`lastname`='%s',`img`='%s',`major`='%s',`school`='%s',`sex`='%s' WHERE `unique_id` = '%s'", 
                            $firstname,
                            $lastname,
                            $new_img_name,
                            $major,
                            $school,
                            $sex,
                            $id
                        );
                        $res = Mysqllib::mysql_post_data_from_query($conn, $update_query);
                        if($res->status){
                            header("Location: /myself/".$id);
                        }
                    }
                }else{
                    return "Invalid type";
                }
            }else{
                return "Invalid extension";
            }
        }
        return $res;
    }

    public static function getUserByEmail($email){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT * FROM users WHERE email='%s'", $conn->real_escape_string($email));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updatePasswordById($id,$newPassword){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $uppercase = preg_match('@[A-Z]@', $newPassword);
        $lowercase = preg_match('@[a-z]@', $newPassword);
        $number    = preg_match('@[0-9]@', $newPassword);

        if(!$uppercase || !$lowercase || !$number || strlen($newPassword) < 8) {
            return "Invalid password";
        }
        $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $conn = $conn_resp->message;
        $query = sprintf("UPDATE users SET password='%s' WHERE unique_id='%s'",$password_hash,$id);
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function savePost($userId,$title,$description){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $insert_query = sprintf("INSERT INTO `posts`(`title`,`description`,`unique_id`) VALUES ( '%s', '%s', '%s')", 
            $title,
            $description,
            $userId
        );
        Mysqllib::mysql_post_data_from_query($conn, $insert_query);
        header("Location: /showpost");
    }

    public static function mergePostUser(){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $select_query = sprintf("SELECT p.*,u.position,u.firstname,u.lastname,u.unique_id FROM posts p INNER JOIN users u ON p.unique_id = u.unique_id");
        $res = Mysqllib::mysql_get_data_from_query($conn, $select_query);
        return $res;
    }

    public static function deletePostById($id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $delete_comment_query = sprintf("DELETE FROM `comments` WHERE post_id = '%s'",$id);
        Mysqllib::mysql_post_data_from_query($conn, $delete_comment_query);
        $delete_query = sprintf("DELETE FROM `posts` WHERE id = '%s'",$id);
        Mysqllib::mysql_post_data_from_query($conn, $delete_query);
        header("Location: /showpost");
    }

    public static function getPostById($id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT * FROM posts WHERE id='%s'",$id);
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function editPostById($postId,$title,$description){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $edit_query = sprintf("UPDATE `posts` SET `title` = '%s', `description` = '%s' WHERE `id` = '%s'",
            $title,
            $description,
            $postId
        );
        Mysqllib::mysql_post_data_from_query($conn, $edit_query);
        header("Location: /showpost");
    }

    public static function insertComment($user_id,$content,$post_id,$comment_id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $insert_query = sprintf("INSERT INTO `comments`(`content`,`user_id`,`post_id`,`parent_comment_id`) VALUES ('%s', '%s', '%s', '%s')", 
            $content,
            $user_id,
            $post_id,
            $comment_id
        );
        Mysqllib::mysql_post_data_from_query($conn, $insert_query);
        header("Location: /showpost");
    }

    public static function getComment(){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT * FROM comments, users WHERE comments.parent_comment_id = 0 AND users.unique_id = comments.user_id ORDER BY id DESC");
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function deleteCommentById($user_id,$id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $delete_comment_query = sprintf("DELETE FROM `comments` WHERE id = '%s' AND user_id = '%s'",$id, $user_id);
        Mysqllib::mysql_post_data_from_query($conn, $delete_comment_query);
        header("Location: /showpost");
    }

    public static function getSubject1($id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT * FROM score WHERE `user_id` = '%s' AND `semester` = 'HK1'",$conn->real_escape_string($id));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }
    
    public static function getSubject2($id){
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;

        $query = sprintf("SELECT * FROM score WHERE `user_id` = '%s' AND `semester` = 'HK2'",$conn->real_escape_string($id));
        $res = Mysqllib::mysql_get_data_from_query($conn, $query);
        return $res;
    }

    public static function updateAuthByEmail($email){
        session_start();
        // Connect db
        $conn_resp = Database::connect_db();
        if(!$conn_resp->status) {
            return $conn_resp;
        }
        $conn = $conn_resp->message;
        $isVerify = "verify";
        $query = sprintf("UPDATE users SET auth='%s' WHERE email='%s'",$isVerify,$email);
        Mysqllib::mysql_get_data_from_query($conn, $query);
        $data = UserAPI::getUserByEmail($email);
        $_SESSION['unique_id'] = $data->message[0]['unique_id'];
        header("Location: /conver");
    }
}
