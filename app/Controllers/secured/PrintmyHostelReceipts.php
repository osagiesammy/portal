<?php
namespace App\Controllers;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PrintmyHostelReceipts
 *
 * @author osagiesammy
 */
class PrintmyHostelReceipts extends BaseController{
    //put your code here
    public function __construct() {
        parent::__construct();

        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->helper('html');
        $this->load->library('table');
        $this->load->library('M_pdf');
    }
    
    
   public function index() {
        $data = array('title' => 'Student Info Display'
        );
        $pdfFilePath = $this->session->userdata('RegNumb') . ".pdf";
        $pdf = $this->m_pdf->load();
        $pdf->SetHeader('Dean Student Affairs|Hostel Identity Card|{PAGENO}');
        $pdf->SetFooter('Hostel Allocation');
        $pdf->defaultheaderfontsize = 10;
        $pdf->defaultheaderfontstyle = 'B';
        $pdf->defaultheaderline = 0;
        $pdf->defaultfooterfontsize = 8;
        $pdf->defaultfooterfontstyle = 'BI';
        $pdf->defaultfooterline = 0;
        //$pdf->SetProtection(array('copy','print'), $this->session->userdata('RegNumb'),$this->session->userdata('RegNumb'));
        $pdf->watermarkImageAlpha = 0.2;
        $pdf->SetWatermarkImage(base_url() . 'assets/images/logo.jpg');
        $pdf->showWatermarkImage = true;
        $html = echo view('secured/printMyhostelreceipts', $data, TRUE);

        //generate the PDF from the given html
        $this->stylesheet = file_get_contents(base_url() . 'assets/bootstrap/css/bootstrap.min.css');
        $pdf->WriteHTML($this->stylesheet, 1);
        $pdf->WriteHTML($html);

        $html2 = echo view('secured/printHostelCard', $data, TRUE);
        $pdf->SetHeader('Dean Student Affairs|Hostel Identity Card|{PAGENO}');
        $pdf->SetFooter('Hostel Allocation');
        $pdf->defaultheaderfontsize = 10;
        $pdf->defaultheaderfontstyle = 'B';
        $pdf->defaultheaderline = 0;
        $pdf->defaultfooterfontsize = 8;
        $pdf->defaultfooterfontstyle = 'BI';
        $pdf->defaultfooterline = 0;
        $pdf->AddPage();
        $pdf->WriteHTML($html2);

        /*********/
        $html3 = echo view('secured/printMyhostelreceipts2', $data, TRUE);
        $pdf->SetHeader('Office of Bursary|School Fees receipt|{PAGENO}');
        $pdf->SetFooter('Hostel Allocation');
        $pdf->defaultheaderfontsize = 10;
        $pdf->defaultheaderfontstyle = 'B';
        $pdf->defaultheaderline = 0;
        $pdf->defaultfooterfontsize = 8;
        $pdf->defaultfooterfontstyle = 'BI';
        $pdf->defaultfooterline = 0;
        $pdf->AddPage();
        $this->stylesheet = file_get_contents(base_url() . 'assets/bootstrap/css/bootstrap.min.css');
        $pdf->WriteHTML($this->stylesheet, 1);
        $pdf->WriteHTML($html3);
        /*****/

        //import from the pdf
        $pdf->Output($pdfFilePath, "I");
        
    }
}