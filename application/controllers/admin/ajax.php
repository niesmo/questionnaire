<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends Admin_Controller{
    public function __construct(){
        parent::__construct();
        $this->output->enable_profiler(FALSE);
    }
    
    public function get_question_info($q_id){
        $question = new $this->Question_model($q_id);
        
        echo "content:" .$question->get_content() . ":content ";
        echo "concept:" .$question->get_concept() . ":concept ";
        echo "scale:" .$question->get_scale() . ":scale ";
        echo "category_id:" .$question->get_category_id() . ":category_id";
    }
    
    public function question_update(){
        $formInfo = $this->input->post();
        $errors = "";
        if(strlen($formInfo['content']) == 0 ){
            $errors .= "Content field is required, ";
        }
        if(strlen($formInfo['concept']) == 0){
            $errors .= "Concept field is required, ";
        }
        if(strlen($formInfo['concept']) > 255){
            $errors .= "Concept field must be 255 characters long or shorter, ";
        }
        if($errors == ""){
            $question = new $this->Question_model($formInfo);
            echo $question->update();
        }
        else{
            echo rtrim($errors,", ");
        }
        
    }
    
}