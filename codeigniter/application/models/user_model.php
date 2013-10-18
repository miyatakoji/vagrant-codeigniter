<?php
class User_model extends CI_Model
{
    //会員登録の時のemail重複確認
    public function check_email($email)
    {
        $this->db->select('email');
        $this->db->from('accounts');
        $this->db->where('email', $email); 
        $query = $this->db->get();

        return $query->num_rows();
    }



    //会員登録
    public function entry($name, $email, $pass, $created)
    {
        $sql = "INSERT INTO accounts SET name=?, email=?, pass=?, created=?";
        $this->db->query($sql, array($name, $email, $pass, $created));
    }



    //ログイン
    public function login($email, $pass)
    {
        $this->db->select('email, name, id, pass');
        $this->db->from('accounts');
        $this->db->where('email', $email);
        $this->db->where('pass', $pass);
        $query = $this->db->get();

        return $row = $query->row_array();
    }



    //ツイート投稿
    public function tweet_entry($name,$tweet,$tweeted,$id)
    {
        $sql = "INSERT INTO tweets SET name=?,tweet=?,tweeted=?,id=?";
        $this->db->query($sql, array($name,$tweet,$tweeted,$id));
    }



    //最新１０件のツイートを返す
    public function show_tweet()
    {
        $this->db->select('tweet, tweeted, name, tweet_id');
        $this->db->order_by("tweeted", 'DESC');
        $query = $this->db->get('tweets');

        return $result = $query->result_array();
    }



    //ツイートされた内容を登録して、その情報を返す
    public function get_tweetinfo()
    {
        $tweet_id = $this->db->count_all('tweets');

        $this->db->where('tweet_id', $tweet_id);

        $query = $this->db->get('tweets');
        return $tweet_info = $query->row_array();
    }



    //指定されたツイートIDから新しい順に１０個持ってくる
    public function get_moretentweet($oldest_tweetnumber)
    {
        $this->db->select('tweet, tweeted, name, tweet_id');
        $this->db->order_by("tweeted", "desc");
        $this->db->where('tweet_id', 156);
        $query = $this->db->get('tweets', 10);

        return $more_tentweet = $query->result_array();
    }
}
