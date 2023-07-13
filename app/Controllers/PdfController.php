<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use \Mpdf\Mpdf;

class PdfController extends BaseController
{

    public function create(){
        return view('create_pdf');
    }

    // public function store() {
    //     $userModel = new UserModel();
    //     $data = [
    //         'name' => $this->request->getVar('name'),
    //         'email'  => $this->request->getVar('email'),
    //         'country'  => $this->request->getVar('country'),
    //     ];
    //     //print_r($data); die();
    //     $userModel->insert($data);
    //     return $this->response->redirect(site_url('/users-list'));
    // }
    

    public function generatePdf()
    {
        // Load the mPDF library
       require_once '../vendor/autoload.php';
       $mpdf = new \Mpdf\Mpdf();


        // Generate the PDF content dynamically
            ob_start();

            $name = $this->request->getVar('name');
            $email  = $this->request->getVar('email');
            $country  = $this->request->getVar('country');
// $mpdf->WriteHTML('Hello Sir');
// $mpdf->WriteHTML('I am $name and a resident of $country seeking to work as a php web developer');
// $mpdf->WriteHTML('Please give your feedback on my email id $email so i can improve and move forward in life.');
echo "<div>Hello Sir</div>
<p>I am $name and a resident of chandigarh , $country seeking to work  </p>
<p> as a php web developer.i have knowledge of cakeph and wordpress.</p>
<p>Hope to see you soon at Evoke Technologies as a part of the team.</p>
<p>Please give your feedback on my email id $email so i can improve </p> 
<p>and move forward in life.</p>";

$html = ob_get_contents();
ob_end_clean();

// Here convert the encode for UTF-8, if you prefer 
// the ISO-8859-1 just change for $mpdf->WriteHTML($html);
$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output(APPPATH . 'pdfs/my_pdf.pdf', 'F');
$content = $mpdf->Output('', 'S');

        // Send the email with the PDF attachment
        $this->sendEmailWithAttachment($content);
    }

    private function sendEmailWithAttachment($attachmentData)
    {
        // Load the email library
        //$this->load->library('email');
        $email = \Config\Services::email();

        // Email configuration
        $config = Array(
          'protocol' => 'smtp',
          'smtp_host' => 'sandbox.smtp.mailtrap.io',
          'smtp_port' => 2525,
          'smtp_user' => 'cfa87fd337de98',
          'smtp_pass' => 'fee53581e1ed05',
          'crlf' => "\r\n",
          'newline' => "\r\n"
        );
        // Initialize the email
        $email->initialize($config);



        // Set the email details
        $email->setFrom('92vky92@gmail.com','vineet');
        $email->setTo('vineetkumar1905@gmail.com');
        $email->setReplyTo('92vky92@gmail.com', 'vineet');
        $email->setSubject('PDF Attachment');
        $email->setMessage('Please find the attached PDF.');

        // Attach the PDF to the email
        //$email->attach($attachmentData, 'attachment.pdf', 'application/pdf');
        $pdf_path=APPPATH . 'pdfs/my_pdf.pdf';
        //print_r($pdf_path); die();
        $email->attach($pdf_path);

        // Send the email
        //$email->send();

        if($email->send(true))
        {
            echo "Mail Sent Successfully";
        }
        else
        {
            echo "Failed to send email";
            $email->printDebugger(['headers']);     
        }
        
    }

}
