<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Halaman_model
 *
 * @package		PemesananOnline
 * @category	halaman
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Halaman_model extends CI_Model {
		
	private $table;
	
	public function __construct()
	{
		
		parent::__construct();
		$this->table = 'halaman';
	}
	
	public function get_all_halaman($page = 1)
	{
		if($page !== 0)
		{
			$this->db->limit(3, ($page-1) * 3);
		}
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
	
	
	public function get_halaman_by_id($id, $array_row = TRUE)
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
	
	public function buat_halaman($data_halaman)
	{
		$data = array(
			'judul' => $data_halaman['judul'],
			'konten' => $data_halaman['konten']
		);
		
		$this->db->insert($this->table, $data);
	}
	
	public function edit_halaman_by_id($id, $data_halaman)
	{
		$data = array(
			'judul' => $data_halaman['judul'],
			'konten' => $data_halaman['konten']
		);
		
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
			
	}
	
	public function delete_halaman_by_id($id)
	{
		//delete halaman
		if(is_array($id))
		{
			$this->db->where_in('id', $id);
		}
		else
		{
			$this->db->where('id', $id);
		}
		$this->db->delete($this->table);
			
	}
	
	public function delete_all_halaman()
	{
		$this->db->empty_table($this->table);
	}
	
	public function all_rows_count()
	{
		$query = $this->db->get($this->table);
		
		return $query->num_rows();
	}
	
	public function exist_id($id)
	{
		$query = $this->db->get_where($this->table, array('id' => $id), 1);
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

/* End of file product_model.php */
/* Location: ./application/models/product_model.php */