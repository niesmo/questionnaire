<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {
    public function __construct(){
        parent::__construct();
    }
    
    public function pre(){
        $searchStr = rawurlencode(strtolower($this->input->post('search')));
        redirect($this->input->post('path'). "/" .$searchStr , "refresh");
    }
}
?>