<?php

class Members extends CI_Controller
{
 function __construct()
 {
	 parent::__construct();
	 //プロファイラを有効にする
	 $this->output->enable_profiler(TRUE);
	 $this->load->library(array('form_validation','session'));
	 $this->load->helper(array('form','url'));
 }
 function entry()
 {
	 //form_validation.phpに登録してあるmembersがFALSEなら
	 if($this->form_validation->run('create') == FALSE)
	 {
		 //ビューファイル『entry.php』を開きます
		 $this->load->view('entry');
	//フォームバリデーションが全てOKなら
	 }else{
		 //POST送信された値をセッションデータに保存します
		 $name=$_POST['name'];
		 $email=$_POST['email'];
		 $pass=$_POST['passw'];
		 
		//ビューファイル『entry_check.php』を開きます
		 $this->load->view('entry_check',$error);
	
 }


                 function db_entry()
                {
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
                }}


                //重複アカウントのチェック
                $sql="SELECT * FROM members WHERE email=?";
                $query=$this->db->query($sql,$email);
                //もしクエリの行数が1件以上あれば
                if($query->num_rows() > 0){
                        //エラーメッセージを配列$errorに代入します
                        $error=array('duplication'=>'指定されたメールアドレスは既に登録されています',
                        'error'=>'');
                        $this->load->view('entry_check',$error);
                }else{
                //データの登録
                $sql="INSERT INTO members SET name=?,email=?,passw=?,picture=?,created=?";
                $this->db->query($sql,array($name,$email,$passw,$picture,$created));
                //現在のセッションをクリアします
                $this->session->sess_destroy();
                //登録後にログインページへ移動
                $this->load->view('login');
                }