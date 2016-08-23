<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Members  {
	private $CI;
	private $current_member;
	private $time_max; //dalam sekon
	public function __construct()
	{
		$this->time_max = 60 * 10; //sekon * menit = maks 10 menit
		$this->CI =& get_instance();
		$this->CI->load->model('member_model');
		$this->get_session();
		$this->counter();
	}
	
	public function counter()
	{
		if($this->CI->session->userdata('pengunjung') === FALSE)
		{
			$this->CI->load->helper('counter');
			counter();
		}
	}
	
	public function get_current_member()
	{
		return $this->current_member;
	}
	
	private function set_current_member($data_member)
	{
		$this->current_member = $data_member;
	}
	
	private function keep_session()
	{
		$diff = time() - $this->CI->session->userdata('time_start');
		if($diff > $this->time_max)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function set_session($id)
	{
	
		$this->CI->session->set_userdata('id_member', $id);
		$this->CI->session->set_userdata('time_start', time());
		$this->CI->session->set_userdata('pengunjung', true);
	}
	
	public function delete_session()
	{
		$this->CI->session->unset_userdata('id_member');
		$this->CI->session->unset_userdata('time_start');
	}
	
	private function get_session()
	{	
		$member_id = $this->CI->session->userdata('id_member');
		if($member_id !== FALSE)
		{
			//selalu cek session
			if($this->keep_session() === TRUE)
			{
				$this->set_session($member_id);
			}
			else
			{
				$this->delete_session();
				redirect('/');
			}
			$member_data = $this->CI->member_model->get_member_by_id($member_id);
			$this->set_current_member($member_data);
		}
		else
		{
			return FALSE;
		}
	}


}


/* End of file product_model.php */
/* Location: ./application/models/product_model.php */