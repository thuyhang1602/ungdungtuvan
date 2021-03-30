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
              <div class="function">
                <?php if($row['position'] === 'student'): ?>
                  <a href="/users/teacher" id="list-user" title="Danh sách giáo viên"><i class="fas fa-chalkboard-teacher"></i></a>
                <?php elseif($row['position'] === 'teacher'): ?>
                  <a href="/users/student" id="list-user" title="Danh sách sinh viên"><i class="fas fa-users"></i></i></a>
                <?php endif; ?>
                <?php if($row['position'] === 'teacher'): ?>
                  <?php $_SESSION['is_teacher'] = $row['unique_id'];  ?>
                  <a href="/post" id="list-user" title="Đăng bài"><i class="fas fa-pen-alt"></i></a>
                <?php endif; ?>
                <a href="/showpost" id="list-user" title="Xem bài"><i class="fas fa-newspaper"></i></a>
              </div>
          </div>
        </div>
        <a href="/logout/<?= $row['unique_id'] ?>" class="logout">Đăng xuất</a>
      </header>
      <div class="search">
        <input type="hidden" value="<?= $row['unique_id'] ?>" id="unique_id">
        <input type="text" placeholder="Nhập tên để tìm kiếm..." id="key">
        <span id="loader"><i class="fa fa-spinner fa-spin"></i></span>
        <button id="search"><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list"></div>
    <?php endforeach; ?>
</section>
<?php }}