<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Lib\DesignService;
use App\Lib\TemplatesService;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/4/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{

    public function homepage()
    {
        if ($this->user) {
            return $this->redirect('/dashboard');
        }
    	$this->set('recentImages', []);
        $this->set('templates', TemplatesService::list(null, 1, 8));
        $this->render('home');
    }

    public function login()
    {
        if ($this->user) {
            return $this->redirect('/');
        }
    	$this->set('title', 'Login');
    	$this->set('hide_header', 1);
    	$this->set('hide_footer', 1);
    	$this->set('hide_share_buttons', 1);
        $this->render('login');
    }

    public function templates()
    {
    	$recentImages = null;

    	$this->set('title', 'Quotes Templates');
    	$this->set('templates', TemplatesService::list(null, 1, 30));
        $this->render('templates');
    }

    public function embed($id)
    {
    	$Design = DesignService::get($id);

        $widget_version = (int) $this->request->getQuery('v', '1.0');

        if (!$Design) {
            echo 'Quote not found';
        }

    	$this->set('title', 'Embed Quote');
    	$this->set('widget_version', $widget_version);
    	$this->set('design', $Design);
        $this->render('embed');
    }

    public function testEmail()
    {
        // if(isset($_POST['submit'])) {
            $mailto = "mauriblint@gmail.com";  //My email address
            //getting customer data
            // $name = $_POST['name']; //getting customer name
            // $fromEmail = $_POST['email']; //getting customer email
            // $phone = $_POST['tel']; //getting customer Phome number
            // $subject2 = "Confirmation: Message was submitted successfully | HMA WebDesign"; // For customer confirmation

            $_name = 'Mauricio';
            $_from_email = 'maurcio@app-sorteos.com';
            $_subject = 'test email'; //getting subject line from client
            $_message = 'asdalkdjaldjalkdjalsdjalkdajdlka jdlkaj dlkajdlakjdlakdj alkj ';

            //Email body I will receive
            $message = "Client Name: " . $_name . "\n"
            // . "Phone Number: " . $phone . "\n\n"
            . "Client Message: " . "\n" . $_message;

            //Message for client confirmation
            // $message2 = "Dear" . $_name . "\n"
            // . "Thank you for contacting us. We will get back to you shortly!" . "\n\n"
            // . "You submitted the following message: " . "\n" . $_POST['message'] . "\n\n"
            // . "Regards," . "\n" . "- HMA WebDesign";

            //Email headers
            $headers = "From: " . $_from_email; // Client email, I will receive
            $headers2 = "From: " . $mailto; // This will receive client

            //PHP mailer function

             $result1 = mail($mailto, $_subject, $_message, $headers); // This email sent to My address
            //  $result2 = mail($fromEmail, $subject2, $message2, $headers2); //This confirmation email to client

             //Checking if Mails sent successfully

             if ($result1) {
               echo "Your Message was sent Successfully!";
             } else {
                echo "Sorry! Message was not sent, Try again Later.";
               print_r($result1); die;
             }

             die;

        //    }
    }
}
