<?php
namespace vms;
use vms\templates\ContainerTemplate;
use api\v1\UserAPI;

class MailPage {
    public $rows;
    public function __construct($params = null) {
        $this->title = "Gửi mail";
        $this->rows = UserAPI::getUserById($params[0]);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new ContainerTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
    <section class="form signup">
    <header>Gửi mail</header>
    <?php foreach($this->rows->message as $row): ?>
      <form action="/sendmail/<?= $row['email'] ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="field input">
          <label>Mật khẩu</label>
          <input type="password" name="password" id="pass_log_id" placeholder="Nhập mật khẩu email của bạn" required>
          <i class="fas fa-eye toggle-password" toggle="#password-field"></i>
        </div>
        <div class="field input">
          <label>Tiêu đề mail</label>
          <input type="text" name="subject" placeholder="Nhập tiêu đề mail" required>
        </div>
        <div class="field input content">
          <label>Nội dung</label>
          <textarea cols="35" rows="12" name="content"></textarea>
        </div>
        <div class="field button">
          <input type="submit" name="submit" value="Gửi">
        </div>
      </form>
      <a href="/chat/<?= $row['unique_id'] ?>" class="return"><i class="fas fa-arrow-left"></i></a>
      <?php endforeach ?>
    </section>
<?php }}