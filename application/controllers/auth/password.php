<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Niesmo
 * Date: 10/7/14
 * Time: 6:16 AM
 */

class Password extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model("User_model" , "MUser");
        $this->load->library('email');

        $this->load->library(array("form_validation"));
        $this->form_validation->set_error_delimiters('<li class="list-group-item list-group-item-danger">', '</li>');

    }

    public function forgot(){
        $header['title'] = "Forgot your password?";
        $this->load->view("templates/head", $header);
        $this->load->view("templates/header");
        $this->load->view("auth/forgot_password");
        $this->load->view("templates/footer");
    }

    public function search(){
        $header['title'] = "Searching for you account?";
        $account_info = $this->input->post("account-info");
        $searchByEmail = false;

        if(filter_var($account_info, FILTER_VALIDATE_EMAIL)){
            //this is a valid email address and we should look in the email column
            $res = $this->MUser->get_user_by_email($account_info);
            $searchByEmail = true;
        }
        else{
            //the user is trying his/her full name
            $res = $this->MUser->get_user_by_full_name($account_info);
        }
        if($res != NULL){
            $hash = substr(sha1(base64_encode($res->get_user_id())),15);
            if($searchByEmail){
                $url = base_url() . "index.php/auth/password/reset/".rawurlencode($res->get_email())."/".($hash);
            }
            else{
                $url = base_url() . "index.php/auth/password/reset/".rawurlencode($res->get_username())."/".($hash);
            }

            $config['mailtype'] = "html";
            $this->email->initialize($config);

            $this->email->from('no-reply@iusur.com', 'IUSuR');
            $this->email->to($res->get_email());
            $this->email->subject('Password reset on IUSuR');

            $message = "<p>You're receiving this e-mail because you requested a password reset for your user account at  IUSuR</p>";
            $message .= "<p>Please go to the following page and choose a new password:</p><p><a href='{$url}'>{$url}</a></p>";
            $message .= "<p>Thanks for using our site!</p>";

            $this->email->message($message);
            $this->email->send();
            $data['url'] = $url;
        }

        $data['UI_message'] = "Thank you, we have send you an email containing a link to reset your password";

        $this->load->view("templates/head", $header);
        $this->load->view("templates/header");
        $this->load->view("auth/forgot_password", $data);
        $this->load->view("templates/footer");
    }

    public function reset($identity,$hash){
        $identity = rawurldecode($identity);
        $searchByEmail = false;

        if(filter_var($identity, FILTER_VALIDATE_EMAIL)){
            //it is an email address
            $res = $this->MUser->get_user_by_email($identity);
            $searchByEmail = true;
        }
        else{
            $res = $this->MUser->get_user_by_full_name($identity);
        }

        if($res != NULL){
            $correctHash = substr(sha1(base64_encode($res->get_user_id())),15);
            if($correctHash == $hash){
                $header = array("title"=>"Set your new password");
                $data['user_id']=$res->get_user_id();
                $this->load->view("templates/head", $header);
                $this->load->view("templates/header");
                $this->load->view("auth/set_new_password", $data);
                $this->load->view("templates/footer");
            }
            else{
                redirect("auth/login");
            }
        }
        else{
            redirect("auth/login");
        }
    }

    public function set_new_password(){
        $data = $this->input->post();
        $this->form_validation->set_rules('new_password', 'Password', 'required|min_length[6]|matches[re_new_password]|md5');
        $this->form_validation->set_rules('re_new_password', 'Password Confirmation', 'required');
        if($this->form_validation->run() == false){
            $data['passwordErrors'][] = validation_errors();
            $header['title']= "Invalid form";
        }
        else{
            $user = new $this->MUser($data['user_id']);
            $updated = $user->update_password($data['new_password']);
            if($updated){
                redirect("user/dashboard");
            }
            else{
                $data['passwordErrors'][] = "Something went wrong while setting your new password! Please try again!";
            }
        }

        $this->load->view("templates/head", $header);
        $this->load->view("templates/header");
        $this->load->view("auth/set_new_password", $data);
        $this->load->view("templates/footer");




        //update the password in the database


    }
} 