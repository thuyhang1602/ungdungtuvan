<?php
namespace vms;
use vms\templates\ContainerTemplate;
use api\v1\UserAPI;

class ChatPage {
    public $rows;
    public $user_id;
    public function __construct($params = null) {
        $this->rows = UserAPI::getUserChatById($params[0]);
        foreach($this->rows->message as $item){
            $this->title = $item["firstname"]." ".$item["lastname"];
        }
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
            <section class="chat-area">
                <header>
                    <a href="/conver" class="back-icon"><i class="fas fa-arrow-left"></i></a>
                    <a href="/info/<?= $row['unique_id'] ?>"><img src="../images/<?= $row['img']; ?>" alt=""></a>
                    <div class="details">
                    <span><?= $row['firstname']. " " . $row['lastname'] . " (" . $row['position'] . ") " ?></span>
                    <p><?= $row['status']; ?><span class="online"><?= ($row['status']=="Active now") ? "<i class='fas fa-circle'></i>":""?></span></p>
                    </div>
                </header>
                <div class="chat-box"></div>
                <form action="#" class="typing-area">
                    <input type="text" class="incoming_id" name="incoming_id" value="<?= $this->user_id; ?>" hidden>
                    <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
                    <button id="send" type="button"><i class="fab fa-telegram-plane"></i></button>
                </form>
            </section>
        <?php endforeach; ?>
    <?php endif; ?>
<?php }}