<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    public function __construct(){
        parent::__construct();  
    }  
    
    public function index(){
        echo "<h1>This is the admin page!!</h1>";
        echo "<h2>You need to be logged in!! if you are not, please let Nima know</h2>";
    }
}
?>