<?php

class Tweet extends CI_Controller
{
	public function __construct()
  {
	    parent::__construct();
	    $this->load->helper('url','cookie','form');
	    $this->load->library(array('session', 'form_validation'));
	}







  public function login_check() 
  {
    $USER_STATUS = $this->session->userdata('USER_STATUS');
    if ($USER_STATUS != 'LOGIN') {
      redirect('tweet/login', 'location');
    } else {

    }
  }







	public function toppage()
  { 
  	$email = $this->input->post('email', true);
  	$pass = $this->input->post('pass', true);
    $this->form_validation->set_rules('email', 'メールアドレス', 'required|valid_email|callback_un_entried_email');
    $this->form_validation->set_rules('pass','パスワード','required');

    if ($this->form_validation->run() == false) {
      return $this->load->view('login');
    };

    $this->load->model('User_model');
    $get_userdata = $this->User_model->login($email);
    if (($get_userdata['email'] === $email)&&($get_userdata['pass'] === $pass)) {
      $this->session->set_userdata('USERNAME', $get_userdata['name']);
      $this->session->set_userdata('USER_STATUS', 'LOGIN');
      $this->session->set_userdata('USER_ID',$get_userdata['id']);
      $this->load->view('toppage');
    } else {
      $this->load->view('login');
    };
	}







	public function un_entried_email($email)
  {
		$this->load->model('User_model');
      $user = $this->User_model->check_email($email);
      if ($user === 0) {
      $this->form_validation->set_message('un_entried_email', 'このメールアドレスは登録されていません。');
      return false;
      } else {
      return true;
      };
	}



  public function logout() 
  {
      $this->session->sess_destroy();
      redirect('tweet/toppage', 'location');
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



  public function tweet_entry()
  {
      $tweet = $this->input->post('tweet', true);
      $id = $this->session->userdata('USER_ID');
      $tweeted_date = date("Y-m-d H:i:s");

      $this->form_validation->set_rules('tweet', 'ツイート', 'required|max_length[140]');

      if ($this->form_validation->run() == false) {
        return $this->load->view('toppage');
      }


      $this->load->model('User_model');
      
      $this->User_model->tweet_entry($id,$tweet,$tweeted_date);

      $ten_tweets = $this->show_tweet();
      $data['ten_tweets'] = $ten_tweets;

      $this->load->view('toppage', $data);
      var_dump($ten_tweets);

  }


  public function show_tweet()
  {
      $this->load->model('User_model');
      return $this->User_model->show_tweet();
  }




	// public function index()
 //  {
 //    	$this->login_check();
 //    	$this->load->view('view_tweet');
 //  		}






}
