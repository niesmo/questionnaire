<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_question_model extends CI_Model {
    private $user_question_id;
    private $content;
    private $questionnaire_id;
    private $question_id;
    private $created_by;
    private $creation_date;
    
    private $questionnaire;
    private $question;
    
    
    public function __construct($userQuestionObj = NULL){
        parent::__construct();
        
        if(isset($userQuestionObj) && $userQuestionObj !== NULL)
        {
            if(is_numeric($userQuestionObj)){
                $this->set_user_question_by_id($userQuestionObj);
            }
            elseif(is_array($userQuestionObj))
            {
                $this->set_array($userQuestionObj);
            }
            else
            {
                $this->set_object($userQuestionObj);   
            }
        }
    }
    
    public function get_id(){
        return $this->user_question_id;
    }
    
    public function get_content(){
        return $this->content;
    }

    public function get_question_id(){
        return $this->question_id;
    }
    
    public function get_creation_date(){
        return $this->creation_date;
    }
    
    public function get_questionnaire_id(){
        return $this->questionnaire_id;
    }    
    public function get_user_name(){
        $this->db->select("firstName, lastName");
        $this->db->where("user_id" , $this->created_by);
        $query = $this->db->get("user");
        $result = $query->row();
        return $result->firstName . " " . $result->lastName;
    }
    
    public function get_questionnaire_name(){
        $this->db->select("name");
        $this->db->where("user_questionnaire_id" , $this->questionnaire_id);
        $query = $this->db->get("user_questionnaire");
        $result = $query->row();
        return $result->name;
    }
    
    
    
    public function save($orig_qn_id = NULL){
        //increase the select count of the question
        if(isset($orig_qn_id)){
            $qq = new $this->Questionnaire_question_model($orig_qn_id, $this->question_id);
            $qq->increment_selected_count();
        }
        
        
        $insertData = array(
            "content"=>$this->content,
            "question_id"=>$this->question_id,
            "questionnaire_id"=>$this->questionnaire_id,
            "created_by"=>$this->created_by
        );
        
        return $this->db->insert("user_question",$insertData);
    }
    
    
    public function update_content($newContent){
        // we are going to keep track of all the changes
        // that each user makes
        $user_id = $this->session->userdata("user_id");
        $insertArr = array(
            "content"=>$newContent,
            "question_id" =>$this->user_question_id,
            "modified_by" =>$user_id
            );
        $this->db->insert("user_question_changes", $insertArr);
        
        
        $this->db->set("content", $newContent);
        $this->db->set("modified_by" , $user_id);
        $this->db->where("user_question_id", $this->user_question_id);
        return $this->db->update("user_question");
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
       
    
    private function set_array($userQuestionObj){
        if(isset($userQuestionObj['user_question_id'])) $this->user_question_id = (int)$userQuestionObj['user_question_id'];
        if(isset($userQuestionObj['content']))          $this->content          = $userQuestionObj['content'];
        if(isset($userQuestionObj['questionnaire_id']))       $this->questionnaire_id       = (int)$userQuestionObj['questionnaire_id'];
        if(isset($userQuestionObj['question_id']))      $this->question_id      = (int)$userQuestionObj['question_id'];
        if(isset($userQuestionObj['created_by']))       $this->created_by       = (int)$userQuestionObj['created_by'];
        if(isset($userQuestionObj['creation_date']))       $this->creation_date    = $userQuestionObj['creation_date'];
        
    }
    
    private function set_object($userQuestionObj){
        if(isset($userQuestionObj->user_question_id))   $this->user_question_id =   (int)$userQuestionObj->user_question_id;
        if(isset($userQuestionObj->content))            $this->content          =   $userQuestionObj->content;
        if(isset($userQuestionObj->questionnaire_id))         $this->questionnaire_id       =   (int)$userQuestionObj->questionnaire_id;
        if(isset($userQuestionObj->question_id))        $this->question_id      =   (int)$userQuestionObj->question_id;
        if(isset($userQuestionObj->created_by))         $this->created_by       =   (int)$userQuestionObj->created_by;
        if(isset($userQuestionObj->creation_date))         $this->creation_date       =   $userQuestionObj->creation_date;
    }
    
    private function set_user_question_by_id($uq_id){
        $this->db->where("user_question_id" , $uq_id);
        $query = $this->db->get("user_question");
        $result = $query->row();
        $this->set_object($result);
    }
}
