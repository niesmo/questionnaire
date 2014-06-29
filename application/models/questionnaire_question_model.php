<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Questionnaire_question_model extends CI_Model{
    private $questionnaire_question_id;
    private $questionnaire_id;
    private $question_id;
    private $view_count;
    private $selected_count;
    
    private $question;
    private $questionnaire;
    
    public function __construct($qqObj = NULL, $q_id=NULL){
        parent::__construct();
        if(isset($qqObj) && $qqObj != NULL){
            if(is_numeric($qqObj)){ //They are passing the questionnaire_id and question_id
                //check if the question id is also set or not!
                if(isset($q_id) && $q_id != NULL && is_numeric($q_id)){
                    //set the private variables
                    $this->question_id = $q_id;
                    $this->questionnaire_id = $qqObj;
                }
                //they are only sending the actual id of qq table primary key
                else{
                    $this->questionnaire_question_id = $qqObj;
                }
            }
            elseif(is_array($qqObj)){
                $this->set_array($qqObj);
            }
            else{
                $this->set_object($qqObj);
            }
        }
    }
    
    public function get_id(){
        if(!isset($this->questionnaire_question_id) || $this->questionnaire_question_id == NULL){
            $this->_set_all_info();
        }
        return $this->questionnaire_question_id;
    }
    
    public function get_view_count($increment = FALSE){
        if(!isset($this->view_count) || $this->view_count == NULL){
            $this->_set_all_info();
        }
        if($increment){
            $this->increment_view_count();
            $this->view_count++;
        }
        return $this->view_count;
    }
    
    public function increment_view_count(){
        $this->db->set("view_count",  "(`view_count`+1)", FALSE);
        if(isset($this->questionnaire_question_id) && $this->questionnaire_question_id != NULL){
            $this->db->where("questionnaire_question_id = {$this->questionnaire_question_id}" );
        }
        else{
            $this->db->where("questionnaire_id", $this->questionnaire_id );
            $this->db->where("question_id", $this->question_id);
        }
        $this->db->update("questionnaire_question");
    }
    
    public function get_selected_count($increment=FALSE){
        if(!isset($this->selected_count) || $this->selected_count == NULL){
            $this->_set_all_info();
        }
        if($increment){
            $this->increment_selected_count();
            $this->selected_count++;
        }
        return $this->selected_count;
    }
    
    public function increment_selected_count($questionnaire_id = NULL){
        
        $this->db->set("selected_count",  "(`selected_count`+1)", FALSE);
        if(isset($this->questionnaire_question_id) && $this->questionnaire_question_id != NULL){
            $this->db->where("questionnaire_question_id = {$this->questionnaire_question_id}" );
        }
        else{
            $this->db->where("questionnaire_id", $this->questionnaire_id );
            $this->db->where("question_id", $this->question_id);
        }
        
        $this->db->update("questionnaire_question");
    }
    
    
    public function get_question(){
        if(!isset($this->question) || $this->question == NULL){
            $this->_set_question();
        }
        return $this->question;
    }
    
    public function get_questionnaire(){
        if(!isset($this->questionnaire) || $this->questionnaire == NULL){
            $this->_set_questionnaire();
        }
        return $this->questionnaire;
    }    
    
    public function connect(){
        $insertArr = array(
            "questionnaire_id"=>$this->questionnaire_id,
            "question_id"=>$this->question_id
            );
        return $this->db->insert("questionnaire_question", $insertArr);
    }
    
    
    
    
    
    
    
    
    
    
    private function _set_questionnaire(){
        $this->db->select("qn.*, qq.*");
        $this->db->from("questionnaire_question as qq");
        $this->db->join("questionnaire as qn", "qn.questionnaire_id = qq.questionnaire_id");
        $this->db->where("qq.questionnaire_question_id" , $this->questionnaire_question_id);
        $query = $this->db->get();
        $resultRow = $query->row();
        
        $this->questionnaire = new $this->Questionnaire_model($resultRow);
        $this->set_object($resultRow);
        return $this->questionnaire;
    }
    
    
    
    private function _set_question(){        
        $this->db->select("q.*, qq.*");
        $this->db->from("questionnaire_question as qq");
        $this->db->join("question as q", "q.question_id = qq.question_id");
        $this->db->where("qq.questionnaire_question_id" , $this->questionnaire_question_id);
        $query = $this->db->get();
        $resultRow = $query->row();
        
        $this->question = new $this->Question_model($resultRow);
        $this->set_object($resultRow);
        return $this->question;
    }
    
    
    private function _set_all_info(){
        $this->db->where("question_id", $this->question_id);
        $this->db->where("questionnaire_id", $this->questionnaire_id);
        $query = $this->db->get("questionnaire_question");
        $this->set_object($query->row());
        return $this;
    }
    
    private function set_array($qqObj){
        if(isset($qqObj['questionnaire_question_id']))  $this->questionnaire_question_id    = $qqObj['questionnaire_question_id'];
        if(isset($qqObj['question_id']))                $this->question_id                  = $qqObj['question_id'];
        if(isset($qqObj['questionnaire_id']))           $this->questionnaire_id             = $qqObj['questionnaire_id'];
        if(isset($qqObj['view_count']))                 $this->view_count                   = $qqObj['view_count'];
        if(isset($qqObj['selected_count']))             $this->selected_count               = $qqObj['selected_count'];
        
        return $this;
    }
    
    private function set_object($qqObj){
        if(isset($qqObj->questionnaire_question_id))    $this->questionnaire_question_id    = $qqObj->questionnaire_question_id;
        if(isset($qqObj->question_id))                  $this->question_id                  = $qqObj->question_id;
        if(isset($qqObj->questionnaire_id))             $this->questionnaire_id             = $qqObj->questionnaire_id;
        if(isset($qqObj->view_count))                   $this->view_count                   = $qqObj->view_count;
        if(isset($qqObj->selected_count))               $this->selected_count               = $qqObj->selected_count;
        return $this;
    }
}
?>