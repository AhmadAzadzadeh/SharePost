<?php 
    class Users extends Controller {
        public function __construct(){
            $this->userModel = $this->model("User");
        }

        public function register(){
            // Check for POST
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Process Form
                $data = [
                    "name" => trim($_POST["name"]),
                    "email" => trim($_POST["email"]),
                    "password" => trim($_POST["password"]),
                    "confirm_password" => trim($_POST["confirm_password"]),
                    "name_err" => "",
                    "email_err" => "",
                    "password_err" => "",
                    "confirm_password_err" => ""
                ];

                // Validate Name => 
                if(empty($data["name"])){
                    $data["name_err"] = "Please Enter Your Name.";
                }

                // Validate Email =>
                if(empty($data["email"])){
                    $data["email_err"] = "Please Enter Your Email.";
                }elseif($this->userModel->findUserByEmail($data["email"])){
                    $data["email_err"] = "Email Already Taken.";
                }

                // Validate Password
                if(empty($data["password"])){
                    $data["password_err"] = "Please Enter Your Password.";
                }elseif (strlen($data["password"]) < 6) {
                   $data["password_err"] = "Your Password Must Be At Least 6 Character.";
                }

                // Validate Confirm Password
                if(empty($data["confirm_password"])){
                    $data["confirm_password_err"] = "Please Enter Your Confirm Password.";
                }elseif ($data["password"] != $data["confirm_password"]) {
                    $data["confirm_password_err"] = "Your Password And ConfirmPassword Must Be Equal To Each Other.";
                }

                // Make Sure Errors Are Empty
                if (empty($data["name_err"]) && empty($data["email_err"]) && empty($data["password_err"]) && empty($data["confirm_password_err"])) {
                    // Validated
                    // Hash Password
                    $data["password"] = password_hash($data["password"], PASSWORD_DEFAULT);
                    // Register User
                    if ($this->userModel->register($data)) {
                        flash("register_success", "You Are Successfully Registered And Can Login");
                        redirect("/users/login");
                    }else {
                        die("Something Went Wrong, Try Again...");
                    }

                }else {
                    $this -> view("/Users/register", $data);
                }

            } else {
                // Load Form
                $data = [
                    "name" => "",
                    "email" => "",
                    "password" => "",
                    "confirm_password" => "",
                    "name_err" => "",
                    "email_err" => "",
                    "password_err" => "",
                    "confirm_password_err" => ""
                ];
                // Load View
                $this -> view("Users/register", $data);
            }
        }

        public function login(){
             // Check for POST
             if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Process Form
                $data = [
                    "email" => trim($_POST["email"]),
                    "password" => trim($_POST["password"]),
                    "email_err" => "",
                    "password_err" => ""
                ];

                // Validate Name => 
                if(empty($data["email"])){
                    $data["email_err"] = "Please Enter Your Email.";
                }else {
                    if($this->userModel->findUserByEmail($data["email"])) {
                        // User Found

                    }else {
                        $data["email_err"] = "User Not Found.";
                    }
                }

                // Validate Password
                if(empty($data["password"])){
                    $data["password_err"] = "Please Enter Your Password.";
                }elseif (strlen($data["password"]) < 6) {
                   $data["password_err"] = "Your Password Must Be At Least 6 Character.";
                }

                // Make Sure Errors Are Empty
                if (empty($data["email_err"]) && empty($data["password_err"])) {
                    // Validated
                    // Check And Set LoggedIn User
                    $loggedInUser = $this->userModel->login($data["email"], $data["password"]);

                    if($loggedInUser){
                        // Create Session
                        $this->createUserSession($loggedInUser);
                    }else {
                        $data["password_err"] = "Your Password is Wrong.";
                        $this->view("/Users/login", $data);
                    }
                }else {
                    $this -> view("/Users/login", $data);
                }
            }else {
                // Load Form
                $data = [
                    "email" => "",
                    "password" => "",
                    "email_err" => "",
                    "password_err" => ""
                ];
                // Load View
                $this -> view("Users/login", $data);
            }
        }

        public function createUserSession($user){
            $_SESSION["user_id"] = $user->id;
            $_SESSION["user_name"] = $user->name;
            $_SESSION["user_email"] = $user->email;
            redirect("Posts");
        }

        public function logout(){
            unset($_SESSION["user_id"]);
            unset($_SESSION["user_name"]);
            unset($_SESSION["user_email"]);
            session_destroy();
            redirect("Users/login");
        }
    }
    ?>