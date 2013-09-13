<?php

class Form extends CI_Controller
{
    public function index()
    {
        $this->load->view('entry.php');
    }






    public function create()
    {
        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        $this->load->library('session');

        $email = $this->input->post('email');


        $this->form_validation->set_rules('name', 'ユーザ名', 'required|alpha_numeric');
        $this->form_validation->set_rules('email', 'メールアドレス', 'required|valid_email|callback_already_used_email');
        $this->form_validation->set_rules('pass', 'パスワード', 'required|min_length[6]');
        $this->form_validation->set_rules('passconf', 'パスワードの確認', 'required|min_length[6]');


        if ($this->form_validation->run() == FALSE) {
            return $this->load->view('entry.php');
        } else {
            $this->load->view('entry_check.php');
        }
    }







    public function entry()
    {

        $this->load->library('session');

        $name = $this->input->post('name');
        $email = $this->input->post('email');
        $pass = $this->input->post('pass');
        $created = date("Y-m-d H:i:s");
        
        //データベースへの接続
        $this->load->database();

        $this->load->model('user_model');
        $this->user_model->entry($name,$email,$pass,$created);
            //登録後にログインページへ移動
            redirect('form/login','location');
    }







    public function already_used_email($email)
    {
        $this->load->model('user_model');
        $user = $this->user_model->check_email($email);
        if ($user == null) {
            $this->form_validation->set_message('already_used_email', 'このメールアドレスはすでに登録されています。');
            return FALSE;
        }
        return TRUE;
    }







    public function login() 
    {
        $email =  $this->input->post('email', true);
        $password = $this->input->post('pass', true);
        if (($email == true) && ($pass == true)){
            $this->session->set_userdata('user_email','$email');
            $this->session->set_userdata('logged_in', true);
            redirect('login_check', 'location');
        } else {
            $this->load->view('login');
        }
    }
    
    public function login_ckeck() {
        $this->is_login();
        $this->load->view('login');
    }
    
    public function logout() {
    $this->session->sess_destroy();
    redirect('form/login_check', 'location');
  }
}
