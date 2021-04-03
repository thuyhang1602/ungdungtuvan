<?php
namespace vms;
use vms\templates\ScoreTemplate;
use api\v1\UserAPI;

class ShowScorePage {
    
    public $generic;
    public $subject_1;
    public $subject_2;
    public $total1;
    public $total2;
    public $total;
    public function __construct($params = null) {
        session_start();
        $this->title = "Tổng quan";
        $this->generic = UserAPI::getUserById($params[0]);
        $this->subject_1 = UserAPI::getSubject1($params[0]);
        $this->subject_2 = UserAPI::getSubject2($params[0]);
        $this->total1 = 0;
        $this->total2 = 0;
        if(!empty($this->subject_1->message)&&!empty($this->subject_2->message)){
            foreach($this->subject_1->message as $row){
                $this->total1 += $row['medium_score'];
            }
            foreach($this->subject_2->message as $row){
                $this->total2 += $row['medium_score'];
            }
            $this->total = ($this->total1/count($this->subject_1->message) + $this->total2/count($this->subject_2->message))/2;
        }else{
            $this->total = "";
        }
    }

    // Khai báo template và truyền bản thân vào template cha
    public function render() {
        $template = new ScoreTemplate();
        $template->renderChild($this);
    }

    // Đổi lại tên __render nếu dùng template cha
    public function __render() {
?>
<a href="/conver" class="return"><i class="fas fa-arrow-left"></i></a>
<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#generic">Tổng quan</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#detail">Xem chi tiết</a>
    </li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div id="generic" class="container tab-pane active"><br>
        <table class="table table-borderless">
            <tr>
                <th>Họ và tên</th>
                <td><?= $this->generic->message[0]['firstname']." ".$this->generic->message[0]['lastname'] ?></td>
            </tr>
            <tr>
                <th>Trường</th>
                <td><?= $this->generic->message[0]['school'] ?></td>
            </tr>
            <tr>
                <th>Sinh viên năm</th>
                <td><?= $this->generic->message[0]['school_year'] ?></td>
            </tr>
            <tr>
                <th>Chuyên ngành</th>
                <td><?= $this->generic->message[0]['major'] ?></td>
            </tr>
            <tr>
                <th>Niên khóa</th>
                <td>2021-2022</td>
            </tr>
            <tr>
                <th>Thời gian đào tạo</th>
                <td>4</td>
            </tr>
            <tr>
                <th>TBC tích lũy (10)</th>
                <?php if(!empty($this->subject_1->message)&&!empty($this->subject_2->message)): ?>
                    <td><?= round($this->total,2) ?></td>
                    <?php $result = round($this->total,2) ?>
                <?php else: ?>
                    <td>Chưa được cập nhật</td>
                <?php endif; ?>
            </tr>
            <tr>
                <th>TBC tích lũy (4)</th>
                <?php if(!empty($this->subject_1->message)&&!empty($this->subject_2->message)): ?>
                    <td>
                        <?php 
                            if($result >=4 && $result < 5){
                                echo "1.0";
                            }elseif($result >= 5 && $result < 5.5){
                                echo "1.5";
                            }elseif($result >= 5.5 && $result < 6.5){
                                echo "2.0";
                            }elseif($result >= 6.5 && $result < 7){
                                echo "2.5";
                            }elseif($result >= 7 && $result < 8){
                                echo "3.0";
                            }elseif($result >= 8 && $result < 8.5){
                                echo "3.5";
                            }elseif($result >= 8.5 && $result < 9){
                                echo "1.5";
                            }else{
                                echo "4.0";
                            }
                        ?>
                    </td>
                <?php else: ?>
                    <td>Chưa được cập nhật</td>
                <?php endif; ?>
            </tr>
        </table>
    </div>
    <div id="detail" class="container tab-pane fade"><br>
        <div class="tab">
            <button class="tablinks" onclick="openScore(event, 'HK1')" id="defaultOpen"><small>HK1</small></button>
            <button class="tablinks" onclick="openScore(event, 'HK2')"><small>HK2</small></button>
        </div>

        <div id="HK1" class="tabcontent">
            <h3>HK 1 (2020-2021)</h3>
            <?php if(!empty($this->subject_1->message)&&!empty($this->subject_2->message)): ?>
                <table class="table table-borderless">
                    <tr>
                        <th>Mã môn</th>
                        <th>Môn học</th>
                        <th>Số tín chỉ</th>
                        <th>Điểm trung bình</th>
                    </tr>
                        <?php foreach($this->subject_1->message as $row): ?>
                            <tr>
                                <td><?= $row['subject_id'] ?></td>
                                <td><?= $row['subject_name'] ?></td>
                                <td><?= $row['credits'] ?></td>
                                <td><?= $row['medium_score'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <tr>
                        <th>Trung bình</th>
                        <td><?= round(($this->total1)/count($this->subject_1->message ),2) ?></td>
                    </tr>
                </table>
            <?php else: ?>
                    <div>Hiện tại điểm chưa được cập nhập. Vui lòng quay lại sau.</div>
            <?php endif; ?>
        </div>
        
        <div id="HK2" class="tabcontent">
            <h3>HK 2 (2020-2021)</h3>
            <?php if(!empty($this->subject_1->message)&&!empty($this->subject_2->message)): ?>
            <table class="table table-borderless">
                <tr>
                    <th>Mã môn</th>
                    <th>Môn học</th>
                    <th>Số tín chỉ</th>
                    <th>Điểm trung bình</th>
                </tr>
                <?php foreach($this->subject_2->message as $row): ?>
                    <tr>
                        <td><?= $row['subject_id'] ?></td>
                        <td><?= $row['subject_name'] ?></td>
                        <td><?= $row['credits'] ?></td>
                        <td><?= $row['medium_score'] ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th>Trung bình</th>
                    <td><?= round(($this->total2)/count($this->subject_2->message ),2) ?></td>
                    
                </tr>
            </table>
            <?php else: ?>
                    <div>Hiện tại điểm chưa được cập nhập. Vui lòng quay lại sau.</div>
            <?php endif; ?>
        </div>
    </div>
  </div>
<?php }}