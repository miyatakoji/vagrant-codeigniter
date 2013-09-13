<?php
class User_model extends CI_Model {

    
    public function check_email($email)
    {
        $this->db->like('email', $email); 
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



}
