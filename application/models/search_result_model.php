<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search_result_model extends CI_Model{
    private $questionnaire_id;
    private $title;
    private $author;
    private $year;
    private $questions;
    
    public function __construct($searchResultObj = NULL){
        if(is_array($searchResultObj)){
            $this->set_array($searchResultObj);
        }
        else{
            $this->set_object($searchResultObj);
        }
    }

    public function get_id(){
        return $this->questionnaire_id;
    }

    public function get_title(){
        return $this->title;
    }

    public function get_decorated_title($term){
        $term = preg_replace('/\s+/', ' ', trim($term));
        $words = explode(' ', $term);

        $highlighted = array();
        foreach ( $words as $word ){
            $highlighted[] = "<b>".$word."</b>";
        }

        return str_ireplace($words, $highlighted, $this->title);
    }

    public function get_author(){
        return $this->author;
    }

    public function get_year(){
        return $this->year;
    }


    public function get_questions(){
        return $this->questions;
    }
    public function set_questions($questions){
        $this->questions = $questions;
    }


    private function set_array($arr){
        if(isset($arr['questionnaire_id'])) $this->questionnaire_id = $arr['questionnaire_id'];
        if(isset($arr['name'])) $this->title = $arr['name'];
        if(isset($arr['author'])) $this->author = $arr['author'];
        if(isset($arr['year'])) $this->year = $arr['year'];
        if(isset($arr['questions'])) $this->questions = $arr['questions'];

        return $this;
    }

    private function set_object($obj){
        if(isset($obj->questionnaire_id)) $this->questionnaire_id = $obj->questionnaire_id;
        if(isset($obj->name)) $this->title = $obj->name;
        if(isset($obj->author)) $this->author = $obj->author;
        if(isset($obj->year)) $this->year = $obj->year;
        if(isset($obj->questions)) $this->questions = $obj->questions;

        return $this;
    }
}
?>