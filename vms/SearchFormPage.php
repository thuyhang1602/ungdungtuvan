<?php
namespace vms;
use vms\templates\ContainerTemplate;
use api\v1\UserAPI;

class SearchFormPage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['unique_id'])){
            header("Location: /");
        }
        $this->title  = "Trang tìm kiếm";
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
    <a href="/conver" class="return"><i class="fas fa-arrow-left"></i></a>
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