<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Questionnaire extends Admin_Controller{
    private $questionnaire;
    private $questions;
    
    public function __construct(){
        parent::__construct();
        $this->load->model("Questionnaire_question_model","MQQ");
        $this->load->model("Category_model","MCategory");
        
        $this->load->library(array("form_validation"));
        $this->form_validation->set_error_delimiters('<li class="list-group-item list-group-item-danger">', '</li>');
    }
    
    public function edit($qq_id){
        $data= array();
        $header = array();
        
        $qq = new $this->MQQ($qq_id);
        $this->questionnaire = $qq->get_questionnaire();
        $this->questions = $this->questionnaire->get_questions();
        $allCategories = $this->MCategory->get_all_categories();
        
        $data['questionnaire'] = $this->questionnaire;
        $data['questions'] = $this->questions;
        $data['categories'] = $allCategories;
        $data['qq_id'] = $qq_id;

        
        $editForm = $this->input->post();
        $this->set_questionnaire_form_rules();
        
        
        
        if(isset($editForm['edit_submit']) && $editForm['edit_submit'] == "Save Changes"){ // the form is submitted
            if($this->form_validation->run() == false){
                $data['editErrors'][] = validation_errors();
                $header['title']= "Invalid form";
            }
            // The form has been submitted and it passed the validations
            else{
                $newQuestionnaire = new Questionnaire_model($editForm);
                if($newQuestionnaire->update()){
                    $data['questionnaire'] = $newQuestionnaire;
                    $data['editSuccess'][] = "The question was successfully updated";
                    
                    $header['title'] = "Edit questionnaire '".character_limiter($newQuestionnaire->get_name(), 20)." | Admin ";
                }
                else{
                    $header['title'] = "Something went wrong | Edit Questionnaire | admin";
                    $data["editErrors"][] = "Something went wrong while updating the questionnaire! Please refresh try again";
                }
            }
        }
        else{
            $header['title'] = "Edit questionnaire '".character_limiter($this->questionnaire->get_name(), 20)." | Admin ";
        }
        
        
        
        $this->load->view("templates/head.php", $header);
        $this->load->view("templates/header.php");
        $this->load->view("questionnaire/admin/edit.php", $data);
        $this->load->view("templates/footer.php");
    }
    
    
    public function create(){
        $header = array("Title"=>"Create New Questionnaire");
        $data = array();
        $form = $this->input->post();
        
        $this->set_questionnaire_form_rules();
        
        if(isset($form['create_submit']) && $form['create_submit'] == "Create Questionnaire"){
            if($this->form_validation->run() == false){
                $data['createErrors'][] = validation_errors();
                $header['title']= "Invalid form";
            }
            else{
                $newQuestionnaire = new Questionnaire_model($form);
                $qn_id = $newQuestionnaire->save(TRUE);
                if($qn_id > 0){
                    $data['createSuccess'][] = "The questionnaire was successfully created";
                    $data['qn_id'] = $qn_id;
                }
                else{
                    $data['createErrors'][] = "Something went wrong! Please refresh and try again";
                }
            }
        }
        
        $this->load->view("templates/head.php", $header);
        $this->load->view("templates/header.php");
        $this->load->view("questionnaire/admin/create.php", $data);
        $this->load->view("templates/footer.php");
    }
    
    
    
    
    
    
    
    
    
    private function set_questionnaire_form_rules(){
        $this->form_validation->set_rules('name', 'Name', 'required|max_length[255]');
        $this->form_validation->set_rules('author', 'Author', 'required|max_length[45]');
        $this->form_validation->set_rules('year', 'Year', 'required|integer|exact_length[4]');
    }
}
?>