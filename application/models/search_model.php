<?php
/**
 * Created by PhpStorm.
 * User: Niesmo
 * Date: 7/24/14
 * Time: 12:43 AM
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Search_model extends CI_Model{
    private $term;
    private $termArray;
    private $columns;
    private $where;

    //filters is like an object that contains the filter name and the value
    /*
     * {
     *  year:2001,
     *  column:author
     * }
     * and this would mean that we want to have all the results in the year 2001 and the term must only search the
     * author field from the possible fields
    */
    private $filters;
    private $searchResult;


    public function __construct($term =null, $filters=null){
        parent::__construct();

        $this->term = $term;
        $this->processTerm();

        $this->columns = "*";

        if(isset($filters['filter']['year'])){
            if($filters['filter']['year'] == -1){
                unset($filters['filter']['year']);
            }
        }

        $this->filters = $filters;
        $this->searchResult = NULL;


        $CI =& get_instance();
        $CI->load->model("search_result_model");
    }

    public function author_search(){
        $this->db->like("author", $this->term);
        if(isset($this->filters['filter']['year'])){
            $this->db->where("year",$this->filters['filter']['year']);
        }
        $query = $this->db->get("questionnaire");

        $searchResult = array();
        $searchResultIndex = 0;
        foreach($query->result() as $qn){
            $searchResult[] = new Search_result_model($qn);
            $searchResult[$searchResultIndex]->set_questions(array());
            $searchResultIndex++;
        }

        return $searchResult;
    }

    public function questionnaire_search(){
        /*
         * First find all the questions that have the terms in them
         * Then associate the questions with questionnaire and find all the parents
         * then look for any questionnaire that is not in the list and contains the terms
         */

        if($this->filters['type'] == "author"){
            return $this->author_search();
        }


        $keywordCondition = "( ";
        foreach($this->termArray as $keyword){
            $keywordCondition .= "q.`content` LIKE '%". $this->db->escape_like_str($keyword) ."%' OR qn.`name` LIKE '%". $this->db->escape_like_str($keyword) ."%' OR ";
        }
        $keywordCondition = rtrim($keywordCondition,"OR ");
        $keywordCondition .= ")";


        $this->db->select("qn.questionnaire_id, qn.name, qn.author, qn.year, count(qq.question_id) as qCount");
        $this->db->where($keywordCondition );

        if(isset($this->filters['filter']['year'])){
            $this->db->where("year",$this->filters['filter']['year']);
        }

        $this->db->from("question as q");
        $this->db->join("questionnaire_question as qq", "qq.question_id = q.question_id");
        $this->db->join("questionnaire as qn", "qn.questionnaire_id = qq.questionnaire_id");

        $this->db->group_by("qq.questionnaire_id");
        $this->db->order_by("year", "DESC");
        $this->db->order_by("qCount", "DESC");



        $query = $this->db->get();
        $result = $query->result();

        $searchResult = array();
        $searchResultIndex = 0;
        foreach ($result as $qn){
            $searchResult[] = new Search_result_model($qn);

            // see if they have any relevant questions and if so add them to the questions of the search model
            $keywordCondition = "( ";
            foreach($this->termArray as $keyword){
                $keywordCondition .= "q.`content` LIKE '%". $this->db->escape_like_str($keyword) ."%' OR ";
            }
            $keywordCondition = rtrim($keywordCondition,"OR ");
            $keywordCondition .= ")";

            $this->db->select("q.question_id, q.content");
            $this->db->where($keywordCondition);

            $this->db->from("question as q");
            $this->db->join("questionnaire_question as qq", "qq.question_id = q.question_id");
            $this->db->where("qq.questionnaire_id = {$qn->questionnaire_id}");
            $this->db->limit(3);

            $query = $this->db->get();
            $questions = $query->result();

            //print_r($questions);
            $searchResult[$searchResultIndex]->set_questions($questions);

            $searchResultIndex++;
        }



        return $searchResult;





        //find all the questions that have the term in them
        if(isset($this->filters)){
            if(isset($this->filters['column'])){
                $this->columns = $this->filters['column'];
            }

            if(isset($this->filters['year'])){
                $this->where = "(`year` = " . (int)$this->filters['year']. ")";
            }
        }

    }

    private function processTerm(){
        $this->termArray = preg_replace( '/\s+/', ' ', $this->term );
        $this->termArray = explode(" ", $this->termArray);
    }
}
?>