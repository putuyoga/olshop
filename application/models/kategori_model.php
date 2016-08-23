<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Kategori_model
 *
 * @package		PemesananOnline
 * @category	kategori
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Kategori_model extends CI_Model {
		
	private $table;
	
	public function __construct()
	{
		
		parent::__construct();
		$this->table = 'kategori';
	}
	
	public function get_all_kategori($page = 1)
	{
		if($page !== 0)
		{
			$this->db->limit(10, ($page-1) * 10);
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
	
	public function inc_dilihat($id)
	{
		$this->db->where('id', $id);
		$this->db->set('dilihat', 'dilihat+1', FALSE);
		$this->db->update($this->table);
	}
	
	public function inc_dipesan($id)
	{
		$this->db->where('id', $id);
		$this->db->set('dipesan', 'dipesan+1', FALSE);
		$this->db->update($this->table);
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
	
	public function cari_nama($nama, $page = 1)
	{
		$this->db->limit(10, ($page-1) * 10);
		$this->db->like('nama', $nama);
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
	
	public function cari_nama_count($nama)
	{
		$this->db->like('nama', $nama);
		$query = $this->db->get($this->table);
		
		return $query->num_rows();
	}
	
	public function get_kategori_by_id($id, $array_row = TRUE)
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
	
	public function get_paling_dipesan($array_row = TRUE)
	{
		$this->db->order_by('dipesan', 'desc');
		$query = $this->db->get($this->table, 1);
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
	
	public function get_paling_dilihat($array_row = TRUE)
	{
		$this->db->order_by('dilihat', 'desc');
		$query = $this->db->get($this->table, 1);
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
	
	public function get_nama_by_id($id)
	{
		if($id !== 0)
		{
			$this->db->select('nama');
			$query = $this->db->get_where($this->table, array('id' => $id), 1);
			if($query->num_rows() > 0)
			{
				$array = $query->row_array();
				return $array['nama'];
			}
			else
			{
				return 'tanpa kategori';
			}
		}
		else
		{
			return 'tanpa kategori';
		}	
	}
	
	public function buat_kategori($data_kategori)
	{
		$data = array(
			'nama' => $data_kategori['nama'],
			'deskripsi' => $data_kategori['deskripsi']
		);
		
		$this->db->insert($this->table, $data);
	}
	
	public function edit_kategori_by_id($id, $data_kategori)
	{
		$data = array(
			'nama' => $data_kategori['nama'],
			'deskripsi' => $data_kategori['deskripsi'],
		);
		
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
			
	}
	
	public function delete_kategori_by_id($id)
	{
		//delete kategori
		if(is_array($id))
		{
			$this->db->where_in('id', $id);
		}
		else
		{
			$this->db->where('id', $id);
		}
		$this->db->delete($this->table);
		
		//update produk
		if(is_array($id))
		{
			$this->db->where_in('id_kategori', $id);
		}
		else
		{
			$this->db->where('id_kategori', $id);
		}
		$data_update = array('id_kategori' => 0);
		$this->db->update('produk', $data_update);
			
	}
	
	public function delete_all_kategori()
	{
		$this->db->empty_table($this->table);
		$data_update = array('id_kategori' => 0);
		$this->db->update('produk', $data_update);
	}
	
	public function all_rows_count()
	{
		$query = $this->db->get($this->table);
		
		return $query->num_rows();
	}
	
	public function exist_nama($nama)
	{
		$query = $this->db->get_where($this->table, array('LOWER(nama)' => strtolower($nama)), 1);
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
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