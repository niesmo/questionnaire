<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_questionnaire_model extends CI_Model {
    private $user_questionnaire_id;
    private $name;
    
    private $created_by;
    private $creation_date;
    private $project_id;
    
    private $questions;
    
    public function __construct($userQuestionnaireObj = NULL){
        parent::__construct();
        
        if(isset($userQuestionnaireObj) && $userQuestionnaireObj !== NULL)
        {
            if(is_numeric($userQuestionnaireObj)){
                $this->set_user_questionnaire_by_id($userQuestionnaireObj);
            }
            elseif(is_array($userQuestionnaireObj))
            {
                $this->set_array($userQuestionnaireObj);
            }
            else
            {
                $this->set_object($userQuestionnaireObj);   
            }
        }
    }
    
    public function get_name(){
        return $this->name;
    }
    
    public function get_id(){
        return $this->user_questionnaire_id;
    }
    
    public function get_creation_date(){
        return $this->creation_date;
    }
    
    public function get_questions(){
        if(!isset($this->questions) || $this->questions == NULL){
            $this->questions = $this->set_questions();
        }
        return $this->questions;
    }
    
    public function is_allowed(){
        $user_id = $this->session->userdata("user_id");
        //SELECT * FROM user_project as up, user_questionnaire as uqn WHERE uqn.project_id = up.project_id AND uqn.user_questionnaire_id = 23 AND up.user_id = 4;
        $this->db->from("user_project as up");
        $this->db->join("user_questionnaire as uqn", "uqn.project_id = up.project_id");
        $this->db->where("uqn.user_questionnaire_id" , $this->user_questionnaire_id);
        $this->db->where("up.user_id" , $user_id);
        return $this->db->count_all_results()==1;
    }
    
    public function get_user_name(){
        $this->db->select("firstName, lastName");
        $this->db->where("user_id" , $this->created_by);
        $query = $this->db->get("user");
        $result = $query->row();
        return $result->firstName . " " . $result->lastName;
    }
    
    public function get_project_name(){
        $this->db->select("name");
        $this->db->where("project_id" , $this->project_id);
        $query = $this->db->get("project");
        $result = $query->row();
        return $result->name;
    }
    
    public function remove(){
        //this function works very similar to the remove function
        //in the project model
        
        $this->db->where("user_questionnaire_id" , $this->user_questionnaire_id);
        return $this->db->delete("user_questionnaire");
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    private function set_questions(){
        $this->db->select("q.*");
        $this->db->from("user_questionnaire as qn");
        $this->db->join("user_question as q", "q.questionnaire_id = qn.user_questionnaire_id");
        $this->db->where("qn.user_questionnaire_id" , $this->user_questionnaire_id);
        
        $query = $this->db->get();

        $questions = $this->result_to_question_objects($query->result());
        return $questions;
    }
    
    
    private function result_to_question_objects($queryResult){
        $questions = array();
        foreach($queryResult as $question){
            $questions[] = new $this->MUQuestion($question);
        }
        return $questions;
        
    }
    
    
    private function set_array($userQuestionnaireObj){
        if(isset($userQuestionnaireObj['user_questionnaire_id'])) $this->user_questionnaire_id = (int)$userQuestionnaireObj['user_questionnaire_id'];
        if(isset($userQuestionnaireObj['name']))          $this->name          = $userQuestionnaireObj['name'];
        if(isset($userQuestionnaireObj['project_id']))       $this->project_id       = (int)$userQuestionnaireObj['project_id'];
        if(isset($userQuestionnaireObj['created_by']))       $this->created_by       = (int)$userQuestionnaireObj['created_by'];
        if(isset($userQuestionnaireObj['creation_date']))       $this->creation_date       = $userQuestionnaireObj['creation_date'];
        
    }
    
    private function set_object($userQuestionnaireObj){
        if(isset($userQuestionnaireObj->user_questionnaire_id))   $this->user_questionnaire_id =   (int)$userQuestionnaireObj->user_questionnaire_id;
        if(isset($userQuestionnaireObj->name))            $this->name          =   $userQuestionnaireObj->name;
        if(isset($userQuestionnaireObj->project_id))         $this->project_id       =   (int)$userQuestionnaireObj->project_id;
        if(isset($userQuestionnaireObj->created_by))         $this->created_by       =   (int)$userQuestionnaireObj->created_by;
        if(isset($userQuestionnaireObj->creation_date))        $this->creation_date      =   $userQuestionnaireObj->creation_date;
    }
    
    private function set_user_questionnaire_by_id($uq_id){
        $this->db->where("user_questionnaire_id" , $uq_id);
        $query = $this->db->get("user_questionnaire");
        $result = $query->row();
        $this->set_object($result);
    }
}
?>