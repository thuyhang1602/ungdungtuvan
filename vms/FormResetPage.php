<?php
namespace vms;
use vms\templates\ContainerTemplate;

class FormResetPage {
    public function __construct($params = null) {
        $this->title  = "Lấy lại mật khẩu";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new ContainerTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<section class="form login">
      <header>Lấy lại mật khẩu</header>
      <form action="/resetpassword" method="POST" enctype="multipart/form-data" autocomplete="off">
          <div class="field input">
            <label>Email</label>
            <input type="email" name="email" placeholder="Nhập địa chỉ email" required>
          </div>
          <div class="field button">
            <input type="submit" name="submit" value="Xác thực mail">
          </div>
          <a href="/" class="return"><i class="fas fa-arrow-left"></i></a>
      </form>
</section>
<?php }}