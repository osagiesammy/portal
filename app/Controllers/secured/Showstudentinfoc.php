<?php

namespace App\Controllers;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Showstudentinfo
 *
 * @author osagiesammy
 */
class Showstudentinfoc extends BaseController {

    //put your code here
    public function index() {
        $data = array('title' => 'Student Info Display'
        );
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('html');
        $this->load->model('ug/Student_m');
        echo view('template/header');
        echo view('template/header_menu');
        echo view('secured/showstudentinfo', $data);
        echo view('template/footer_other');
    }

}
