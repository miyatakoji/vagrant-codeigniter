class Members extends CI_Controller
{
 function __construct()
 {
	 parent::__construct();
	 //�v���t�@�C����L���ɂ���
	 $this->output->enable_profiler(TRUE);
	 $this->load->library(array('form_validation','session'));
	 $this->load->helper(array('form','url'));
 }
 function entry()
 {
	 //form_validation.php�ɓo�^���Ă���members��FALSE�Ȃ�
	 if($this->form_validation->run('members') == FALSE)
	 {
		 //�r���[�t�@�C���wentry.php�x���J���܂�
		 $this->load->view('entry');
	//�t�H�[���o���f�[�V�������S��OK�Ȃ�
	 }else{
		 //POST���M���ꂽ�l���Z�b�V�����f�[�^�ɕۑ����܂�
		 $name=$_POST['name'];
		 $email=$_POST['email'];
		 $pass=$_POST['passw'];
		 
		//�r���[�t�@�C���wentry_check.php�x���J���܂�
		 $this->load->view('entry_check',$error);
	
 }
}