<?php
// app/core/Controller.php
class Controller {
    public function model($model){
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }
    public function view($view, $data = []){
        extract($data);
        require '../app/views/layout/header.php';
        require '../app/views/' . $view . '.php';
        require '../app/views/layout/footer.php';
    }
}