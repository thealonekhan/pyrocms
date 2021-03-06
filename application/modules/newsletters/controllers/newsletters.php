<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Newsletters extends Public_Controller {

    function __construct() {
        parent::Public_Controller();
        $this->load->model('newsletters_m');
    }

    function index() {
        $this->data->newsletters = $this->newsletters_m->getNewsletters(array('order'=>'created_on DESC'));
        $this->layout->create('index', $this->data);
    }
    
	function archive($id = '') {
        $this->data->newsletter = $this->newsletters_m->getNewsletter($id);
        if ($this->data->newsletter) {
            $this->layout->create('view', $this->data);
        } else {
            show_404();
        }
    }
    
    // Public: Register for newsletter
    function subscribe() {
        $this->load->library('validation');
        $rules['email'] = 'trim|required|valid_email';
        $this->validation->set_rules($rules);
        $this->validation->set_fields();
        
        if ($this->validation->run()) {
            if ($this->newsletters_m->subscribe($_POST)) {
                redirect('newsletters/subscribed');
                return;
            } else {
                $this->session->set_flashdata(array('error'=>'This address has already been registered.'));
                redirect('');
                return;
            }
        } else {
            $this->session->set_flashdata(array('notice'=>$this->validation->error_string));
            redirect('');
            return;
        }
    }
    
    // Public: Register for newsletter
    function subscribed() {
    	$this->layout->create('subscribed', $this->data);
    }
    
    // Public: Unsubscribe from newsletter
    function unsubscribe($email = '') {
        if (!$email) redirect('');
        if ($this->newsletters_m->unsubscribe($email)) {
            $this->session->set_flashdata(array('success'=>'You have been removed from the newsletter mailing list.'));
            redirect('');
            return;
        } else {
            $this->session->set_flashdata(array('notice'=>'An error occurred.'));
            redirect('');
            return;
        }
    }

}
?>