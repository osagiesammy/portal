<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PayforHostel
 *
 * @author osagiesammy
 */
class PayforHostel extends BaseController {

    // Call the Model constructor
    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->model('ug/Student_m');
        $this->load->helper('captcha');
        $this->load->model('ug/Paymentremita_m');
        $this->load->helper('html');
        $this->load->model('admin/Captcha');
    }

    public function index() {

        $this->form_validation->set_error_delimiters('<div class="errormessage">', '</div>');
        $this->form_validation->set_rules('Amount', 'Amount Paid', 'trim|required|xss_clean');
        $this->form_validation->set_rules('payerName', 'Payer Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('payerEmail', 'Payer Email', 'trim|required|xss_clean');
        $this->form_validation->set_rules('payerPhone', 'Payer Phone', 'trim|required|xss_clean');
        $this->form_validation->set_rules('paymenttype', 'Payment type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('paymentItem', 'Payment Item', 'trim|required|xss_clean');
        $this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|xss_clean|callback_captcha_check');

        if ($this->form_validation->run() == FALSE) {
            $sub_data['cap_img'] = $this->Captcha->make_captcha();
            $this->load->view('template/header');
            $this->load->view('template/header_menu');
            $this->load->view('secured/payforHostel', $sub_data);
            $this->form_validation->set_message('rule', 'Error Message');
            $this->load->view('template/footer_other');
        } else {
            $this->acad_sess = $this->input->post('academic_session');
            $this->itemname = $this->input->post('itemname');
            $this->itemCode = $this->input->post('ItemCode');
            $this->RegNumb = $_SESSION['RegNumb'];
            $this->db->from('paymentTrans');
            $this->db->where('RegNumb', $_SESSION['RegNumb']);
            $this->db->where('Item_Code', $this->itemCode);
            $this->db->where('status', '01');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
                //var_dump($row);die();
                foreach ($query->result() as $row) {

                    if ($row->status == '01') {
                        $this->db->from('paymentItems');
                        $this->db->where('MultiPay', 'Yes');
                        $this->db->where('ItemCode', $this->itemCode);
                        $query = $this->db->get();
                        if ($query->num_rows() > 0) {
                            $this->Paymentremita_m->payment();
                            redirect('secured/Processpayment', 'refresh');
                        } else {
                            $msg = "Hello. You already have a paid transaction check back in two hours time!!! ";
                            $_SESSION['paymsg'] = $msg;
                            $this->session->mark_as_flash('paymsg');
                            redirect('secured/PayforHostel', 'refresh');
                        }
                    } else {
                        $this->Paymentremita_m->payment();
                        redirect('secured/Processpayment', 'refresh');
                    }
                }
                $msg = "Hello. You already have a paid OR pending transaction check back in two hours time!!! ";
                $_SESSION['paymsg'] = $msg;
                $this->session->mark_as_flash('paymsg');
                redirect('secured/PayforHostel', 'refresh');
            } else {
                $this->Paymentremita_m->payment();
                redirect('secured/Processpayment', 'refresh');
                return TRUE;
            }
        }
    }

    public function captcha_check() {
// First, delete old captchas
        $expiration = time() - 3600; // One hour limit
        $this->db->where('captcha_time < ', $expiration)
                ->delete('captcha');
// Then see if a captcha exists:
        $sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
        $binds = array($_POST['captcha'], $this->input->ip_address(), $expiration);
        $query = $this->db->query($sql, $binds);
        $row = $query->row();
        if ($row->count == 0) {
            $this->form_validation->set_message('captcha_check', 'You must submit the word that appears in the image');
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
