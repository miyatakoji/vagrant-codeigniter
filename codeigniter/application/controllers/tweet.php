<?php
class Tweet extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url','cookie','form');
        $this->load->library(array('session', 'form_validation','encrypt'));
    }



    //email,passバリデーション
    public function un_entried_info($email)
    {
        $postpass = $this->input->post('pass', true);
        $pass = $this->encrypt->sha1("$postpass");
        $this->load->model('User_model');
        $get_userdata = $this->User_model->login($email, $pass);
        if ($get_userdata == null) {
        $this->form_validation->set_message('un_entried_info', 'メールアドレスかパスワードに誤りがあります');
        return false;
        } else {
        return true;
        };
    }



    //ログイン確認＆ツイート登録＆ツイート１０件表示orツイートされたデータをjsonで返す
    public function tweet_entry()
    {
        if ($this->session->userdata('USER_STATUS') != 'LOGIN'){
            return $this->load->view('login');
        }

        $tweet = $this->input->post('tweet', true);
        $name = $this->session->userdata('USERNAME');
        $tweeted = date("Y-m-d H:i:s");
        $id = $this->session->userdata('USER_ID');

        $this->form_validation->set_rules('tweet', 'ツイート', 'required');

        if ($this->form_validation->run() == false) {
            return echo "ツイートは必須です";

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


    //最新ツイートを１０件持ってくる
    public function show_tweet()
    {
        $this->load->model('User_model');
        return $this->User_model->show_tweet();
    }


    //次の１０件を持ってくる
    public function geting_moretweet()
    {
        $this->load->model('User_model');
        $oldest_tweetnumber = $this->input->get('oldest_tweetnumberdayo',true);
        $more_tentweet = $this->User_model->get_moretentweet($oldest_tweetnumber

        $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($more_tentweet));
        return;
    }
}
