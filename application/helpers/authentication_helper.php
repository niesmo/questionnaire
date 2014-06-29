<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('is_logged_in'))
{
    function is_logged_in(){
        $CI =& get_instance();      
        $sessionInfo = $CI->session->userdata('logged_in');
        if (isset($sessionInfo) && $sessionInfo === true)
            return true;
        else
            return false;
    }
}


if ( ! function_exists('is_admin'))
{
    function is_admin(){
        $CI =& get_instance();
        $sessionInfo = $CI->session->userdata('is_admin');
        if (is_logged_in()&& isset($sessionInfo) && $sessionInfo === true)
            return true;
        else
            return false;
    }
}


if(!function_exists("get_logged_in_user_info")){
    function get_logged_in_user_info(){
        $CI =& get_instance();
        $sessionInfo = $CI->session->userdata('logged_in');
        if (isset($sessionInfo) && $sessionInfo === true){
            $user = array();
            $user['user_id'] =  $CI->session->userdata('user_id');
            $user['username'] =  $CI->session->userdata('username');
            $user['email'] =  $CI->session->userdata('email');
            $user['logged_in'] = true;
            return $user;
        }
        else{
            return array();
        }
    }
}


if(!function_exists("get_notification_count")){
    function get_notification_count(){
        $CI =& get_instance();
        $CI->db->where("user_id", $CI->session->userdata("user_id"));
        $CI->db->where("response IS NULL", NULL, false);
        return $CI->db->count_all_results("collaboration_request");
    }
}