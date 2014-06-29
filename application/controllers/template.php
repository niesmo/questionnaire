<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template extends CI_Controller {
    public function __construct(){
        parent::__construct();
    }
    
	public function index(){
		$this->load->view('templates/head.php', array('title'=>"Home"));
		$this->load->view('templates/header.php');
		$this->load->view('pages/template.php');
		$this->load->view('templates/footer.php');
	}
}

/* End of file template.php */
/* Location: ./application/controllers/template.php */