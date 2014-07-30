<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
    public function __construct(){
        parent::__construct();
        
        $this->load->model("user_model" , "MUser");

        //Native libraries
        $this->load->library(array("form_validation"));
        $this->form_validation->set_error_delimiters('<li class="list-group-item list-group-item-danger">', '</li>');

        //Exception libraries
        $this->load->library("Login_fail");
        $this->load->library("Exception_user_insertion_failed");


    }
    
	public function index(){
        if(is_logged_in()){
            redirect("/");
        }

        $data = array();
        $hearder = array();
        
        $header['title'] = "Login | Sign up";
        
        //rendering the views
        $this->load->view('templates/head.php', $header);
        $this->load->view('templates/header.php');
        $this->load->view('auth/index.php');
        $this->load->view('templates/footer.php');    
        
        
	}
    

    
    public function authenticate(){
        if(is_logged_in()){
            redirect("/");
        }

        $data = array();
        $header = array();
        
        $loginForm = $this->input->post();
        
        $this->set_login_form_rules();
        if($this->form_validation->run() == false){
            $data['loginErrors'][] = validation_errors();
            $header['title']= "Invalid form";
        }
        else{
            $user = new $this->MUser($loginForm);
            try{
                $user->login();
                $uri = urldecode($this->input->server('QUERY_STRING'));
                $uri = str_replace("location=/questionnaire", "", $uri);
                
                //This line is added for using the localhost
                //$uri = str_replace("/questionnaire", "" , $uri);
                
                $uri = str_replace("/index.php", "", $uri);
                redirect($uri, 'refresh');
            }
            catch (Login_fail $exception)
            {
            	$data['loginErrors'][]= "<li class='list-group-item list-group-item-danger'>Username or password is incorrect</li>";
                $header['title']= "Login failed";
            }
        }
        
        $this->load->view('templates/head.php', $header);
        $this->load->view('templates/header.php');
        $this->load->view('auth/index.php', $data);
        $this->load->view('templates/footer.php');
    }
    
    public function sign_up(){
        $signUpForm = $this->input->post();
        $this->set_signup_form_rules();
        if($this->form_validation->run() == false){
            $data['signupErrors'][] = validation_errors();
            $header['title']= "Invalid form";
        }
        else{
            $user = new $this->MUser($signUpForm);
            
            try{
                $user->signup();
                redirect('/template/', 'refresh');
            }
            catch (Exception_user_exist $e)
            {
                $data['signupErrors'][]= "<li class='list-group-item list-group-item-danger'>Your email already exist</li>";
            }
            catch(Exception_user_insertion_failed $e){
                $data['signupErrors'][]= "<li class='list-group-item list-group-item-danger'>Something went wrong when inserting your email. check the debugging tools open . . . </li>";
            }
        }
        
        $this->load->view('templates/head.php', $header);
        $this->load->view('templates/header.php');
        $this->load->view('auth/index.php', $data);
        $this->load->view('templates/footer.php');
       
    }
    
    public function logout(){
        $this->MUser->wipe_login_session();
        redirect('/template/', 'refresh');
    }
    
    
    
    
    
    private function set_login_form_rules(){
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
    }
    
    private function set_signup_form_rules(){
        $this->form_validation->set_message('is_unique', 'This email address already exist');
        
        $this->form_validation->set_rules('firstName', 'First Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('lastName', 'Last Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[user.email]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|matches[re-password]|md5');
        $this->form_validation->set_rules('re-password', 'Password Confirmation', 'required');
    }
}

/* End of file login.php */
/* Location: ./application/controllers/auth/login.php */