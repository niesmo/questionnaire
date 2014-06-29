<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question extends User_Controller{
    
    public function __construct(){
        parent::__construct();
        
    }
    
    public function index(){
        echo "This is the index of the question controller";
    }
    
    
    public function detail($uq_id){
        // TODO 
        // We need to check if the user if looking at the question that they have access to 
        // and if not, redirect them somewhere else!!!!!
        $data = array();
        $header = array("title"=>"User Question Detail");

        $this->load->model("User_question_change_model", "MUQuestionChange");
        
        $question = new $this->MUQuestion($uq_id);
        $questionHistory = $this->MUQuestionChange->get_question_history($question->get_id());
        
        $data['question'] = $question;
        $data['questionHistory'] = $questionHistory;
        
        $this->load->view("templates/head.php" , $header);
        $this->load->view("templates/header.php");
        $this->load->view("user/question/detail.php", $data);
        $this->load->view("templates/footer.php");
    }
}
?>