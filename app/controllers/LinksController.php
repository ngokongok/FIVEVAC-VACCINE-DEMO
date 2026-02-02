<?php
    // app/controllers/LinksController.php
class LinksController extends Controller {

    // Quản lý liên kết – chỉ dành cho quản trị viên
    public function add() {
        require_role(['admin']);
        $this->view('links/add', []);
    }

    public function delete() {
        require_role(['admin']);
        $this->view('links/delete', []);
    }

}