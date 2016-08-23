<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Produk_model
 *
 * @package		PemesananOnline
 * @category	produk
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Produk_model extends CI_Model {
		
	private $table;
	private $session;
	
	public function __construct()
	{
		
		parent::__construct();
		$this->table = 'produk';
		$this->session = 'user_id';
	}
	
	public function get_all_produk($page = 1)
	{
		$this->db->limit(10, ($page-1) * 10);
		$this->db->select('(SELECT nama FROM kategori WHERE id = produk.id_kategori LIMIT 1) as kategori, id, nama, harga, deskripsi, tersedia, dilihat, dipesan', FALSE);
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
		$this->db->select('(SELECT nama FROM kategori WHERE id = produk.id_kategori LIMIT 1) as kategori, id, nama, harga, deskripsi, tersedia, dilihat, dipesan', FALSE);
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
		$this->db->select('(SELECT nama FROM kategori WHERE id = produk.id_kategori LIMIT 1) as kategori, id, nama, harga, deskripsi, tersedia, dilihat, dipesan', FALSE);
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
	
	public function get_latest_produk($page = 1)
	{
		$this->db->limit(12, ($page-1) * 12);
		$this->db->order_by('id DESC');
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
	
	public function latest_produk_count()
	{
		$query = $this->db->get($this->table);
		return $query->num_rows();
	}
	
	public function get_latest_produk_by_kategori($id, $page = 1)
	{
		$this->db->limit(12, ($page-1) * 12);
		$this->db->order_by('id DESC');
		$this->db->where('id_kategori', $id);
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
	
	public function latest_produk_by_kategori_count($id)
	{
		$this->db->where('id_kategori', $id);
		$query = $this->db->get($this->table);
		return $query->num_rows();
	}
	
	public function get_produk_by_id($id, $array_row = TRUE)
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
	
	public function buat_produk($data_produk)
	{
		$data = array(
			'nama' => $data_produk['nama'],
			'id_kategori' => $data_produk['id_kategori'],
			'deskripsi' => $data_produk['deskripsi'],
			'harga' => $data_produk['harga'],
		);
		
		$this->db->insert($this->table, $data);
	}

	public function edit_produk_by_id($id, $data_produk)
	{
		$data = array(
			'nama' => $data_produk['nama'],
			'id_kategori' => $data_produk['id_kategori'],
			'harga' => $data_produk['harga'],
			'tersedia' => $data_produk['tersedia'],
			'deskripsi' => $data_produk['deskripsi']
		);
		
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
			
	}
	
	public function get_last_id()
	{
		return $this->db->insert_id();
	}
	
	public function delete_produk_by_id($id)
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
		$this->db->delete($this->table);
			
	}
	
	public function delete_all_produk()
	{
		$this->db->empty_table($this->table);
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


}

/* End of file product_model.php */
/* Location: ./application/models/product_model.php */