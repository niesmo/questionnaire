<?php
class Questionnaire_model extends CI_Model{
    private $questionnaire_id;
    private $name;
    private $author;
    private $year;
    private $status;
    
    private $questions;
    
    public function __construct($questionnaireObj = NULL){
        parent::__construct();
        if(isset($questionnaireObj) && $questionnaireObj !== NULL){
            
            if(is_numeric($questionnaireObj)){
                $this->set_questionnaire_by_id($questionnaireObj);
            }
            
            elseif(is_array($questionnaireObj)){
                $this->set_array($questionnaireObj);
            }
            
            else{
                $this->set_object($questionnaireObj);   
            }
        }
        return $this;
    }
    
    public function get_name(){
        return $this->name;
    }
    public function get_id(){
        return $this->questionnaire_id;
    }
    public function get_year(){
        return $this->year;
    }
    public function get_author(){
        return $this->author;
    }
    
    
    public function get_questions(){
        if(!isset($this->questions) || $this->questions == NULL){
            $this->questions = $this->_get_questions();
        }
        return $this->questions;
    }
    
    public function get_other_questions($q_id){
        $i = 0;
        $allQuestions = $this->get_questions();
        foreach($allQuestions as $question){
            if($question->get_id() == $q_id){
                unset($allQuestions[$i]);
            }
            $i++;
        }
        return $allQuestions;
    }
    
    public function get_all_questionnaires(){
        $questionnaires = array();
        $this->db->order_by("year", "DESC");
        $query = $this->db->get("questionnaire");
        $questionnaires = $this->to_questionnaire_obj($query->result());
        return $questionnaires;
    }
    
    public function search($searchTerm, $field="all", $searchYear=NULL){

        $whereClause = "(`author` LIKE '%". $this->db->escape_like_str($searchTerm) ."%'";

        //$this->db->like("author", $searchTerm);
        if(strtolower($field) == "all"){
            $whereClause .= " OR `name` LIKE '%". $this->db->escape_like_str($searchTerm) ."%'";
            //$this->db->or_like("name", $searchTerm);
        }
        $whereClause .= " )";


        if($searchYear != NULL && $searchYear != -1){
            $whereClause .= " AND (`year` = ".$this->db->escape_str($searchYear).")";
            //$this->db->where("(`year` = {$searchYear})");
        }

        $this->db->where($whereClause);
        $this->db->order_by("year", "DESC");
        $query = $this->db->get("questionnaire");
        $questionnaires = $this->to_questionnaire_obj($query->result());
        return $questionnaires;
    }
    
    public function qsearch($searchTerm){
        $this->setup_join_questionnaire_question();
        $this->db->where("( `content` LIKE '%{$searchTerm}%' OR `concept` LIKE '%{$searchTerm}%' )");

        /*$this->db->like("content" , $searchTerm);
        $this->db->or_like("concept" , $searchTerm);*/

        $query = $this->db->get();
        
        $questions = $this->result_to_question_objects($query->result());
        return $questions;
    }

    public function advance_search($searches){
        $whereClause = "";
        if(isset($searches['filter'])){
            /*if(isset($searches['filter']['year']) && $searches['filter']['year'] == -1){
                unset($searches['filter']['year']);
            }*/

            foreach($searches['filter'] as $filter=>$value){
                if($filter == "year"){
                    if($value != -1)
                        $whereClause .= "`".$filter."` = ".$value . " AND ";
                }
                else{
                    $whereClause .= "`".$filter."` = '".$value."' AND ";
                }
            }

            $whereClause = rtrim($whereClause, "AND ") ;
            if(strlen($whereClause) > 0)
                $whereClause = "(" . $whereClause . ")";
        }
        if(isset($searches['search'])){
            $whereClause .= " AND (";
            foreach($searches['search'] as $search=>$value){
                if($search == "year"){
                    if($value != -1)
                        $whereClause .= "`".$search."` = ".$value . " OR ";
                }
                else{
                    $whereClause .= "`".$search."` LIKE '%".$value."%' OR ";
                }
            }
            $whereClause = trim(rtrim($whereClause,"OR "), "AND "). ")";
        }
        $this->db->where($whereClause);
        if(isset($searches['search']['author'])){
            $this->db->group_by("author");
        }
        $this->db->order_by("year", "DESC");
        $this->db->limit(4);
        $query = $this->db->get("questionnaire");
        if($query->num_rows() == 0)
            return array();

        $results = array();
        foreach($query->result() as $questionnaire){
            $results[] = new Questionnaire_model($questionnaire);
        }
        return $results;
    }
    
    public function update($status = "ORIGINAL"){
        $this->db->where("questionnaire_id", $this->questionnaire_id);
        $updateArray = array(
            "name"=>$this->name,
            "author"=>$this->author,
            "year"=>$this->year,
            "status"=>$status
        );
        return $this->db->update("questionnaire", $updateArray);
    }
    
    public function save($returnCreateID = FALSE){
        $insertArr = array(
            "name"=>$this->name,
            "author"=>$this->author,
            "year"=>$this->year
            );
        $result = $this->db->insert("questionnaire",$insertArr);
        
        if($returnCreateID)
            return $this->db->insert_id();
        return $result;
    }

    public function get_distinct_years(){
        $years = array();
        $this->db->select("year");
        $this->db->distinct();
        $this->db->order_by("year", "DESC");
        $this->db->where("year IS NOT NULL");
        $query = $this->db->get("questionnaire");
        if($query->num_rows() == 0){
            return $years;
        }

        foreach($query->result() as $year){
            $years[] = $year->year;
        }
        return $years;
    }



    private function setup_join_questionnaire_question(){
        $this->db->select("q.*");
        $this->db->from("questionnaire as qn");
        $this->db->join("questionnaire_question as qq", "qn.questionnaire_id = qq.questionnaire_id");
        $this->db->join("question as q", "q.question_id = qq.question_id");
        $this->db->where("qn.questionnaire_id" , $this->questionnaire_id);
    }
    
    private function _get_questions(){
        $this->setup_join_questionnaire_question();
        $query = $this->db->get();

        $questions = $this->result_to_question_objects($query->result());
        return $questions;
    }
    
    private function result_to_question_objects($queryResult){
        $questions = array();
        foreach($queryResult as $question){
            $questions[] = new $this->Question_model($question);
        }
        return $questions;
    
    }
    
    public function print_q(){
        var_dump($this);
    }
    
    private function set_questionnaire_by_id($id){
        $this->db->where("questionnaire_id" , $id);
        $query = $this->db->get("questionnaire");
        $newQuestionnaire = $query->result();
        $this->set_object($newQuestionnaire[0]);
        return $this;
    }
    
    private function to_questionnaire_obj($arr){
        $questionnaires = array();
        foreach($arr as $questionnaire){
            $questionnaires[] = new Questionnaire_model($questionnaire);
        }
        return $questionnaires;
    }
    
    private function set_array($questionnaireObj){
        
        if(isset($questionnaireObj['questionnaire_id']))    $this->questionnaire_id     = (int)$questionnaireObj['questionnaire_id'];
        if(isset($questionnaireObj['name']))                $this->name                 = $questionnaireObj['name'];
        if(isset($questionnaireObj['author']))              $this->author               = $questionnaireObj['author'];
        if(isset($questionnaireObj['year']))                $this->year                 = (int)$questionnaireObj['year'];
        if(isset($questionnaireObj['status']))              $this->status               = $questionnaireObj['status'];         else $this->status = "COMPLETE";

        return $this;
    }
    
    private function set_object($questionnaireObj){
        if(isset($questionnaireObj->questionnaire_id))  $this->questionnaire_id = (int)$questionnaireObj->questionnaire_id;
        if(isset($questionnaireObj->name))              $this->name             = $questionnaireObj->name;
        if(isset($questionnaireObj->author))            $this->author           = $questionnaireObj->author;
        if(isset($questionnaireObj->year))              $this->year             = (int)$questionnaireObj->year;
        if(isset($questionnaireObj->status))            $this->status           = $questionnaireObj->status;
        
        return $this;
    }
}

?>