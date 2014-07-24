<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {
    private $user_id;
    private $firstName;
    private $lastName;
    private $email;
    private $password;
    private $role;
    private $default_project_id;
    
    public function __construct($userObj = NULL){
        parent::__construct();
        if(isset($userObj) && $userObj !== NULL)
        {
            if(is_numeric($userObj)){
                $this->set_user_by_id($userObj);
            }
            elseif(is_array($userObj))
            {
                $this->set_array($userObj);
            }
            else
            {
                $this->set_object($userObj);   
            }
        }
        return $this;
    }
   
    public function login(){
        if($this->authenticate()){
            return true;
        }
        else{
            throw new Login_fail();
            return false;
        }
    }
    
    public function signup(){
        if($this->user_exist()){
            throw new Exception_user_exist();
        }
        
        $user_id = $this->insert_user();
        if( $user_id === FALSE){
            throw new Exception_user_insertion_failed();
        }
        else{
            $this->user_id = $user_id;
            $this->set_login_session();
            return true;
        }
    }
    
    public function wipe_login_session(){
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('is_admin');
        $this->session->unset_userdata("project_id");
        $this->session->unset_userdata("user_qn_id");
        return true;
    }
    
    public function is_logged_in(){
        $sessionInfo = $this->session->userdata("logged_in");
        if (isset($sessionInfo) && $sessionInfo === true)
            return true;
        else
            return false;
    }
    
    public function get_user(){
        
        if(!isset($this->user_id) || $this->user_id == NULL || $this->user_id == 0){            
            $this->set_user();
        }
        return $this;
    }
    
    public function get_username(){
        if(!isset($this->firstName) || $this->firstName == NULL){
            $this->set_user();
        }
        return $this->firstName . " " . $this->lastName;
    }
    
    public function get_user_id(){
        if(!isset($this->user_id) || $this->user_id == NULL){
            $this->set_user();
        }
        return $this->user_id;
    }
    
    public function set_default_project_id($dp_id){
        $this->db->set("default_project_id", $dp_id);
        $this->db->where("user_id", $this->user_id);
        $this->db->update("user");
        
        $this->default_project_id = $dp_id;
    }
    
    public function get_default_project_id(){
        if(!isset($this->default_project_id) || $this->default_project_id == NULL){
            $this->set_user();
        }
        return (isset($this->default_project_id)?$this->default_project_id:-1);
    }
    
    public function default_project_is_set(){
        if(!isset($this->default_project_id) || $this->default_project_id == NULL)
            return FALSE;
        return TRUE;
    }
    
    
    public function get_user_by_email($email){
        $this->db->where("email" , $email);
        $query = $this->db->get("user");
        if($query->num_rows() == 0){
            return NULL;
        }
        return $this->set_object($query->row());
    }
    
    public function submit_collaboration_request($project_id){
        $insertArr = array(
            "sender"=>$this->session->userdata("user_id"),
            "user_id"=>$this->user_id,
            "project_id"=>$project_id
            );
        
        return $this->db->insert("collaboration_request", $insertArr);
    }
    
    public function is_in_project($project_id){
        $this->db->where("project_id" , $project_id);
        $this->db->where("user_id" , $this->user_id);
        $query = $this->db->get("user_project");
        if($query->num_rows() == 1)
            return TRUE;
        return FALSE;
    }
    
    public function is_invited($project_id){
        $this->db->where("project_id" , $project_id);
        $this->db->where("user_id" , $this->user_id);
        $this->db->where("response IS NULL" ,NULL, FALSE);
        $query = $this->db->get("collaboration_request");
        if($query->num_rows()>0)
            return TRUE;
        return FALSE;
    }
    
    
    public function get_notifications(){
        //SELECT u.firstName, u.lastName, p.name, cr.request_date 
        //FROM user as u, project as p, collaboration_request as cr 
        //WHERE u.user_id = cr.user_id AND p.project_id = cr.project_id and cr.user_id = 1
        
        $this->db->select("collaboration_request_id, cr.sender, u.firstName, u.lastName, p.project_id, p.name, cr.request_date");
        $this->db->where("cr.sender = u.user_id");
        $this->db->where("p.project_id = cr.project_id");
        $this->db->where("cr.user_id" , $this->user_id);
        $this->db->where("response IS NULL", NULL, FALSE);
        
        $query = $this->db->get("user as u, project as p, collaboration_request as cr");
        return $query->result();
    }
    
    public function accept_offer($request_id){
        //first check and see if the sender intended to invite this person,
        //or are they just runnung the command from the command line!!!!
        
        if(!$this->invited_me($request_id))
            return false;
        
        //get the request info
        $request = $this->get_request($request_id);
        
        //set the request response to ACCEPT
        $updateArr = array("response"=>"ACCEPT");
        $this->db->where("collaboration_request_id", $request_id);
        $this->db->update("collaboration_request", $updateArr);
        
        
        //create the user_project entry in the DB
        $insertArr = array(
            "user_id"=>$request->user_id,
            "project_id"=>$request->project_id
        );
        return $this->db->insert("user_project", $insertArr);
    }
    
    
    public function decline_offer($request_id){
        //first check and see if the sender intended to invite this person,
        //or are they just runnung the command from the command line!!!!
        
        if(!$this->invited_me($request_id))
            return false;
        
        //get the request info
        $request = $this->get_request($request_id);
        
        //set the request response to ACCEPT
        $updateArr = array("response"=>"DECLINE");
        $this->db->where("collaboration_request_id", $request_id);
        return $this->db->update("collaboration_request", $updateArr);
    }
    
    
    
    
    
    
    
    
    

    
    
    
    
    /********************* UTILITIES **********************/
    private function set_array($userObj){
        //change the keys to lower case
        foreach($userObj as $key=>$val){
            $userObj[strtolower($key)] = $val;
        }
        
        if(isset($userObj['user_id']))      $this->user_id      = (int)$userObj['user_id'];
        if(isset($userObj['firstname']))    $this->firstName    = $userObj['firstname'];
        if(isset($userObj['lastname']))     $this->lastName     = $userObj['lastname'];
        if(isset($userObj['email']))        $this->email        = $userObj['email'];
        if(isset($userObj['password']))     $this->password     = $userObj['password'];
        if(isset($userObj['role']))         $this->role         = $userObj['role'];         else $this->role = "REGULAR";
        if(isset($userObj['default_project_id']))         $this->default_project_id         = (int)$userObj['default_project_id'];

        return $this;
    }
    
    private function set_object($userObj){
        if(isset($userObj->user_id))    $this->user_id      = (int)$userObj->user_id;
        if(isset($userObj->firstName))  $this->firstName    = $userObj->firstName;
        if(isset($userObj->lastName))   $this->lastName     = $userObj->lastName;
        if(isset($userObj->email))      $this->email        = $userObj->email;
        if(isset($userObj->password))   $this->password     = $userObj->password;
        if(isset($userObj->role))       $this->role         = $userObj->role;
        if(isset($userObj->default_project_id))       $this->default_project_id         = (int)$userObj->default_project_id;
        
        return $this;
    }
    
    private function set_user_by_id($user_id){
        $this->db->where("user_id" , $user_id);
        $query = $this->db->get("user");
        return $this->set_object($query->row());
    }
    
    
    private function user_exist(){
        $this->db->select('user_id, email');
        $this->db->where("email", $this->email);
        $query = $this->db->get("user");
        if($query->num_rows() == 0)
            return false;
        return true; 
    }
    
    private function insert_user(){
        $this->password = sha1($this->password);
        $insertArr = array(
            "user_id" => null,
            "firstName"=> $this->firstName,
            "lastName"=> $this->lastName,
            "email"=> $this->email,
            "password" => $this->password,
            "role"=> $this->role
        );
        if($this->db->insert("user", $insertArr)){
            return $this->db->insert_id();
        }
        else{
            return FALSE;
        }
    }
    
    private function set_login_session(){
        $this->get_user();
        $sessionConfig = array(
                   "user_id"   => $this->user_id,
                   "username"  => $this->firstName . " " . $this->lastName,
                   "email"     => $this->email,
                   "logged_in" => TRUE
               );
        if($this->role == "ADMIN")
            $sessionConfig['is_admin'] = TRUE;

        $this->session->set_userdata($sessionConfig);
    }
    
    
    
    private function authenticate(){
        $this->password = sha1($this->password);
        $whereArr = array(
            "email"=>$this->email,
            "password"=>$this->password
            );
        
        $this->db->where($whereArr);
        $this->db->limit(1);
        $query = $this->db->get("user");
        if($query->num_rows() == 0){
            return false;
        }
        else{
            $newUser = $query->result();
            $newUser = $newUser[0];
            $newUser = new User_model($newUser);
            $newUser->set_login_session();
            return true;
        }
    }
    
    private function set_user(){
        $this->user_id = $this->session->userdata('user_id');        
        $this->db->where("user_id" , $this->user_id);
        $query = $this->db->get("user");
        $user = $query->row();
        
        $this->set_object($user);
    }
    
    
    private function invited_me($request_id){
        $this->db->where("collaboration_request_id" , $request_id);
        $query = $this->db->get("collaboration_request");
        $request = $query->row();
        if(empty($request)){
            return false;
        }
        return $request->user_id == $this->user_id;
    }
    
    private function get_request($request_id){
        $this->db->where("collaboration_request_id", $request_id);
        $query = $this->db->get("collaboration_request");
        $request = $query->row();
        return $request;
    }
}
?>