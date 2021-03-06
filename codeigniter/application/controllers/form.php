<?php
class Form extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'cookie', 'security', 'form'));
        $this->load->library(array('session', 'form_validation','encrypt'));
    }



    //会員登録機能（バリデーション）
    public function create()
    {

        $this->form_validation->set_rules('name', 'ユーザ名', 'required|alpha_numeric');
        $this->form_validation->set_rules('email', 'メールアドレス', 'required|valid_email|callback_already_used_email');
        $this->form_validation->set_rules('pass', 'パスワード', 'required|min_length[6]');
        $this->form_validation->set_rules('passconf', 'パスワードの確認', 'required|min_length[6]');

        if ($this->form_validation->run() == false) {
            return $this->load->view('entry.php');
        } else {
            $name = $this->input->post('name', true);
            $email = $this->input->post('email', true);
            $postpass = $this->input->post('pass', true);
            $pass = $this->encrypt->sha1("$postpass");
            $this->session->set_userdata(array(
                'name' => $name,
                'email' => $email,
                'postpass' => $postpass,
                'pass' => $pass));

            $this->load->view('entry_check.php');
        }
    }


    //確認後の本登録
    public function entry()
    {
        $name = $this->session->userdata('name');
        $email = $this->session->userdata('email');
        $pass = $this->session->userdata('pass');
        $created = date("Y-m-d H:i:s");
        
        $this->load->model('User_model');
        
        $this->User_model->entry($name,$email,$pass,$created);

        $this->session->sess_destroy();

        redirect('form/login', 'location');
    }


    //emailバリデーション(callback関数)
    public function already_used_email($email)
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



 public function login()
    {
        $email = $this->input->post('email', true);
        $postpass = $this->input->post('pass', true);
        $pass = $this->encrypt->sha1("$postpass");
        $this->form_validation->set_rules('pass', 'パスワード', 'required');
        $this->form_validation->set_rules('email', 'メールアドレス', 'required|valid_email|callback_un_entried_info');

        if ($this->form_validation->run() == false) {
            return $this->load->view('login');
        }

        $this->load->model('User_model');
        $get_userdata = $this->User_model->login($email, $pass);
        if (($get_userdata['email'] === $email)&&($get_userdata['pass'] === $pass)) {
            $this->session->set_userdata('USERNAME', $get_userdata['name']);
            $this->session->set_userdata('USER_STATUS', 'LOGIN');
            $this->session->set_userdata('USER_ID',$get_userdata['id']);
            
            return $this->load->view('login_success');
        }
    }



    //ログアウト
    public function logout() 
    {
        $this->session->sess_destroy();
        redirect('form/login', 'location');
    }



    //ログイン後ここに移動する
    public function toppage()
    {
        $USER_STATUS = $this->session->userdata('USER_STATUS');
        if ( $USER_STATUS = 'LOGIN') {
            $ten_tweets = $this->show_tweet();
            $data['ten_tweets'] = $ten_tweets;
            return $this->load->view('toppage', $data);
        } else {
            $this->load->view('login');
        }
    }


 //ログイン確認＆ツイート登録＆ツイート１０件表示orツイートされたデータをjsonで返す
    public function tweet_entry()
    {
        if ($this->session->userdata('USER_STATUS') != 'LOGIN'){
            return $this->load->view('login');
        }

        $tweet = $this->input->post('tweettext', true);
        $name = $this->session->userdata('USERNAME');
        $tweeted = date("Y-m-d H:i:s");
        $id = $this->session->userdata('USER_ID');

        $this->form_validation->set_rules('tweettext', 'ツイート', 'required|max_length[140]');

        if ($this->form_validation->run() == false) {

            echo validation_errors();
            return;
            // $ten_tweets = $this->show_tweet();
            // $data['ten_tweets'] = $ten_tweets;
            // return $this->load->view('toppage', $data);
        }

        $this->load->model('User_model');

        $this->User_model->tweet_entry($name,$tweet,$tweeted,$id);

        $tweet_info = $this->User_model->get_tweetinfo();

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($tweet_info));
        return;
    }




    //次の１０件を持ってくる
    public function geting_moretweet()
    {
        $this->load->model('User_model');
        $oldest_tweetnumber = $this->input->get('oldest_tweetnumberdayo',true);
        $more_tentweet = $this->User_model->get_moretentweet($oldest_tweetnumber);

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($more_tentweet));
        return;
    }



    //最新ツイートを１０件持ってくる
    public function show_tweet()
    {
        $this->load->model('User_model');
        return $this->User_model->show_tweet();
    }
}
