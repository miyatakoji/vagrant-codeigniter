<?php

class Form extends CI_Controller {
	
	function index()
	{
			$this->load->view('entry.php');

        }
        
        function create()
        {
		$this->load->helper(array('form', 'url'));
		
		$this->load->library('form_validation');

                $this->form_validation->set_rules('name', 'ユーザ名', 'required|alpha_numeric');
		$this->form_validation->set_rules('email', 'メールアドレス', 'required|valid_email');
		$this->form_validation->set_rules('pass', 'パスワード', 'required|min_length[6]');

	
                
		if ($this->form_validation->run() == FALSE)
		{
                    echo "入力に誤りがあります！！";    
			$this->load->view('entry.php');
		}
		else
		{
		        $this->load->view('entry_check.php');
                }
                 //登録データの整理
                $name=$this->session->userdata('name');
                $email=$this->session->userdata('email');
                $passw=$this->session->userdata('passw');
                $created=date("Y-m-d H:i:s");

                 //データベースへの接続
                $this->load->database();
                //データの登録
                $sql="INSERT INTO members SET name=?,email=?,passw=?,created=?";
                $this->db->query($sql,array($name,$email,$passw,$created));
                //現在のセッションをクリアします
                $this->session->sess_destroy();
                //登録後にログインページへ移動
                $this->load->view('login');
        }
}
                
                
