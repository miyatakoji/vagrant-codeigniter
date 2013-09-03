<?php

class Form extends CI_Controller {
	
	function index()
	{
		$this->load->helper(array('form', 'url'));
		
		$this->load->library('form_validation');

                $this->form_validation->set_rules('id', 'ユーザID', 'required');  
                $this->form_validation->set_rules('name', 'ユーザ名', 'required|alpha_numeric');
		$this->form_validation->set_rules('pass', 'パスワード', 'required');
		$this->form_validation->set_rules('pass', 'パスワードの確認', 'required|min_length[6]');
		$this->form_validation->set_rules('email', 'メールアドレス', 'required|valid_email');

				
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('entry');
		}
		else
		{
			$this->load->view('entrysuccess');
		}
	}
}
?>