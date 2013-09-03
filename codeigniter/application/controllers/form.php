<?php

class Form extends CI_Controller {
	
	function index()
	{
		$this->load->helper(array('form', 'url'));
		
		$this->load->library('form_validation');

                $this->form_validation->set_rules('id', '���[�UID', 'required');  
                $this->form_validation->set_rules('name', '���[�U��', 'required|alpha_numeric');
		$this->form_validation->set_rules('pass', '�p�X���[�h', 'required');
		$this->form_validation->set_rules('pass', '�p�X���[�h�̊m�F', 'required|min_length[6]');
		$this->form_validation->set_rules('email', '���[���A�h���X', 'required|valid_email');

				
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