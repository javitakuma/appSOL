<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Login_model extends CI_Model
{
	
	public function __construct()
	{
		
		parent::__construct();
		
	}
	
	public function get_users()
	{		
		//$query = $this->db->get('users');
		
		return "Datos desde modelo";		
	}
	
}