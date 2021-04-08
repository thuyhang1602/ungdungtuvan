<?php
namespace vms;

use vms\templates\ContainerTemplate;
use api\v1\UserAPI;
use models\UserModel;

class RegisterPage
{
    public function __construct($params = null)
    {
        $this->title  = "Đăng ký";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        session_start();
        $template = new ContainerTemplate();
        // Register user
        if(isset($_POST['submit'])){
            $email = $_POST["email"];
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
              $checkExist = UserAPI::checkExistEmail($email);
              if ($checkExist->status) {
                  $user = new UserModel($_POST,$_FILES);
                  $result = UserAPI::save($user);
                  if($result == "Invalid type"){
                      $_SESSION['error'] = "<div class='error-text'>Please upload an image file - jpeg, png, jpg <span class='close'>&times;</span></div>";
                  }elseif($result == "Invalid extension"){
                      $_SESSION['error'] = "<div class='error-text'>Extension file is invalid! <span class='close'>&times;</span></div>";
                  }elseif($result == "Invalid password"){
                      $_SESSION['error'] = "<div class='error-text'>Password must have uppercase letter, lower letter and number. <span class='close'>&times;</span></div>";
                  }
              }else{
                  $_SESSION['error'] = "<div class='error-text'>$email - This email already exist! <span class='close'>&times;</span></div>";
              }  
            }else{
              $_SESSION['error'] = "<div class='error-text'>$email is not a valid email! <span class='close'>&times;</span></div>";
            }
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render()
    {
        ?>
<section class="form signup">
    <header>Đăng ký</header>
      <form action="register" method="POST" enctype="multipart/form-data" autocomplete="off">
        <?= isset($_SESSION['error']) ? $_SESSION['error']: "" ?>
        <?php unset($_SESSION['error']) ?>
        <div class="name-details">
          <div class="field input">
            <label>Tên</label>
            <input type="text" name="firstname" placeholder="Nhập tên" required>
          </div>
          <div class="field input">
            <label>Họ</label>
            <input type="text" name="lastname" placeholder="Nhập họ" required>
          </div>
        </div>
        <div class="field">
          <label>Giới tính</label>
          <select name="sex" id="sex" required>
              <option value="male">Nam</option>
              <option value="female">Nữ</option>
          </select>
        </div>
        <div class="field">
          <label>Chức vụ</label>
          <select name="position" id="position" required>
              <option value="student">Sinh viên</option>
              <option value="teacher">Giáo viên</option>
          </select>
        </div>
        <div class="field input">
          <label>Email</label>
          <input type="email" name="email" placeholder="Nhập địa chỉ email" required>
        </div>
        <div class="field input">
          <label for="school">Trường học</label>
          <input type="text" name="school" id="school" placeholder="Nhập trường học" required>
        </div>
        <div class="field input">
          <label for="school_year">Sinh viên năm</label>
          <input type="number" name="school_year" id="school_year" min="1" max="9" placeholder="Bạn là sinh viên năm mấy ?">
        </div>
        <div class="field input">
          <label for="major">Chuyên ngành</label>
          <input type="text" name="major" id="major" placeholder="Nhập chuyên ngành" required>
        </div>
        <div class="field input">
          <label>Mật khẩu</label>
          <input type="password" name="password" id="pass_log_id" placeholder="Đặt mật khẩu" required>
          <i class="fas fa-eye toggle-password" toggle="#password-field"></i>
        </div>
        <div class="field image">
          <label>Chọn hình ảnh</label>
          <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
        </div>
        <div class="field button">
          <input type="submit" name="submit" value="Đăng ký">
        </div>
      </form>
      <div class="link">Bạn đã có tài khoản rồi? <a href="/">Nhấp vào đây để đăng nhập</a></div>
</section>
<?php
    }
}
