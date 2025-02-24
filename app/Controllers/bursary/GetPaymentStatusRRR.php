<?php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GenerateRRR
 *
 * @author osagiesammy
 */
class GetPaymentStatusRRR extends BaseController {

    //put your code here
    function __construct() {
// Call the Model constructor
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model('ug/Student_m');
        $this->load->model('ug/Paymentremita_m');
        $this->load->helper('html');
        $this->output->set_header("HTTP/1.0 200 OK");
        $this->output->set_header("HTTP/1.1 200 OK");
        $this->output->set_header('Last-Modified: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->output->set_header("Cache-Control: post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
    }

    public function index() {

        $this->form_validation->set_error_delimiters('<div class="errormessage">', '</div>');
        $this->form_validation->set_rules('RRR', 'RRR number', 'trim|required|xss_clean|alpha_numeric|callback_RRRNUMB_check');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('template/header');
            $this->load->view('template/header_menu');
            $this->load->view('bursary/getpaymentstatusRRR');
            $this->form_validation->set_message('rule', 'Error Message');
            $this->load->view('template/footer_other');
        } else {
            $this->rrr = $this->input->post('RRR');
            $this->response = $this->Paymentremita_m->getPaymentStatus($this->rrr);
            if (isset($this->response)) {
                $_SESSION['response'] = $this->response;
                    redirect('bursary/GetStatusCheck', 'refresh');
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        }

        public function RRRNUMB_check() {
            $this->RRR = $this->input->post('RRR');
            $query = $this->db->get_where('paymentTrans', array('RRR' => $this->RRR));
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                $this->form_validation->set_message('RRRNUMB_check', 'Invalid credential submitted transaction FAILED!!!');
                return FALSE;
            }
        }

    }
    