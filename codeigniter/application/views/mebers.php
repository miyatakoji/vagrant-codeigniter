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
	 if($this->form_validation->run('members') == FALSE)
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
}