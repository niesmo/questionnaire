<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_question_change_model extends CI_Model {
	private $user_question_change_id;
	private $content;
	private $modification_date;
	private $question_id;
	private $modified_by_id;
	private $modified_by_name;


	public function __construct($userQuestionChangeObj = NULL){
        parent::__construct();
        
        if(isset($userQuestionChangeObj) && $userQuestionChangeObj !== NULL)
        {
            if(is_numeric($userQuestionChangeObj)){
                $this->set_user_question_change_by_id($userQuestionChangeObj);
            }
            elseif(is_array($userQuestionChangeObj))
            {
                $this->set_array($userQuestionChangeObj);
            }
            else
            {
                $this->set_object($userQuestionChangeObj);   
            }
        }
    }

    /*public function __get($name){
        switch ($name) {
            case 'content':
                return $this->content;

            case 'modification_date':
                return $this->modification_date;

            case 'modified_by_name':
                return $this->modified_by_name;

            case 'modified_by_id':
                return $this->modified_by_id;   
            
            default:
                echo "PROPERTY DOES NOT EXIST OR IS NOT DEFINED";
                break;
        }

    }*/

    public function get_content(){
        return $this->content;
    }

    public function get_modified_by_name(){
        return $this->modified_by_name;
    }

    public function get_modification_date($format = NULL){
        if($format === NULL){
            return $this->modification_date;    
        }
        else{
            $date = date_create($this->modification_date);
            return date_format( $date, $format );
        }
        
    }





    public function get_question_history($question_id){
    	$history = array();
    	$this->db->from("user_question_changes as uqc");
    	$this->db->join("user as u", "u.user_id = uqc.modified_by");
    	$this->db->where("uqc.question_id", $question_id);
        $this->db->order_by("modification_date DESC");

    	$query = $this->db->get();
    	$counter = 0;
    	
    	foreach($query->result() as $qu_history){
    		
    		$history[$counter] = new User_question_change_model($qu_history);
    		$history[$counter]->set_modified_name($qu_history->firstName ." ". $qu_history->lastName);
    		
    		$counter++;
    	}

    	return $history;
    }







    private function set_modified_name($name){
        $this->modified_by_name = $name;
    }

    private function set_user_question_change_by_id($uqc_id){
    	$this->db->where("question_changes_id" ,$uqc_id);
    	$query = $this->db->get("question_changes");
    	$this->set_object($query->row());
    	return $this;
    }

    private function set_array($userQuestionChangeObj){
    	//TODO
    	return $this;
    }

    private function set_object($userQuestionChangeObj){
    	if(isset($userQuestionChangeObj->user_question_changes_id))   $this->user_question_change_id =   (int)$userQuestionChangeObj->user_question_changes_id;
        if(isset($userQuestionChangeObj->content))            	$this->content          	=   $userQuestionChangeObj->content;
        if(isset($userQuestionChangeObj->question_id))        	$this->question_id      	=   (int)$userQuestionChangeObj->question_id;
        if(isset($userQuestionChangeObj->modified_by))     		$this->modified_by_id      	=   (int)$userQuestionChangeObj->modified_by;
        if(isset($userQuestionChangeObj->modification_date))    $this->modification_date	=   $userQuestionChangeObj->modification_date;


        return $this;
    }
}
?>