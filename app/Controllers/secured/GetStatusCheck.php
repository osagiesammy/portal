<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GetStatusCheck
 *
 * @author osagiesammy
 */
class GetStatusCheck extends BaseController {

    //put your code here
    function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->database();
        $this->load->model('ug/Paymentremita_m');
        $this->load->helper('html');
    }

    public function index() {
        $this->load->view('template/header');
        $this->load->view('template/header_menu');
        $this->load->view('secured/getstatuscheck');
        $this->load->view('template/footer_other');
    }

}