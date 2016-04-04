<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Login extends MX_Controller
{
	
	public function __construct()
	{		
		parent::__construct();
		$this->load->model('Login_Model');
		
	}
	
	public function index()
	{		
		//$data['users'] = $this->data_users();
		$this->load->view('Login');			
	}	
	
	public function indexPost()
	{
		//$data['users'] = $this->data_users();
		//$this->load->view('Login');
		echo "--";
	}
}