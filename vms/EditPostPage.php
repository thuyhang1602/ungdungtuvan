<?php
namespace vms;
use vms\templates\PostTemplate;
use api\v1\UserAPI;

class EditPostPage {
    public $rows;
    public function __construct($params = null) {
        $this->title  = "Sửa tin";
        $this->rows = UserAPI::getPostById($params[0]);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new PostTemplate();
        $template->renderChild($this);
        if(isset($_POST['submit'])){
            UserAPI::editPostById($_POST['post_id'],$_POST['title'],$_POST['description']);
        }
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<div class="row">
    <div class="col-md-8 col-md-offset-2">  
        <h1>Sửa tin</h1>
        <?php foreach($this->rows->message as $row): ?>
            <form action="/editpost" method="POST">
                <input type="hidden" value="<?= $row['id'] ?>" name="post_id"/>
                <div class="form-group">
                    <label for="title">Tiêu đề <span class="require">*</span></label>
                    <input type="text" class="form-control" name="title" value=<?= $row['title'] ?> required/>
                </div>
                <div class="form-group">
                    <label for="description">Nội dung <span class="require">*</span></label>
                    <textarea rows="5" class="form-control" id="description" name="description" required><?= $row['description'] ?></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" name="submit" class="btn btn-primary">
                        Cập nhật
                    </button>
                    <button class="btn btn-default" type="reset">
                        Reset
                    </button>
                </div>
            </form>
        <?php endforeach; ?>
        <a href="/conver" class="back-icon"><i class="fas fa-arrow-left"></i></a>
    </div>
</div>
<?php }}