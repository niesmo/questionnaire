<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Questionnaire extends User_Controller{
    private $questionnaire;
    
    public function __construct(){
        parent::__construct();
        
    }
    
    public function index(){
        echo "This is the index of the user questionnaire controller";
    }
    
    
    public function detail($qn_id){
        //TODO
        //We need to check if the person looking at this page is a collaberator in this project or no
        //if not we need to redirect them to some other place
        
        $data = array();
        
        $questionnaire = new $this->MUQuestionnaire($qn_id);
        if(!$questionnaire->is_allowed()){
            echo "You dont have access to this page! Please go to the home page and try again!";
            return;
        }
        
        
        $questionnaire->get_questions();
        $data['questionnaire'] = $questionnaire;
        
        
        $header = array("title"=>"Questionnaire Details for " . character_limiter($questionnaire->get_name(), 20));
        $this->load->view("templates/head.php", $header);
        $this->load->view("templates/header.php");
        $this->load->view("user/questionnaire/detail.php", $data);
        $this->load->view("templates/footer.php");
    }
}
?>