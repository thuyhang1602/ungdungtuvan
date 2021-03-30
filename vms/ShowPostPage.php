<?php
namespace vms;
use vms\templates\PostTemplate;
use api\v1\UserAPI;

class ShowPostPage {
    public $rows;
    public function __construct($params = null) {
        session_start();
        if(!isset($_SESSION['unique_id'])){
            header("Location: /");
        }
        $this->title  = "Bảng tin";
        $this->rows = UserAPI::mergePostUser();
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new PostTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
    <a href="/conver" class="back-icon"><i class="fas fa-arrow-left"></i></a>
    <?php if(count($this->rows->message) > 0): ?>
        <?php foreach($this->rows->message as $row): ?>
            <?php if(isset($row['id'])): ?>
                <div class="post-container">
                    <div class="posts">
                        <?php if($row['position'] === 'teacher' && $row['unique_id'] === $_SESSION['unique_id']): ?>
                            <div class="massaction">
                                <a href="/deletepost/<?= $row['id'] ?>"><i class="fas fa-trash-alt"></i></a>
                                <a href="/editpost/<?= $row['id'] ?>"><i class="fas fa-edit"></i></a>
                            </div>
                        <?php endif; ?>
                        <div class="post">
                            <h2><?= $row['title'] ?></h2>
                            <div><strong>Đăng bởi:</strong> <?= $row['firstname']." ".$row['lastname'] ?></div>
                            <p><strong>Nội dung:</strong> <?= $row['description'] ?></p>
                            <div class="comments" id="comments-<?= $row['id'] ?>"></div>
                            <form action="#" class="comment-box">
                                <input type="hidden" name="comment_id" id="comment_id" value="0" />
                                <input type="hidden" class="user_id" name="user_id" value="<?= $_SESSION['unique_id'] ?>"/>
                                <input type="text" name="input-comment" id="input-comment-<?= $row['id'] ?>" placeholder="Viết bình luận" class="input-comment" autocomplete="off"/>
                                <button type="button" class="comment-button" data-attr="<?= $row['id'] ?>">Bình luận</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div>Hiện tại chưa có bảng tin nào</div>
    <?php endif; ?>
<?php }}