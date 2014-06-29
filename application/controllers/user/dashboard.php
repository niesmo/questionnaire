<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends User_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load->model("User_model" , "MUser");
        $this->load->model("User_project_model", "MUserProject");        
    }
    
    //user dashboard
    public function index(){
        $header = array();
        $data = array();
        
        $header['title'] = "User Dashboard for {$this->MUser->get_username()}";

        $projects = $this->MUserProject->get_projects();
        $defaultProject_id = $this->MUser->get_default_project_id();
        
        $data['projects'] = $projects;
        $data['defaultProject_id'] = $defaultProject_id;
        
        $this->load->view("templates/head.php", $header);
        $this->load->view("templates/header.php");
        $this->load->view("user/dashboard.php", $data);
        $this->load->view("templates/footer.php");
    }
}
?>