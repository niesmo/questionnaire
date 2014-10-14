<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Question_model extends CI_Model{
    private $question_id;
    private $content;
    private $concept;
    private $scale;
    private $category_id;
    
    private $category_name;
    
    public function __construct($questionObj = NULL){
        parent::__construct();
        if(isset($questionObj) && $questionObj !== NULL){
            if(is_numeric($questionObj)){
                $this->set_question_by_id($questionObj);
            }
            elseif(is_array($questionObj)){
                $this->set_array($questionObj);
            }
            else{
                $this->set_object($questionObj);
            }
        }
        return $this;
    }
    
    public function get_id(){
        return $this->question_id;
    }

    public function get_question_id(){
        return $this->question_id;
    }
    
    public function set_id($id){
        $this->question_id = $id;
    }
    
    public function get_content($pretty = FALSE){
        return (isset($this->content)?$this->content:(($pretty)?"N/A":NULL));
    }
    public function get_concept($pretty = FALSE){
        return ($this->concept)?$this->concept:(($pretty)?"N/A":NULL);
    }
    public function get_scale($pretty = FALSE){
        return (isset($this->scale)||$this->scale === 0)?$this->scale:(($pretty)?"N/A":NULL);
    }
    
    public function get_category_name(){
        if(!isset($this->category_name) || $this->category_name == NULL){
            $this->category_name = $this->_get_category_name();
        }
        return $this->category_name;
    }
    
    public function get_category_id($pretty = FALSE){
        return ($this->category_id)?$this->category_id:(($pretty)?"N/A":NULL);
    }
    
    public function update(){
        $this->db->where("question_id", (int)$this->question_id);
        $updateArray = array(
            "content"=>$this->content,
            "concept"=>$this->concept,
            "scale"=>(int)$this->scale,
            "category_id"=>$this->category_id
        );
        return $this->db->update("question", $updateArray);
    }
    
    
    public function save($returnInsertID = FALSE){
        $insertArr = array(
            "content"=>$this->content,
            "concept"=>$this->concept,
            "scale"=>$this->scale,
            "category_id"=>$this->category_id
            );
        $result = $this->db->insert("question", $insertArr);
        if($returnInsertID)
            return $this->db->insert_id();
        return $result;
    }
    
    
    
    
    
    
    
    
    
    
    private function _get_category_name(){
        $this->db->select("name");
        $this->db->where("category_id", $this->category_id);
        $query = $this->db->get("category");
        return $query->row()->name;
    }
    
    private function toQuestionObj($arr){
        $questions = array();
        foreach($arr as $question){
            $questions[] = new Question_model($question);
        }
        return $questions;
    }
    
    private function set_array($questionObj){
        if(isset($questionObj['question_id']))      $this->question_id      = (int)$questionObj['question_id'];
        if(isset($questionObj['content']))          $this->content          = $questionObj['content'];
        if(isset($questionObj['concept']))          $this->concept          = $questionObj['concept'];
        if(isset($questionObj['scale']))            $this->scale            = (int)$questionObj['scale'];
        if(isset($questionObj['category_id']))      $this->category_id      = (int)$questionObj['category_id'];
        if(isset($questionObj['category_name']))    $this->category_name    = $questionObj['category_name'];
        return $this;
    }
    
    private function set_object($questionObj){
        if(isset($questionObj->question_id))    $this->question_id      = (int)$questionObj->question_id;
        if(isset($questionObj->content))        $this->content          = $questionObj->content;
        if(isset($questionObj->concept))        $this->concept          = $questionObj->concept;
        if(isset($questionObj->scale))          $this->scale            = (int)$questionObj->scale;
        if(isset($questionObj->category_id))    $this->category_id      = (int)$questionObj->category_id;
        if(isset($questionObj->category_name))  $this->category_name    = $questionObj->category_name;
        
        return $this;
    }
    
    private function set_question_by_id($id){
        $this->db->where("question_id", $id);
        $query = $this->db->get("question");
        $newQuestion = $query->result();
        $this->set_object($newQuestion[0]);
        return $this;
    }
}
?>