<?php
namespace vms;
use vms\templates\ContainerTemplate;
use api\v1\UserAPI;

class ConverPage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['unique_id'])){
            header("Location: /");
        }
        $this->title  = "Trang chủ";
        $this->rows = UserAPI::getUserById($_SESSION['unique_id']);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new ContainerTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<section class="users">
    <?php  foreach($this->rows->message as $row): ?>
      <header>
        <div class="content">
          <a href="/myself/<?= $row['unique_id'] ?>" title="Thông tin cá nhân"><img src="/images/<?= $row['img'] ?>" alt=""></a>
          <div class="details">
            <span><?= $row['firstname']. " " . $row['lastname'] . " (" . $row['position'] . ") " ?></span>
              <p><?= $row['status'] ?><span class="online"><?= ($row['status']=="Active now") ? "<i class='fas fa-circle'></i>":""?></span></p>
          </div>
        </div>
        <a href="/logout/<?= $row['unique_id'] ?>" class="logout">Đăng xuất</a>
      </header>
      <h4>Chức năng chính</h4>
      <div class="function">
          <div class="function-item">
              <?php if($row['position'] === 'student'): ?>
                <a href="/users/teacher" id="list-user" title="Danh sách giáo viên"><i class="fas fa-chalkboard-teacher"></i></a>
                <div class="title-function">Giáo viên</div>
              <?php elseif($row['position'] === 'teacher'): ?>
                <a href="/users/student" id="list-user" title="Danh sách sinh viên"><i class="fas fa-users"></i></a>
                <div class="title-function">Sinh viên</div>
              <?php endif; ?>
          </div>
          <?php if($row['position'] === 'teacher'): ?>
            <div class="function-item">
              <?php $_SESSION['is_teacher'] = $row['unique_id']; ?>
              <a href="/post" id="list-user" title="Đăng bài"><i class="fas fa-pen-alt"></i></a>
              <div class="title-function">Đăng bài</div>
            </div>
          <?php endif; ?>
          <div class="function-item">
              <a href="/showpost" id="list-user" title="Xem bài"><i class="fas fa-newspaper"></i></a>
              <div class="title-function">Tin tức</div>
          </div>
          <?php if($row['position'] === 'student'): ?>
            <div class="function-item">
              <a href="/showscore/<?= $row['unique_id'] ?>" id="list-user" title="Xem điểm"><i class="fas fa-school"></i></a>
              <div class="title-function">Xem điểm</div>
            </div>
          <?php endif; ?>
          <div class="function-item">
              <a href="/searchpage" id="list-user" title="Tìm kiếm"><i class="fas fa-search"></i></a>
              <div class="title-function">Tìm kiếm</div>
          </div>
      </div>
    <?php endforeach; ?>
</section>
<?php }}