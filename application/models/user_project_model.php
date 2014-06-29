<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_project_model extends CI_Model {
    private $user_project_id;
    private $permission;
    
    private $user;
    private $projects;
    
    public function __construct($userProjectObj = NULL){
        parent::__construct();
        
        if(isset($userProjectObj) && $userProjectObj !== NULL)
        {
            if(is_numeric($userProjectObj)){
                $this->set_user_project_by_id($userProjectObj);
            }
            elseif(is_array($userProjectObj))
            {
                $this->set_array($userProjectObj);
            }
            else
            {
                $this->set_object($userProjectObj);   
            }
        }
    }
    
    public function get_projects(){
        if(!isset($this->projects) || $this->projects == NULL){
            $this->set_projects();
        }
        return $this->projects;
    }
    public function get_user(){
        if(!isset($this->user) || $this->user == NULL){
            $this->set_user();
        }
        return $this->user;
    }
    
    
    public function make_connection($project){
        $this->user = $this->get_user();
        $insertArr = array(
            "user_project_id"=>NULL,
            "user_id"=>$this->user->get_user_id(),
            "project_id"=>$project->get_id(),
            "permission"=>"WRITE"
        );
        
        $this->db->insert("user_project", $insertArr);
        
        $insertArr['user_project_id'] = $this->db->insert_id();
        $this->set_array($insertArr);
        return $this;
    }
    
    public function is_allowed(){
        $user_id = $this->session->userdata("user_id");
        //SELECT * FROM user_project as up WHERE up.project_id = 39 AND up.user_id = 4;
        $this->db->from("user_project as up");
        $this->db->where("up.project_id" , $this->user_project_id);
        $this->db->where("up.user_id" , $user_id);
        return $this->db->count_all_results()==1;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    private function result_to_project_objects($arr){
        $CI =& get_instance();
        $CI->load->model('Project_model', 'MProject');
        
        $projects = array();
        foreach ($arr as $project){
            $projects[] = new $CI->MProject($project);
        }
        return $projects;
    }
    
    private function set_user(){
        $CI =& get_instance();
        $CI->load->model('User_model', 'MUser');
        
        $user_id = $this->session->userdata('user_id');
        $this->db->where("u.user_id" , $user_id);
        $query = $this->db->get("user as u");
        $result = $query->row();
        $this->user = new $CI->MUser($result);
    }
    
    private function set_projects(){        
        if(!isset($user) || $user == NULL){
            $this->set_user();    
        }
        
        $this->db->select("p.*");
        $this->db->from("user_project as up");
        $this->db->join("project as p", "p.project_id = up.project_id");
        $this->db->where("up.user_id" , $this->user->get_user_id());
        $query = $this->db->get();
        
        $this->projects = $this->result_to_project_objects($query->result());
    }
    
    
    private function set_array($userProjectObj){
        if(isset($userProjectObj['user_project_id']))   $this->user_project_id  = $userProjectObj['user_project_id'];
        if(isset($userProjectObj['permission']))        $this->permission       = $userProjectObj['permission'];
    }
    
    private function set_object($userProjectObj){
        if(isset($userProjectObj->user_project_id)) $this->user_project_id  = $userProjectObj->user_project_id;
        if(isset($userProjectObj->permission))      $this->permission       = $userProjectObj->permission;
    }
    
    private function set_user_project_by_id($up_id){
        $this->db->where("user_project_id" , $up_id);
        $query = $this->db->get("user_project");
        $result = $query->row();
        $this->set_object($result);
    }
}
?>