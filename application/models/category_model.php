<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model{
    private $category_id;
    private $name;
    private $parent;
    
    public function __construct($categoryObj = NULL){
        parent::__construct();
        if(isset($categoryObj) && $categoryObj !== NULL)
        {
            if(is_numeric($categoryObj)){
                $this->set_category_by_id($categoryObj);
            }
            elseif(is_array($categoryObj))
            {
                $this->set_array($categoryObj);
            }
            else
            {
                $this->set_object($categoryObj);   
            }
        }
        return $this;
    }
    
    public function get_all_categories(){
        return $this->to_category_obj($this->db->get("category")->result());
    }
    
    public function get_id(){
        return $this->category_id;
    }
    
    public function get_name(){
        return $this->name;
    }
    
    

    
    
    
    
    
    
    
    
    
    
    
    
    private function to_category_obj($arr){
        $categories = array();
        foreach($arr as $category){
            $categories[] = new Category_model($category);
        }
        return $categories;
    }
    
    private function set_object($categoryObj){
        if(isset($categoryObj->category_id))    $this->category_id  = $categoryObj->category_id;
        if(isset($categoryObj->name))           $this->name         = $categoryObj->name;
        if(isset($categoryObj->parent))         $this->parent       = $categoryObj->parent;
        
    }
    private function set_array($categoryObj){
        if(isset($categoryObj['category_id']))  $this->category_id  = $categoryObj['category_id'];
        if(isset($categoryObj['name']))         $this->name         = $categoryObj['name'];
        if(isset($categoryObj['parent']))       $this->parent       = $categoryObj['parent'];
    }
    private function set_category_by_id($id){
        $this->db->where("category_id" , $id);
        $query = $this->db->get("category");
        $this->set_object($query->row());
        return $this;
    }
}

?>