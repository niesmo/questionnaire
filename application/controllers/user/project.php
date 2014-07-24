<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project extends User_Controller{
    
    
    public function __construct(){
        parent::__construct();
        $this->load->model("Project_model", "MProject");
        
    }
    
    public function index(){
        echo "This is the index of the project controller";
    }
    
    
    public function detail($project_id){
        //TODO
        //We need to check if the project that they are
        //trying to access belogs to that the person who
        //they claim they are.
        if(!isset($project_id))
            redirect("user/dashboard");
        
        $header = array();
        $data = array();
        
        $project = new $this->MProject($project_id);
        if(!$project->is_allowed()){
            echo "You dont have access to this project! Please go to the home page and try again!";
            return;
        }
        
        $questionnaires = $project->get_questionnaire();
        $collaborators = $project->get_collaborators();
        $allProjects = $project->get_all_my_projects();

        
        $data['project'] = $project;
        $data['questionnaires'] = $questionnaires;
        $data['collaborators'] = $collaborators;
        $data['allProjects'] = $allProjects;
        
        
        
        $header['title'] = "Project {$project->get_name()} Details";
        
        $this->load->view("templates/head.php",$header);
        $this->load->view("templates/header.php");
        $this->load->view("user/project/detail.php", $data);
        $this->load->view("templates/footer.php");
        
    }
}

?>
