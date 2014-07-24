<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Questionnaire extends CI_Controller {
    public function __construct(){
        parent::__construct();

        $this->output->enable_profiler(TRUE);
    }
    
	public function index(){
        $data = array();
        $questionnares = $this->Questionnaire_model->get_all_questionnaires();
        $data['questionnares'] = $questionnares;
        
		$this->load->view('templates/head.php', array('title'=>"Questionnaires"));
		$this->load->view('templates/header.php');
        $this->load->view('questionnaire/questionnaire.php', $data);
		$this->load->view('templates/footer.php');
	}
    
    public function search($searchTerm = "",$field="all" ,$searchYear=NULL){
        if($searchTerm == "")
            redirect("questionnaire", "refresh");

        $searchTerm = rawurldecode ($searchTerm);
        $data = array();
        $years = $this->Questionnaire_model->get_distinct_years();
        
        $this->benchmark->mark('questionnaire_start');
        $data['questionnares'] = $this->Questionnaire_model->search($searchTerm,$field, $searchYear);
        $this->benchmark->mark('questionnaire_end');
        
        $data['elapsedSearchTime'] = $this->benchmark->elapsed_time('questionnaire_start', 'questionnaire_end');
        $data['searchTerm'] = $searchTerm;
        $data['searchYear'] = $searchYear;
        $data['years'] = $years;
        $data['field'] = $field;
        
        $this->load->view('templates/head.php', array('title'=>"Search for '{$searchTerm}' in Questionnaires"));
		$this->load->view('templates/header.php');
        $this->load->view('questionnaire/search.php', $data);
		$this->load->view('templates/footer.php');
    }
    
    public function qsearch($qn_id, $searchTerm= ""){
        if($searchTerm == "")
            redirect("questionnaire/detail/{$qn_id}", "refresh");
        
        $searchTerm = rawurldecode ($searchTerm);
        $questionnaire = new Questionnaire_model($qn_id);
        $data = array();
        
        $data['questionnaire'] = $questionnaire;
        $this->benchmark->mark("question_search_start");
        $data['questions'] = $questionnaire->qsearch($searchTerm);
        $this->benchmark->mark("question_search_end");
        $data['elapsedSearchTime'] = $this->benchmark->elapsed_time("question_search_start", "question_search_end" );
        $data['searchTerm'] = $searchTerm;
        
        
        $this->load->view('templates/head.php', array('title'=>"Search for '{$searchTerm}' in Questions In '".character_limiter($questionnaire->get_name(), 15)."'"));
        $this->load->view('templates/header.php');
        $this->load->view('questionnaire/qsearch.php', $data);
        $this->load->view('templates/footer.php');
        
    }
    
    public function detail($id){
        $data = array();
        
        //Get the questionnaire and its questions
        $questionnaire = new Questionnaire_model($id);
        $questions = $questionnaire->get_questions();
        
        $data['questionnaire'] = $questionnaire;
        $data['questions'] = $questions;
        
        $this->load->view('templates/head.php', array('title'=>"Detail of Questionnaire '".character_limiter($questionnaire->get_name(), 15)."'"));
		$this->load->view('templates/header.php');
        $this->load->view("questionnaire/detail", $data);
        $this->load->view('templates/footer.php');
        
    }
}

/* End of file Questionnaire.php */
/* Location: ./application/controllers/Questionnaire.php */