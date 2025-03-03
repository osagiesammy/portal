<?php
namespace App\Controllers;


class Logoutc extends BaseController {

    function index() {
        // Begin the session
        $this->load->library('session');
        $this->load->helper('url');
        $this->session->sess_destroy();
        redirect('/secured/Studentloginc', 'refresh');
    }

}