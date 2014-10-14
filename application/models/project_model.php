<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_model extends CI_Model implements JsonSerializable{
    private $project_id;
    private $name;
    private $description;
    private $creation_date;
    private $created_by_id;
    private $created_by_name;
    private $last_modified_date;
    private $last_modified_by_id;
    private $last_modified_name;
    private $color;
    
    private $questionnaires;
    
    public function __construct($projectObj = NULL){
        parent::__construct();
        if(isset($projectObj) && $projectObj != NULL){
            if(is_numeric($projectObj)){
                $this->set_project_by_id($projectObj);
            }
            else if(is_array($projectObj)){                
                $this->set_array($projectObj);
            }
            else{
                $this->set_object($projectObj);
            }
        }
    }
    
    public function get_id(){
        return $this->project_id;
    }
    
    public function get_name(){
        return $this->name;
    }
    
    public function get_description(){
        return ($this->description == ""? "No description is defined":$this->description);
    }

    public function get_color(){

        if(isset($this->color)){
            return $this->color;
        }
        $colors = $this->config->item('colors');
        return $colors[$this->project_id%count($colors)];
    }

    public function get_created_name(){
        if(!isset($this->created_by_name) || is_null($this->created_by_name)){
            $this->db->select("firstName, lastName");
            $this->db->from("user as u");
            $this->db->join("project as p", "p.created_by_id = u.user_id");
            $this->db->where("p.created_by_id = {$this->created_by_id}");
            $query = $this->db->get();
            $this->created_by_name = $query->result()[0]->firstName . " " .$query->result()[0]->lastName;
        }
        return $this->created_by_name;
    }

    public function get_creation_date_pretty(){
        return date("F j, Y, g:i a", strtotime($this->creation_date));
    }

    public function get_last_modified_name(){
        if(!isset($this->last_modified_name) || is_null($this->last_modified_name)){
            if(!isset($this->last_modified_by_id)){
                $this->last_modified_name = "N/A";
            }
            else{
                $this->db->select("firstName, lastName");
                $this->db->from("user as u");
                $this->db->join("project as p", "p.last_modified_by_id = u.user_id");
                $this->db->where("p.last_modified_by_id = {$this->last_modified_by_id}");
                $query = $this->db->get();
                $this->last_modified_name = $query->result()[0]->firstName . " " .$query->result()[0]->lastName;
            }
        }
        return $this->last_modified_name;
    }

    public function get_last_modification_date_pretty(){
        if(isset($this->last_modified_date)){
            return date("F j, Y, g:i a", strtotime($this->last_modified_date));
        }else{
            return "N/A";
        }


    }


    
    public function quick_create($name = NULL){
        if(isset($name) && $name != NULL){
            $this->name = $name;
        }
        return $this->create();
    }
    
    public function create(){
        return $this->insert_project();
    }
    
    public function remove(){
        //in order to remove a project, we first need to
        //remove all the questionnaire that are the children
        //of this project and then delete the project itself.
        
        //in order to remove a questionnaire, we first need
        //to remove all of the questions for that questionnaire,
        //and then remove the questionnaire itself
        
        //all of these things happen without any effort because
        //of the way that the foreign keys are setup.
        
        $this->db->where("project_id" , $this->project_id);
        return $this->db->delete("project");
    }
    
    public function get_questionnaire(){
        if(!isset($this->questionnaires) || $this->questionnaires == NULL){
            $this->set_questionnaires();
        }
        return $this->questionnaires;
    }
    
    public function add_questionnaire($name){
        $user_id = $this->session->userdata('user_id');
        $insertArr = array(
            "name"=>$name,
            "project_id"=>$this->project_id,
            "creation_date"=>date("Y-m-d H:i:s"),
            "created_by"=>$user_id
            );
        
        $this->db->insert("user_questionnaire" , $insertArr);
        return $this->db->insert_id();
    }
    
    
    public function update(){
        if(!isset($this->project_id) || $this->project_id == NULL){
            return FALSE;
        }

        $this->last_modified_date = date("Y-m-d H:i:s",time());
        $this->last_modified_by_id = $this->session->userdata("user_id");

        $updateArr = array();
        if(isset($this->name)) $updateArr['name'] = $this->name;
        if(isset($this->description)) $updateArr['description'] = $this->description;
        if(isset($this->color)) $updateArr['color'] = $this->color;
        $updateArr['last_modified_date'] = $this->last_modified_date;
        $updateArr['last_modified_by_id'] = $this->last_modified_by_id;

        
        $this->db->where("project_id" , $this->project_id);
        return $this->db->update("project", $updateArr);
    }
    
    public function is_allowed(){
        $user_id = $this->session->userdata("user_id");
        $this->db->from("user_project as up");
        $this->db->where("up.project_id" , $this->project_id);
        $this->db->where("up.user_id" , $user_id);
        return $this->db->count_all_results()==1;
    }
    
    public function get_collaborators(){
        $CI =& get_instance();
        $CI->load->model('User_model', 'MUser');
        $users = array();

        $this->db->from("user_project as up");
        $this->db->join("user as u", "u.user_id = up.user_id");
        
        $this->db->where("project_id", $this->project_id);
        $this->db->where("up.user_id !=" , $this->session->userdata("user_id"));
        
        $query = $this->db->get();
        foreach($query->result() as $user){
            $users[] = new $CI->MUser($user);
        }
        return $users;
    }

    public function get_all_my_projects(){
        $this->db->select("*");
        $this->db->from("project as p");
        $this->db->join("user_project as up", "up.project_id = p.project_id");
        $this->db->where("up.user_id", $this->session->userdata("user_id"));
        $query = $this->db->get();

        //checking if the array is empty
        if($query->num_rows() == 0)
            return array();

        $results = array();
        foreach($query->result() as $pr){
            $results[] = new Project_model($pr);
        }
        return $results;

    }

    public function set_project_color($color){
        $this->color = $color;
        return $this->update();
    }








    public function jsonSerialize() {
        return [
            'id' => $this->project_id,
            'name' => $this->name,
            'description' => $this->description,
            'creation_date'=>$this->creation_date,
            "created_by_id"=>$this->created_by_id,
            "created_by_name"=>$this->created_by_name,
            "last_modified_date"=>$this->last_modified_date,
            "last_modified_by_id"=>$this->last_modified_by_id,
            "color"=>$this->get_color()
        ];
    }
    
    
    
    
    
    
    
    
    
    
    private function set_questionnaires(){
        $this->db->where("project_id", $this->project_id);
        $query = $this->db->get("user_questionnaire");
        $this->questionnaires = $this->result_to_user_questionnaire_objects($query->result());
        return $this->questionnaires;
    }
    
    private function result_to_user_questionnaire_objects($arr){
        $CI =& get_instance();
        $CI->load->model('User_questionnaire_model', 'MUserQuestionnaire');
        
        $questionnaires = array();
        foreach ($arr as $questionnaire){
            $questionnaires[] = new $CI->MUserQuestionnaire($questionnaire);
        }
        return $questionnaires;
    }
    
    private function insert_project(){
        $CI = &get_instance();
        $CI->load->model("User_model", "MUser");

        $this->created_by_id = $this->session->userdata('user_id');
        $userTemp = new $CI->MUser($this->created_by_id);
        $this->creation_date = date("Y-m-d H:i:s",time());

        $insertArr = array(
           "project_id" => null,
           "name"=> $this->name,
           "description"=> $this->description,
           "created_by_id"=>$this->created_by_id,
        );
        $this->db->insert("project", $insertArr);
        $insertArr['created_by_name'] = $userTemp->get_username();

        $insertArr['project_id'] = $this->db->insert_id();
        $this->set_array($insertArr);
        return $this;
    }
    
    private function set_array($projectObj){
        if(isset($projectObj['project_id']))            $this->project_id           = (int)$projectObj['project_id'];
        if(isset($projectObj['name']))                  $this->name                 = $projectObj['name'];
        if(isset($projectObj['description']))           $this->description          = $projectObj['description'];

        if(isset($projectObj['created_by_id']))         $this->created_by_id        = $projectObj['created_by_id'];
        if(isset($projectObj['created_by_name']))       $this->created_by_name      = $projectObj['created_by_name'];
        if(isset($projectObj['creation_date']))         $this->creation_date        = $projectObj['creation_date'];
        if(isset($projectObj['last_modified_by_id']))   $this->last_modified_by_id  = $projectObj['last_modified_by_id'];
        if(isset($projectObj['last_modified_date']))    $this->last_modified_date   = $projectObj['last_modified_date'];

        if(isset($projectObj['color']))                 $this->color                = $projectObj['color'];
    }
    
    private function set_object($projectObj){
        if(isset($projectObj->project_id))          $this->project_id           = (int)$projectObj->project_id;
        if(isset($projectObj->name))                $this->name                 = $projectObj->name;
        if(isset($projectObj->description))         $this->description          = $projectObj->description;

        if(isset($projectObj->created_by_id))       $this->created_by_id        = $projectObj->created_by_id;
        if(isset($projectObj->created_by_name))     $this->created_by_name      = $projectObj->created_by_name;
        if(isset($projectObj->creation_date))       $this->creation_date        = $projectObj->creation_date;
        if(isset($projectObj->last_modified_by_id)) $this->last_modified_by_id  = $projectObj->last_modified_by_id;
        if(isset($projectObj->last_modified_date))  $this->last_modified_date   = $projectObj->last_modified_date;

        if(isset($projectObj->color))               $this->color                = $projectObj->color;
    }
    
    private function set_project_by_id($p_id){
        $this->db->where("project_id" , $p_id);
        $query = $this->db->get("project");
        $result = $query->row();
        $this->set_object($result);
    }
}
?>