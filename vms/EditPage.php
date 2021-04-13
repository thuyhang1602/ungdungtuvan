<?php
namespace vms;

use vms\templates\ContainerTemplate;
use api\v1\UserAPI;
use models\UserModel;

class EditPage
{
    public $rows;
    public function __construct($params = null)
    {
        session_start();
        $this->title  = "Sửa thông tin";
        $this->rows = UserAPI::getUserById($params[0]);
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render()
    {
        $template = new ContainerTemplate();
        // Register user
        if (isset($_POST['submit'])) {
          $user = new UserModel($_POST, $_FILES);
          $result = UserAPI::update($user,$_SESSION['unique_id']);
          if ($result == "Invalid type") {
              $_SESSION['error'] = "<div class='error-text'>Please upload an image file - jpeg, png, jpg <span class='close'>&times;</span></div>";
          } elseif ($result == "Invalid extension") {
              $_SESSION['error'] = "<div class='error-text'>Extension file is invalid! <span class='close'>&times;</span></div>";
          }
        }
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render()
    {
        ?>
<section class="form signup">
<?php foreach ($this->rows->message as $row): ?>
  <a href="/myself/<?= $row['unique_id'] ?>" class="return"><i class="fas fa-arrow-left"></i></a>
    <header>Sửa thông tin sinh viên</header>
      <form action="/edit" method="POST" enctype="multipart/form-data" autocomplete="off">
        <?= isset($_SESSION['error']) ? $_SESSION['error']: "" ?>
        <?php unset($_SESSION['error']); ?>
        <div class="name-details">
          <div class="field input">
            <label>Tên</label>
            <input type="text" name="firstname" placeholder="Nhập tên" value=<?= $row['firstname']?> required>
          </div>
          <div class="field input">
            <label>Họ</label>
            <input type="text" name="lastname" placeholder="Nhập họ" value=<?= $row['lastname']?> required>
          </div>
        </div>
        <div class="field">
          <label>Giới tính</label>
          <select name="sex" id="sex" required>
            <?php if ($row['sex'] == 'male'): ?>
                <option value="male" selected>Nam</option>
                <option value="female">Nữ</option>
            <?php else: ?>
                <option value="male">Nam</option>
                <option value="female" selected>Nữ</option>
            <?php endif; ?>
          </select>
        </div> 
        <div class="field input">
          <label for="school">Trường học</label>
          <input type="text" name="school" id="school" placeholder="Nhập trường học" value=<?= $row['school'] ?> required>
        </div>
        <div class="field input">
          <label for="major">Chuyên ngành</label>
          <input type="text" name="major" id="major" placeholder="Nhập chuyên ngành"  value=<?= $row['major'] ?> required>
        </div>
        <div class="field image">
          <label>Chọn hình ảnh</label>
          <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
        </div>
        <div class="field button">
          <input type="submit" name="submit" value="Sửa">
        </div>
      </form>
      <?php endforeach; ?>
</section>
<?php
    }
}
