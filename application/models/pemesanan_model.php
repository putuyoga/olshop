<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Pemesanan_model
 *
 * @package		PemesananOnline
 * @category	pemesanan
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Pemesanan_model extends CI_Model {
		
	private $table;
	
	public function __construct()
	{
		
		parent::__construct();
		$this->table = 'pemesanan';
	}
	
	public function get_all_pesanan($page = 1, $id_member = '')
	{
		$this->db->select('(SELECT username FROM member WHERE id = pemesanan.id_member LIMIT 1) as pemesan, ' . 
		'(SELECT COUNT(*) FROM detail_pemesanan WHERE id_pemesanan = pemesanan.id ) as banyak_produk, ' . 
		'id, tanggal, harga_total, status, catatan', FALSE);
		if(trim($id_member) !== '')
		{
			$this->db->where('id_member', $id_member);
		}
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
	
	public function cari_id($id, $page = 1)
	{
		
		$this->db->limit(10, ($page-1) * 10);
		$this->db->select('(SELECT username FROM member WHERE id = pemesanan.id_member LIMIT 1) as pemesan, ' . 
		'(SELECT COUNT(*) FROM detail_pemesanan WHERE id_pemesanan = pemesanan.id ) as banyak_produk, ' . 
		'id, tanggal, harga_total, status, catatan', FALSE);
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
	
	public function cari_pemesan($username, $page = 1)
	{
		$this->db->limit(10, ($page-1) * 10);
		$this->db->select('(SELECT username FROM member WHERE id = pemesanan.id_member LIMIT 1) as pemesan, ' . 
		'(SELECT COUNT(*) FROM detail_pemesanan WHERE id_pemesanan = pemesanan.id ) as banyak_produk, ' . 
		'id, tanggal, harga_total, status, catatan', FALSE);
		$this->db->where(" id_member IN (SELECT id FROM member WHERE username like '%". $username ."%')", NULL, FALSE);
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
	
	public function cari_pemesan_count($username)
	{
		$this->db->where_in('id_member', "(SELECT id FROM member WHERE username like '%". $username ."%'");
		$query = $this->db->get($this->table);
		
		return $query->num_rows();
	}
	
	public function get_pesanan_by_id($id, $array_row = TRUE)
	{
		$this->db->select('pemesanan.id as id_pesanan, pemesanan.harga_total as harga_total, ' . 
		'pemesanan.status as status, member.username as username, pemesanan.catatan as catatan, ' . 
		'member.id as id_member');
		$this->db->join('member', 'member.id = '. $this->table .'.id_member');
		$query = $this->db->get_where($this->table, array($this->table . '.id' => $id), 1);
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
	
	public function get_detail_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('detail_pemesanan');
		$this->db->join('produk', 'produk.id = detail_pemesanan.id_produk');
		$this->db->where('id_pemesanan', $id);
		
		$query = $this->db->get();
		if($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return NULL;
		}
	}
	
	
	public function banyak_barang_by_id($id)
	{
		$this->db->select_sum('kuantitas');
		$query = $this->db->get_where('detail_pemesanan', array('id_pemesanan' => $id));
		$count = $query->row_array();
		if($count['kuantitas'] !== NULL)
		{
			return $count;
		}
		else
		{
			return array('kuantitas' => 0);
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
				return $query->row_array()['nama'];
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
	
	private function hitung_total($array_detail)
	{
		$total = 0;
		foreach($array_detail as $detail)
		{
			$total += $detail['qty'] * $detail['price'];
		}
		return $total;
	}
	
	public function buat_pemesanan($id_member, $catatan, $array_detail)
	{
		$data = array(
			'id_member' => $id_member,
			'tanggal' => date('Y-m-d H:i:s'),
			'catatan' => $catatan,
			'harga_total' => $this->hitung_total($array_detail)
		);
		
		$this->db->insert($this->table, $data);
		$this->insert_detail($this->db->insert_id(), $array_detail);
		$this->inc_dipesan($array_detail);
	}
	
	private function insert_detail($id_pemesanan, $array_detail)
	{
		foreach($array_detail as $detail)
		{
			$harga_total = $detail['qty'] * $detail['price'];
			$data = array(
				'id_pemesanan' => $id_pemesanan,
				'id_produk' => $detail['id'],
				'kuantitas' => $detail['qty'],
				'harga_total' => $harga_total
			);
			$this->db->insert('detail_pemesanan', $data);
		}
	}
	
	private function inc_dipesan($array_detail)
	{
		$this->load->model('kategori_model');
		$this->load->model('produk_model');
		foreach($array_detail as $detail)
		{
			$produk = $this->produk_model->get_produk_by_id($detail['id']);
			if($produk !== NULL)
			{
				$this->produk_model->inc_dipesan($detail['id']);
				$kategori = $this->kategori_model->get_kategori_by_id($produk['id_kategori']);
				if($kategori !== NULL)
				{
					$this->kategori_model->inc_dipesan($kategori['id']);
				}
			}
		}
		
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
	
	public function delete_pesanan_by_id($id)
	{
		//delete pemesanan
		if(is_array($id))
		{
			$this->db->where_in('id', $id);
		}
		else
		{
			$this->db->where('id', $id);
		}
		$this->db->delete($this->table);
		
		//delete detail_pemesanan
		if(is_array($id))
		{
			$this->db->where_in('id_pemesanan', $id);
		}
		else
		{
			$this->db->where('id_pemesanan', $id);
		}
		$this->db->delete('detail_pemesanan');
	}
	
	public function delete_produk($id, $id_produk)
	{
		$where = array(
			'id_pemesanan' => $id,
			'id_produk' => $id_produk
		);
		$this->db->delete('detail_pemesanan', $where);
	}
	
	public function delete_all_pesanan()
	{
		$this->db->empty_table($this->table);
		$this->db->empty_table('detail_pemesanan');
	}
	
	public function all_rows_count()
	{
		$query = $this->db->get($this->table);
		
		return $query->num_rows();
	}
	
	public function rubah_status_pesanan($id, $status)
	{
		$data = array(
			'status' => $status
		);
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
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