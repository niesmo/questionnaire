<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question extends CI_Controller {
    public function __construct(){
        parent::__construct();
    }
          
    public function index(){
        echo "index of question controller";
    }
          
    public function search($searchTerm = "", $questionnaire_id = ""){
        echo "search function question controller";
    }
          
    public function detail($qn_id, $q_id){
        $this->load->model("User_model", "MUser");
        $this->load->model("User_project_model", "MUserProject");
              
        $data = array();
        $question = new $this->Question_model($q_id);
        $questionnaire = new $this->Questionnaire_model($qn_id);
        $qq = new $this->Questionnaire_question_model($qn_id, $q_id);
        $user_id = $this->MUser->get_user_id();
              
        $viewCount = $qq->get_view_count(TRUE); // increase the count
        $selectedCount = $qq->get_selected_count();

        $data['question'] = $question;
        $data['questionnaire'] = $questionnaire;
        $data['otherQuestions'] = $questionnaire->get_other_questions($question->get_id());
        $data['viewCount'] = $viewCount;
        $data['selectedCount'] = $selectedCount;
        $data['qq_id'] = $qq->get_id();
        $data['user_id'] = $user_id;

        
        $project_id = $this->session->userdata("project_id");
        $user_qn_id = $this->session->userdata("user_qn_id");
        if(isset($project_id) && $project_id != "" && isset($user_qn_id) && $user_qn_id != ""){
            $data['quickAdd'] = TRUE;
            $data['project_id'] = $project_id;
            $data['user_qn_id'] = $user_qn_id;
            $data['qn_name'] = $this->session->userdata("qn_name");
            $data['project_name'] = $this->session->userdata("project_name");
        }
        else{
            $data['quickAdd'] = FALSE;
        }
               
        if(is_logged_in()){             
            $data['projects'] =  $this->MUserProject->get_projects();
        }
              
        $this->load->view("templates/head.php", array("title"=>"Detail for question '{$question->get_content()}'"));
        $this->load->view("templates/header.php");
        $this->load->view("question/detail.php", $data);
        $this->load->view("templates/footer.php");
    }
}

/* End of file Question.php */
/* Location: ./application/controllers/Question.php */
?>