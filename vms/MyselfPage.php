<?php
namespace vms;
use vms\templates\ContainerTemplate;
use api\v1\UserAPI;

class MyselfPage {
    public $rows;
    public $user_id;
    public function __construct($params = null) {
        $this->rows = UserAPI::getUserChatById($params[0]);
        $this->title = $this->rows->message[0]['firstname']." ".$this->rows->message[0]['lastname'];
        $this->user_id = $params[0];
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new ContainerTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
    <?php if( count($this->rows->message)> 0): ?>
        <?php foreach($this->rows->message as $row): ?>
            <a href="/conver" class="return"><i class="fas fa-arrow-left"></i></a>
            <div class="card">
                <img src="../images/<?= $row['img'] ?>" style="width:100%">
                <h1><?= $row['firstname']." ".$row['lastname'] ?></h1>
                <h3>Giới tính: <?= ($row['sex'] === 'male') ? "Nam":"Nữ" ?></h3>
                <h4>Trường: <?= $row['school'] ?></h4>
                <p class="title">Vị trí: <?= ($row['position'] === 'teacher') ? "giáo viên":"sinh viên" ?></p>
                <p><?= ($row['position'] === 'teacher') ? "Bộ môn: ".$row['major']: "Chuyên ngành: ".$row['major'] ?></p>
                <p class="email">Email: <?= $row['email'] ?></p>
                <p><button class="editButton" onclick="window.location.href='/edit/<?= $row['unique_id'] ?>';">Sửa thông tin</button></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php }}