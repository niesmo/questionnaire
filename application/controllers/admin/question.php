<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question extends Admin_Controller{
    private $question;
    
    public function __construct(){
        parent::__construct();
        $this->load->model("Questionnaire_question_model","MQQ");
        $this->load->model("Category_model","MCategory");
        
        $this->load->library(array("form_validation"));
        $this->form_validation->set_error_delimiters('<li class="list-group-item list-group-item-danger">', '</li>');
    }
    
    public function index(){
        echo "<h1>Question Admin Index</h1>";
    }
    
    public function edit($qq_id){
        $data= array();
        $header = array();
        
        $qq = new $this->MQQ($qq_id);
        $this->question = $qq->get_question();
        $allCategories = $this->MCategory->get_all_categories();
        
        
        $data['question'] = $this->question;
        $data['categories'] = $allCategories;
        $data['qq_id'] = $qq_id;

        
        $editForm = $this->input->post();
        $this->set_question_form_rules();
        
        if(isset($editForm['edit_submit']) && $editForm['edit_submit'] == "Save Changes"){ // the form is submitted
            if($this->form_validation->run() == false){
                $data['editErrors'][] = validation_errors();
                $header['title']= "Invalid form";
            }
            // The form has been submitted and it passed the validations
            else{
                $newQuestion = new Question_model($editForm);
                if($newQuestion->update()){
                    $data['question'] = $newQuestion;
                    $data['editSuccess'][] = "The question was successfully updated";
                    
                    $header['title'] = "Edit question '".character_limiter($newQuestion->get_content(), 20)." | Admin ";
                }
                else{
                    $header['title'] = "Something went wrong | Edit Question | admin";
                    $data["editErrors"][] = "Something went wrong while updating the question! Please refresh try again";
                }
            }
        }
        else{
            $header['title'] = "Edit question '".character_limiter($this->question->get_content(), 20)." | Admin ";
        }
        
        
        
        $this->load->view("templates/head.php", $header);
        $this->load->view("templates/header.php");
        $this->load->view("question/admin/edit.php", $data);
        $this->load->view("templates/footer.php");
    }
    
    
    public function create($questionnaire_id = NULL){
        $header = array("title"=>"Create New Question");
        $data = array();
        $data['categories'] = $this->MCategory->get_all_categories();
        
        if(!isset($questionnaire_id) || $questionnaire_id == NULL){
            $data['questionnaires'] = $this->Questionnaire_model->get_all_questionnaires();
            $data['hasQuestionnaire'] = FALSE;
        }
        else{
            $data['questionnaires'][] = new $this->Questionnaire_model($questionnaire_id);
            $data['qn_id'] = $questionnaire_id;
            $data['hasQuestionnaire'] = TRUE;
        }
        
        $form = $this->input->post();
        $this->set_question_form_rules();
        
        if(isset($form['create_submit']) && $form['create_submit'] == "Create Question"){
            if($this->form_validation->run() == false){                
                $data['createErrors'][] = validation_errors();
                $header['title']= "Invalid Question";
            }
            else{
                $newQuestion = new Question_model($form);
                $newQuestionID = $newQuestion->save(TRUE);
                if($newQuestionID > 0){
                    //make a connection between this and the questionnaire that the user
                    //has selected as the parent of this question.
                    $qn_id = ($data['hasQuestionnaire'])?(int)$questionnaire_id:(int)$form['qn_id'];
                    
                    $questionnaireQuestion = new $this->Questionnaire_question_model($qn_id, $newQuestionID);
                    //var_dump($questionnaireQuestion);
                    if($questionnaireQuestion->connect()){
                        $data['createSuccess'][] = "The question was successfully created and connected to the questionnaire";
                    }
                    else{
                        $data['createErrors'][] = "Something went wrong! Please try again";
                        $header['title']= "Something went wrong";
                    }
                }
                else{
                    $data['createErrors'][] = "Something went wrong! Please try again";
                    $header['title']= "Something went wrong";
                }
            }
        }
        
        
        
        
        $this->load->view("templates/head.php", $header);
        $this->load->view("templates/header.php");
        $this->load->view("question/admin/create.php", $data);
        $this->load->view("templates/footer.php");
    }
    
    
    
    
    private function set_question_form_rules(){
        $this->form_validation->set_rules('content', 'Content', 'required');
        $this->form_validation->set_rules('concept', 'Concept', 'required|max_length[255]');
        $this->form_validation->set_rules('scale', 'Scale', 'integer|max_length[11]');
        $this->form_validation->set_rules('category_id', 'Category', "required");
    }
}
?>