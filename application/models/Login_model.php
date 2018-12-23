<?php
class Login_model extends CI_Model{
	//cek nopek dan password approver
	function auth_login($username,$password){
		$query=$this->db->query("SELECT * FROM login WHERE username='$username' AND password=MD5('$password') LIMIT 1");
		return $query;
	}
}