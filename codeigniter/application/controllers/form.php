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
		$this->form_validation->set_rules('passconf', 'パスワードの確認', 'required|min_length[6]');
                $this->form_validation->set_rules('pass', 'パスワード', 'required|min_length[6]');

	
                
        if ($this->form_validation->run() == FALSE)
	{  
                return $this->load->view('entry.php');
	}
		else
	{ 
                $this->load->view('entry_check.php');
                }
        }
        
        function db_entry()
        {
               
                $this->load->library('session');
                
                
                $name=$_POST['name'];
                $email=$_POST['email'];
                $pass=$_POST['pass'];
                $passconf=$_POST['passconf'];
                 

                //登録データの整理
                $name=$this->session->userdata('name');
                $email=$this->session->userdata('email');
                $pass=$this->session->userdata('pass');
                $created=date("Y-m-d H:i:s");

                //データベースへの接続
                $this->load->database();
                //データの登録
                $sql="INSERT INTO account SET name='$name' , email='$email' , pass='$pass' , created='$created'" ;
                $this->db->query($sql,array($name,$email,$pass,$created)); 
                //現在のセッションをクリアします
                $this->session->sess_destroy();
                //登録後にログインページへ移動
                $this->load->view('login');
        }
}
                
                
