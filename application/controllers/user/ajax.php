<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends User_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model("Project_model" , "MProject");
        $this->load->model("User_project_model" , "MUserProject");
        $this->output->enable_profiler(FALSE);
    }

    public function create_project(){
        header('Content-Type: application/json');
        $projectName = $this->input->post("name");
        $newProject = $this->MProject->quick_create($projectName);
        $newConnection = $this->MUserProject->make_connection($newProject);


        if(isset($newConnection) && $newConnection != NULL){
            $json_res = json_encode($newProject);
            $decode = json_decode($json_res, true);
            $decode['status'] = "success";
            $json_res = json_encode($decode);
            echo $json_res;
        }
        else{
            echo json_encode(array("status"=>"failed"));
        }
    }    
    
    public function remove_project(){
        $project_id = $this->input->post("project_id");
        $project = new $this->MProject($project_id);
        //print_r($project);
        if($project->remove() > 0){
            echo "SUCCESS";
            return;
        }
        echo "FAILED";
    }
    
    public function set_default(){
        $defaultProjectId = $this->input->post("defaultProjectId");
        if($defaultProjectId == -1 || !isset($defaultProjectId) || $defaultProjectId == NULL){
            echo "FAILED";
            return;
        }
        
        $user = $this->MUserProject->get_user();
        $user->set_default_project_id($defaultProjectId);
        echo "SUCCESS";
    }
    
    public function add_question(){
        $data = $this->input->post();
        
        
        if(!isset($data['user_questionnaire_id']) || $data['user_questionnaire_id'] == "" ||
            !isset($data['question_id']) || $data['question_id'] == ""||
            !isset($data['original_questionnaire_id']) || $data['original_questionnaire_id'] == ""){
            
            echo "FAILED";
            return;
        }
        
        $question = new $this->Question_model($data['question_id']);
        $data['questionnaire_id'] = $data['user_questionnaire_id'];
        $data['content'] = $question->get_content();
        $data['created_by'] = $this->session->userdata("user_id");
        $user_question = new $this->MUQuestion($data);
        
        //setting session for the quick add
        
        $questionnaire = new $this->MUQuestionnaire($data['user_questionnaire_id']);
        $project = new $this->MProject($data['project_id']);
        
        $sessionConfig = array(
            "user_qn_id"    => (int)$data['user_questionnaire_id'],
            "project_id"    => (int)$data['project_id'],
            "qn_name"       => $questionnaire->get_name(),
            "project_name"  => $project->get_name()
        );
        $this->session->set_userdata($sessionConfig);
        
        if($user_question->save($data['original_questionnaire_id'])){
            echo "SUCCESS";
        }
        else{
            echo "FAILED";
        }
    }
    
    public function change_user_question(){
        $data = $this->input->post();
        if(!isset($data['question_id']) || !isset($data['content'])){
            echo "FAILED";
            return;
        }
        
        $q_id = $data['question_id'];
        $content = $data['content'];
        
        $question = new $this->MUQuestion($q_id);
        if($question->update_content($content) > 0){
            echo "SUCCESS";
            return;
        }
        
        echo "FAILED";
        
    }
    
    public function create_questionnaire(){
        $data = $this->input->post();
        $questionnaireName = $data['questionnaireName'];
        $project_id = $data['project_id'];
        $project = new $this->MProject($project_id);
        
        $newQuestionnaire_id = $project->add_questionnaire($questionnaireName);
        
        if ($newQuestionnaire_id > 0){
            echo "SUCCESS:{$newQuestionnaire_id}";
        }
        else{
            echo "FAILED";
        }
    }
    
    public function remove_user_questionnaire(){
        $questionnaire_id = $this->input->post("questionnaire_id");
        $questionnaire = new $this->MUQuestionnaire($questionnaire_id);
        if($questionnaire->remove() > 0){
            echo "SUCCESS";
            return;
        }
        echo "FAILED";
    }
    
    public function set_project_description(){
        $this->load->model("Project_model" , "MProject");
        $data = $this->input->post();
        $project = new $this->MProject($data);
        if($project->update() == 1){
            echo "SUCCESS";
        }
        else{
            echo "FALSE";
        }
    }
    
    public function get_questionnaires(){
        $this->load->model("Project_model" , "MProject");
        $project_id = $this->input->post("project_id");
        $user_id = $this->session->userdata("user_id");
        
        $project = new $this->MProject($project_id);
        $questionnaires = $project->get_questionnaire();
        if(!empty($questionnaires)){
            foreach($questionnaires as $questionnaire){
                echo $questionnaire->get_id(). ", ".$questionnaire->get_name()."\n";
            }
        }
        else{
            echo "EMPTY";
        }   
    }

    public function compare_content(){
        $this->config->load('dandelion_sim');
        $id = $this->config->item('id');
        $key = $this->config->item('key');
        $url = $this->config->item('url');
        
        
        $data = $this->input->post();
        
        $current = rawurlencode ($data['current']);
        $new = rawurlencode ($data['newContent']);
        
        $f_url = $url . "?text1=" . $current . "&text2=" . $new ."&min_confidence=0.6" ."&\$app_id=". $id . "&\$app_key=".$key; 
        $xml = file_get_contents($f_url);
        $parsedData = json_decode($xml);
        echo $parsedData->similarity;
    }

    public function invite_collaborator(){
        $this->load->model("User_model" , "MUser");
        $email = $this->input->post("email");
        $project_id = $this->input->post("project_id");
        //check if the email is valid
        
        
        $collaborator = $this->MUser->get_user_by_email($email);
        //we didnt find the user but we pretent that we sent it
        if($collaborator == NULL){
            echo "SUCCESS";
            return;
        }
        
        //check if there is already a request 
        if($collaborator->is_invited($project_id)){
            echo "SUCCESS";
            return;
        }
        
        //check if that person is already in the group
        if($collaborator->is_in_project($project_id)){
            echo "REPEAT";
            return;
        }
        
        if($collaborator->submit_collaboration_request($project_id)){
            echo "SUCCESS";
        }
        else{
            echo "FAILED";
        }
        
    }
    
    public function get_notifications(){
        
        $this->load->model("User_model" , "MUser");
        $user = $this->MUser->get_user();
        $notifications = $user->get_notifications();
        $counter = 1;
        echo "<notifications>";
        foreach($notifications as $notification){
            echo "<notification data-id='{$notification->collaboration_request_id}'>";
                echo "<from data-id='{$notification->sender}'>".$notification->firstName. " ".$notification->lastName. "</from>";
                echo "<project data-id='{$notification->project_id}'>".$notification->name."</project>";
                echo "<date>".$notification->request_date."</date>";
            echo "</notification>";
            $counter++;
        }
        echo "</notifications>";
        
    }
    
    public function accept_collaboration(){
        $this->load->model("User_model" , "MUser");
        $data = $this->input->post();
        $user = $this->MUser->get_user();
        
        if($user->accept_offer($data['request_id'])){
            echo "SUCCESS";
            return;
        }
        else{
            echo "FAILED";
            return;
        }
    }
    
    public function decline_collaboration(){
        $this->load->model("User_model" , "MUser");
        $data = $this->input->post();
        $user = $this->MUser->get_user();
        
        if($user->decline_offer($data['request_id']) > 0){
            echo "SUCCESS";
            return;
        }
        else{
            echo "FAILED";
            return;
        }
    }

    public function add_questionnaire_to_project(){
        $this->load->model("User_model" , "MUser");
        $this->load->model("User_questionnaire_model" , "MUQuestionnaire");

        $data = $this->input->post();
        $user = $this->MUser->get_user();

        //first craete a new qn in the user_questionnaire table
        $new_qn = new User_questionnaire_model(array(
            'name' => "[COPY] ".$data['questionnaire_name'],
            'created_by' => $user->get_user_id(),
            'project_id' => $data['project_id']
        ));

        $newQnId = $new_qn->save();
        $originalQnId = $data['questionnaire_id'];
        $originalQn = new Questionnaire_model($originalQnId);
        if($newQnId){
            //copy all the questions from the question table that belog to this questionnaire in the uesr question table
            $originalQuestions = $originalQn->get_questions();

            $questionsInserted = $new_qn->insert_questions($originalQuestions);
            if($questionsInserted == count($originalQuestions)){
                echo "SUCCESS";
            }
        }
        else{
            echo "FAILED";
        }

    }

    public function add_questions_to_questionnaire(){

        $this->load->model("User_model" , "MUser");
        $this->load->model("User_questionnaire_model" , "MUQuestionnaire");
        $this->load->model("User_question_model" , "MUQuestion");

        $data = $this->input->post();
        $user = $this->MUser->get_user();

        $questions = [];
        foreach($data['questions'] as $question){
            $questions[] = new User_question_model(array(
                "content"=>$question['question_content'],
                "questionnaire_id"=>$question['questionnaire_id'],
                "question_id"=>$question['question_id'],
                "created_by"=>$user->get_user_id()
            ));
        }

        $qn = new User_questionnaire_model($data['questionnaire_id']);
        $questionsInserted = $qn->insert_questions($questions);
        if($questionsInserted == count($questions)){
            $qn->updated();
            echo "SUCCESS";
        }
        else{
            echo "FAILED";
        }
    }

    public function update_project_color(){
        $data = $this->input->post();
        if(!isset($data['project_id']) || !isset($data['color'])){
            echo "FAILED";
            return;
        }

        if(strlen($data['color']) > 6){
            echo "FAILED";
            return;
        }

        $project = new $this->MProject($data['project_id']);

        $res = $project->set_project_color($data['color']);
        if($res == 1){
            echo "SUCCESS";
            return;
        }
        else{
            echo "FAILED";
            return;
        }
    }

}?>

