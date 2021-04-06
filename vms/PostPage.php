<?php
namespace vms;
use vms\templates\PostTemplate;
use api\v1\UserAPI;

class PostPage {
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['is_teacher'])){
            header("Location: /conver");
        }
        $this->title  = "Đăng tin";
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new PostTemplate();
        $template->renderChild($this);
        if(isset($_POST['submit'])){
            UserAPI::savePost($_POST['user_id'],$_POST['title'],$_POST['description']);
        }
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<a href="/conver" class="back-icon"><i class="fas fa-arrow-left"></i></a>
<div class="row d-flex justify-content-center mt-4">
    <div class="col-md-8 col-md-offset-2">  
        <h1>Đăng tin</h1>
        <form action="/post" method="POST">
            <input type="hidden" value="<?= $_SESSION['unique_id'] ?>" name="user_id"/>
            <div class="form-group">
                <label for="title">Tiêu đề <span class="require">*</span></label>
                <input type="text" class="form-control" name="title" required/>
            </div>
            <div class="form-group">
                <label for="description">Nội dung <span class="require">*</span></label>
                <textarea rows="5" class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-primary">
                    Đăng tin
                </button>
                <button class="btn btn-default" type="reset">
                    Reset
                </button>
            </div>
        </form>
    </div>
</div>
<?php }}