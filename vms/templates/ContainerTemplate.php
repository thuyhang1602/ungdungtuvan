<?php
namespace vms\templates;

class ContainerTemplate {
    // Khai báo child và hàm render child view-model
    public $child;
    public function renderChild($child) {
        $this->child = $child;
        $this->render();
    }

    public function __construct($params = null) {}

    public function render() {
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?= $this->child->title ?></title>
        <link rel='shortcut icon' type='image/x-icon' href='/assets/img/favicon.ico'/>
        <link rel="stylesheet" href="/assets/css/style.css" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    </head>
    <body>
        <div class="wrapper">
            <?php $this->child->__render(); ?>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="/assets/js/toggle-password.js"></script>
        <script src="/assets/js/handle.js"></script>
    </body>
    </html>
<?php }}