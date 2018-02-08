<?php
    class Posts extends Controller {
        public function __construct(){
            if(!isLoggedIn()){
                redirect("users/login");
            }
            $this->postModel = $this->model("Post");
            $this->userModel = $this->model("User");
        }
        public function index(){
            $posts = $this->postModel->getPosts();
            $data = [
                "posts" => $posts
            ];
            $this->view("Posts/index", $data);
        }

        public function add(){
            if($_SERVER["REQUEST_METHOD"] == "POST" ){
                $data = [
                    "title" =>  trim($_POST["title"]),
                    "body" => trim($_POST["body"]),
                    "user_id" => $_SESSION["user_id"],
                    "title_err" => "",
                    "body_err" => ""
                ];
                // Validate title
                if(empty($data["title"])){
                    $data["title_err"] = "Enter Your Title.";
                }

                // Validate body
                if (empty($data["body"])) {
                    $data["body_err"] = "Enter The Body Of The Post.";
                }

                // Make Sure We haven't any error
                if(empty($data["title_err"]) && empty($data["body_err"])){
                    // Validated
                    if($this->postModel->addPost($data)){
                        flash("post_message", "Post Added");
                        redirect("posts");
                    }else {
                        die("Something Went Wrong, Try Again");
                    }
                }else {
                    // Load data With errors
                    $this->view("Posts/add", $data);
                }
            }else {
                $data = [
                    "title" => "",
                    "body" => "",
                    "title_err" => "",
                    "body_err" => ""
                ];
                $this->view("Posts/add", $data);   
            }
        }
        public function show($id){
            $post = $this->postModel->getPostById($id);
            $user = $this->userModel->getUserById($post->user_id);
            $data = [
                "post" => $post,
                "user" => $user
            ];
            $this->view("posts/show", $data);
        }
        // edit Method
        public function edit($id){
            if($_SERVER["REQUEST_METHOD"] == "POST" ){
                $data = [
                    "title" =>  trim($_POST["title"]),
                    "body" => trim($_POST["body"]),
                    "user_id" => $_SESSION["user_id"],
                    "id" => $id,
                    "title_err" => "",
                    "body_err" => ""
                ];
                // Validate title
                if(empty($data["title"])){
                    $data["title_err"] = "Enter Your Title.";
                }

                // Validate body
                if (empty($data["body"])) {
                    $data["body_err"] = "Enter The Body Of The Post.";
                }

                // Make Sure We haven't any error
                if(empty($data["title_err"]) && empty($data["body_err"])){
                    // Validated
                    if($this->postModel->updatePost($data)){
                        flash("post_message", "Post Updated");
                        redirect("posts");
                    }else {
                        die("Something Went Wrong, Try Again");
                    }
                }else {
                    // Load data With errors
                    $this->view("Posts/edit", $data);
                }
            }else {
                // Get Existing Post From Model
                $post = $this->postModel->getPostById($id);
                // Check For Owner
                if($post->user_id != $_SESSION["user_id"]){
                    redirect("posts");
                }
                $data = [
                    "id" => $id,
                    "title" => $post->title,
                    "body" => $post->body
                ];
                $this->view("Posts/edit", $data);   
            }
        }
        
        public function delete($id){
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $post = $this->postModel->getPostById($id);
                // Check For Owner
                if($post->user_id != $_SESSION["user_id"]){
                    redirect("posts");
                }
                if($this->postModel->deletePost($id)){
                    flash("post_message", "Post Removed");
                    redirect("posts");
                }else {
                    die("Something Went Wrong, Try Again.");
                }
            }else {
                redirect("posts");
            }
        }
    } 
?>