<?php 
    /* 
    Base Controller
    Loads the models and views
    */
    class Controller{
        // Load model
        public function model($model){
        // Require model file
        require_once "../app/models/".$model.".php";
        return new $model();
        }

        // Load View
        public function view($view, $data=[]){
            if(file_exists("../app/views/".$view.".php")){
                require_once "../app/views/".$view.".php";
            }else {
                // View doesn't exist
                die("View Doesn't Exist!");
            }
        }
    }
?>