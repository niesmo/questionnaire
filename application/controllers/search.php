<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {
    public function __construct(){
        parent::__construct();
    }
    
    public function pre(){
        $data = $this->input->post();
        $path = $data['path'];
        unset($data['path']);
        unset($data['search-btn']);

        $queryStr = "";
        foreach($data as $d){
            $queryStr .= rawurlencode(strtolower(trim($d))) . "/";
        }
        $queryStr = rtrim($queryStr,"/");
        $this->session->set_userdata("last_search_uri", $path. "/" .$queryStr);
        redirect($path. "/" .$queryStr , "refresh");
    }
}
?>