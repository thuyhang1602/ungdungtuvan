<?php
namespace vms;
use vms\templates\ContainerTemplate;
class NotFoundPage {
    public function __construct($params = null) {
        $this->title = "404 - Page not found";
    }

    public function render() {
        $template = new ContainerTemplate();
        $template->renderChild($this);
    }
    public function __render(){

?>

<div class="not-found">404 - Not found</div>

<?php }}