<?php
/**
 * Created by PhpStorm.
 * User: Niesmo
 * Date: 7/6/14
 * Time: 1:52 PM
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {
    public function suggest_search(){
        $data = $this->input->post();
        $result = $this->Questionnaire_model->advance_search($data);
        if(count($result)>0){
            $xmlRes="";
            $xmlRes .= "<questionnaires>";
            foreach($result as $qn){
                $xmlRes .= "<questionnaire id='{$qn->get_id()}'>";
                $xmlRes .= "<name>".$qn->get_name()."</name>";
                $xmlRes .= "<author>".$qn->get_author()."</author>";
                $xmlRes .= "<year>".$qn->get_year()."</year>";
                $xmlRes .= "</questionnaire>";
            }
            $xmlRes .= "</questionnaires>";
            echo trim(rtrim($xmlRes));
        }
    }
}
?>