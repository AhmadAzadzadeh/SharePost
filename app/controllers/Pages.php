<?php
    class Pages extends Controller {
        public function __construct(){
            
        }

        public function index(){
            if(isLoggedIn()){
                redirect("Posts");
            }
            $data = [
                "title" => "SharePosts",
                "description" => "Simple Social Network Built On The Future PHP Framework"
            ];
            $this -> view("Pages/index", $data);
        }

        public function about(){
            $data = [
                "title" => "About Page",
                "description" => "App To Share Post With Other Users"
            ];
            $this -> view("Pages/about", $data);
        }
    }
?>