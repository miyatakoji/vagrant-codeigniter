<?php

class Form extends CI_Controller
{   

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('url','cookie','security','form'));
        $this->load->library('session');
      }


    public function index()
    {
        $this->load->view('entry.php');
    }








    public function create()
    {


        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        $this->load->library('session');

        $name = $this->input->post('name',true);
        $email = $this->input->post('email',true);
        $pass = $this->input->post('pass',true);
        $this->session->set_userdata(array(
                        'name' => $name,
                        'email' => $email,
                        'pass' => $pass));


        $this->form_validation->set_rules('name', 'ユーザ名', 'required|alpha_numeric');
        $this->form_validation->set_rules('email', 'メールアドレス', 'required|valid_email|callback_already_used_email');
        $this->form_validation->set_rules('pass', 'パスワード', 'required|min_length[6]');
        $this->form_validation->set_rules('passconf', 'パスワードの確認', 'required|min_length[6]');


        if ($this->form_validation->run() == false) {
            return $this->load->view('entry.php');
        } else {
            $this->load->view('entry_check.php');
        }
    }









    public function entry()
    {

        $this->load->library('session');

        $name = $this->session->userdata('name');
        $email = $this->session->userdata('email');
        $pass = $this->session->userdata('pass');
        $created = date("Y-m-d H:i:s");
        
        $this->load->database();

        $this->load->model('User_model');
        
        $this->User_model->entry($name,$email,$pass,$created);

        $this->session->sess_destroy();
        //登録後にログインページへ移動

        $this->load->helper('url');    
        redirect('form/login','location');
    }










    private function already_used_email($email)
    {
        $this->load->model('User_model');
        $user = $this->User_model->check_email($email);
        if ($user > 0) {
            $this->form_validation->set_message('already_used_email', 'このメールアドレスは既に使われています。');
            return false;
        } else {
            return true;
        
        };
    }

    // public function go() {
    //     $this->load->view('login.php');
    // }








    public function login() 
    {
        $email = $this->input->post('email', true);
        $password = $this->input->post('pass', true);
        if (($email == true) && ($pass == true)){
            $this->session->set_userdata('user_email','$email');
            $this->session->set_userdata('logged_in', true);
            redirect('login_check', 'location');
        } else {
            $this->load->view('login');
        }
    }
    
    
    public function logout() {
    $this->session->sess_destroy();
    redirect('form/login_check', 'location');
  }



    public function tweets(){
        $this->load->view('tweets.php');
    }



}
