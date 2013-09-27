<?php
class User_model extends CI_Model {
    var $title = 'User_model';
    
    public function check_email($email)
    {
        $this->db->select('email');
        $this->db->from('accounts');
        $this->db->where('email', $email); 
        $query = $this->db->get();

        return $query->num_rows();
    }


    public function check_pass($pass)
    {
        $this->db->like('pass', $pass); 
    }



    public function entry($name,$email,$pass,$created)
    {
        $sql = "INSERT INTO accounts SET name=?,email=?,pass=?,created=?";
        $this->db->query($sql, array($name, $email, $pass, $created));
    }



    public function login($email)
    {
        $this->db->select('email','name','id','pass');
        $this->db->from('accounts');
        $this->db->where('email',$email);
        $query = $this->db->get();

        return $query->result_array();

      
}

}
