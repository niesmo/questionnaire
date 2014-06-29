<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class User_Controller extends MY_Controller
{
    private $user_id;
    public function __construct()
    {
        parent::__construct();
        $this->load->model("User_question_model","MUQuestion");
        $this->load->model("User_questionnaire_model","MUQuestionnaire");
        
        //check if the user is logged in and is an admin
        if(!is_logged_in()){
            redirect("auth/login?location=".urlencode($_SERVER['REQUEST_URI']),"refresh");
        }
        else{
            $this->user_id = $this->session->userdata("user_id");
        }
        
        
        //$this->output->enable_profiler(TRUE);
    }
    
    public function get_user_id(){
        return $this->user_id;
    }
}
?>