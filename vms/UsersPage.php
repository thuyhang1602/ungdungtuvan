<?php
namespace vms;
use vms\templates\ContainerTemplate;
use api\v1\UserAPI;

class UsersPage {
    public $rows;
    public $data;
    public $position;
    public function __construct($params = null) {
        if($params[0] === 'student'){
            $this->title = "Danh sách sinh viên";
        }else{
            $this->title = "Danh sách giáo viên";
        }
        session_start();
        $this->rows = UserAPI::getUserByPosition($_SESSION['unique_id'],$params[0]);
        $this->data = UserAPI::getOutput($_SESSION['unique_id'],$this->rows->message);
        $this->position = $params[0];
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
                        if($this->position === 'student'){
                            echo "Không có sinh viên";
                        }else{
                            echo "Không có giáo viên";
                        }
                    }
                }
            ?>
        </div>
    </section>
<?php }}