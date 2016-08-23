<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Member_model
 *
 * @package		PemesananOnline
 * @category	member
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Member_model extends CI_Model {
		
	private $table;
	private $session;	
	private $id;
	private $username;
	private $nama_lengkap;
	private $status;
	private $email;
	private $no_hp;
	private $alamat;
	
	public function __construct()
	{
		
		parent::__construct();
		$this->table = 'member';
		$this->session = 'user_id';
	}
	
	public function get_all_member($page = 1)
	{
		$this->db->limit(10, ($page-1) * 10);
		$query = $this->db->get($this->table);
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return NULL;
		}
	}
	
	public function cari_id($id, $page = 1)
	{
		
		$this->db->limit(10, ($page-1) * 10);
		$this->db->where('id', $id);
		$query = $this->db->get($this->table);
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return NULL;
		}
	}
	
	public function cari_id_count($id)
	{
		$query = $this->db->get($this->table, array('id' => $id));
		return $query->num_rows();
	}
	
	public function cari_username($username, $page = 1)
	{
		$this->db->limit(10, ($page-1) * 10);
		$this->db->like('username', $username);
		$query = $this->db->get($this->table);
		
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return NULL;
		}
	}
	
	public function cari_username_count($username)
	{
		$this->db->like('username', $username);
		$query = $this->db->get($this->table);
		
		return $query->num_rows();
	}
	
	public function get_member_by_id($id, $array_row = TRUE)
	{
		$query = $this->db->get_where($this->table, array('id' => $id), 1);
		if($query->num_rows() > 0)
		{
			if($array_row === TRUE)
			{
				return $query->row_array();
			}
			else
			{
				return $query->row();
			}
		}
		else
		{
			return NULL;
		}
	}
	
	public function get_member_by_email($email, $array_row = TRUE)
	{
		$query = $this->db->get_where($this->table, array('email' => $email), 1);
		if($query->num_rows() > 0)
		{
			if($array_row === TRUE)
			{
				return $query->row_array();
			}
			else
			{
				return $query->row();
			}
		}
		else
		{
			return NULL;
		}
	}
	
	public function get_member_by_login($username, $password)
	{
		$data_member = array(
			'username' => $username,
			'password' => md5($password)
		);
		
		$query = $this->db->get_where($this->table, $data_member, 1);
		
		if($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		else
		{
			return NULL;
		}
	}
	
	public function new_member($data_member)
	{
		$data = array(
			'username' => $data_member['username'],
			'email' => $data_member['email'],
			'password' => md5($data_member['password']),
			'nama_lengkap' => $data_member['nama_lengkap'],
			'no_hp' => $data_member['no_hp'],
			'level' => 1,
			'alamat' => $data_member['alamat']
		);
		
		$this->db->insert('member', $data);
	}
	
	public function new_admin($data_admin)
	{
		$data = array(
			'username' => $data_admin['username'],
			'email' => $data_admin['email'],
			'password' => md5($data_admin['password']),
			'level' => 255
		);
		$this->db->insert('member', $data);
	}
	
	public function edit_member_by_id($id, $data_member, $from_admin = TRUE)
	{
		$data = array(
			'email' => $data_member['email'],
			'nama_lengkap' => $data_member['nama_lengkap'],
			'no_hp' => $data_member['no_hp'],
			'alamat' => $data_member['alamat']
		);
		if($from_admin === FALSE)
		{
			if(trim($data_member['password']) !== '')
			{
				$data['password'] = md5($data_member['password']);
			}
		}
		else
		{
			$data['username'] = $data_member['username'];
			$data['level'] =  $data_member['level'];
		}
		
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
			
	}
	
	public function update_password_by_id($id, $password)
	{
		$data = array(
			'password' => md5($password)
		);
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
	}
	
	public function delete_member_by_id($id)
	{
		if(is_array($id))
		{
			$this->db->where_in('id', $id);
		}
		else
		{
			$this->db->where('id', $id);
		}
		
		//biar super admin ga ikut di delete
		$this->db->where('level !=', '256');
		$this->db->delete($this->table);
			
	}
	
	public function delete_all_member()
	{
		$this->db->where('level !=', '256');
		$this->db->delete($this->table);
	}
	
	public function all_rows_count()
	{
		$query = $this->db->get($this->table);
		
		return $query->num_rows();
	}
	
	public function exist_username($username)
	{
		$query = $this->db->get_where($this->table, array('username' => $username), 1);
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function exist_email($email)
	{
		$query = $this->db->get_where($this->table, array('email' => $email), 1);
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function is_valid_token($token)
	{
		$this->db->select('id');
		$this->db->where('SHA1(member.email"-"member.password) = $token');
		$this->db->get($this->table);
	}
	
	public function ganti_password($id, $password)
	{
	}

}

/* End of file product_model.php */
/* Location: ./application/models/product_model.php */