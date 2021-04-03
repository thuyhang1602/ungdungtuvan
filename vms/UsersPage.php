<?php
namespace vms;
use vms\templates\ContainerTemplate;
use api\v1\UserAPI;

class UsersPage {
    public $rows;
    public $data;
    public function __construct($params = null) {
        $this->title = "Danh sách giáo viên";
        session_start();
        $this->rows = UserAPI::getUserByPosition($_SESSION['unique_id'],$params[0]);
        $this->data = UserAPI::getOutput($_SESSION['unique_id'],$this->rows->message);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new ContainerTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
    <a href="/conver" class="return"><i class="fas fa-arrow-left"></i></a>
    <section class="users">
        <div class="users-list">
            <?php
                if (isset($this->rows->message)) {
                    if (count($this->rows->message) > 0) {
                        echo $this->data;
                    }else{
                        echo "Không có giáo viên";
                    }
                }
            ?>
        </div>
    </section>
<?php }}