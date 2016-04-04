<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
class Welcome extends MX_Controller
{
	
	public function __construct()
	{		
		parent::__construct();
		$this->load->model('Welcome_Model');
		
	}
	
	public function index()
	{		
		//$data['users'] = $this->data_users(); TODO
		$this->load->view('Welcome');			
	}	
	
	public function indexPost()
	{
		//$data['users'] = $this->data_users();
		//$this->load->view('Login');
		echo "--";
	}
}