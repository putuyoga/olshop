<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Pemesanan
 *
 * @package		PemesananOnline
 * @subpackage	backend
 * @category	pemesanan
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Pemesanan extends ADM_Controller {
	
	public function __construct() {
		parent::__construct('pemesanan');
		$this->load->model('pemesanan_model');
		$this->load->helper('string');
		$this->load->helper('form');
	}
	
	function _remap($method)
	{
		$param_offset = 2;

		if ( ! method_exists($this, $method))
		{
			$param_offset = 1;
			$method = 'index';
		}

		$params = array_slice($this->uri->rsegment_array(), $param_offset);

		call_user_func_array(array($this, $method), $params);
	}
	
	public function index($page = 1)
	{
		if(is_numeric($page) === FALSE)
		{
			$page = 1;
		}
		
		$this->cek_hapus();
		if($this->input->post('do_cari') !== FALSE)
		{
			$post = $this->input->post();
			redirect('administrator/pemesanan/cari/' . $post['pilihan_cari'] . '/' . $post['input_cari']);
		}
		
		//setup page links
		$this->load->library('pagination');

		$config['base_url'] = base_url() . 'index.php/administrator/pemesanan';
		$config['total_rows'] = $this->pemesanan_model->all_rows_count();
		$config['use_page_numbers'] = TRUE;
		$config['per_page'] = 10; 

		$this->pagination->initialize($config);
		$data_view['pagelink'] = $this->pagination->create_links();
		$data_view['pilihan'] = '';
		$data_view['input_cari'] = '';
		
		$data_view['rows'] = $this->pemesanan_model->get_all_pesanan($page);
		$this->set_judul('list');
		$main = $this->load->view('backend/pemesanan/main', $data_view, TRUE);
		$this->tampil($main);
	}
	
	public function cari($pilihan = 'id', $input, $page = 1)
	{
		
		$this->cek_hapus();
		
		switch($pilihan)
		{
			case 'id':
				$data_view['rows'] = $this->pemesanan_model->cari_id($input, $page);
				$config['total_rows'] = $this->pemesanan_model->cari_id_count($input);
			break;
			case 'username':
				$data_view['rows'] = $this->pemesanan_model->cari_pemesan($input, $page);
				$config['total_rows'] = $this->pemesanan_model->cari_pemesan_count($input);
			break;
			default:
				redirect('administrator/pemesanan');
			break;
		}
		
		if( ! is_numeric($page))
		{
			redirect('administrator/pemesanan');
		}
		
		if($data_view['rows'] !== NULL)
		{
			//setup page links
			$this->load->library('pagination');

			$config['base_url'] = base_url() . 'index.php/administrator/pemesanan';
			$config['use_page_numbers'] = TRUE;
			$config['per_page'] = 10; 

			$this->pagination->initialize($config);
			$data_view['pagelink'] = $this->pagination->create_links();
		
			$this->set_judul('pencarian ' . $pilihan);
			$data_view['pilihan'] = $pilihan;
			$data_view['input_cari'] = $input;
			$main = $this->load->view('backend/pemesanan/main', $data_view, TRUE);
			$this->tampil($main);
		}
		else
		{
			$this->set_flash_feedback('Hasil pencarian tidak ada', 'info');
			redirect('administrator/pemesanan');
		}
	}

	public function cek_hapus()
	{
		if($this->input->post('do_hapus') !== FALSE)
		{
			$id = $this->input->post('id_hapus');
			if($id !== FALSE)
			{
				$this->pemesanan_model->delete_pesanan_by_id($id);
				$this->set_feedback('Berhasil hapus ' . count($id) . ' pesanan', 'sukses');
			}
			else
			{
				$this->set_feedback('Pilih pesanan yang akan dihapus !', 'error');
			}
		}
	}
	
	public function hapus($id = '')
	{
		if(is_numeric($id))
		{
			$this->pemesanan_model->delete_pesanan_by_id($id);
			$this->set_flash_feedback('Berhasil hapus pesanan', 'sukses');
		}
		redirect('/administrator/pemesanan');
	}
	
	public function hapus_semua()
	{
		$this->pemesanan_model->delete_all_pesanan();
		$this->set_flash_feedback('Sukses hapus semua pesanan', 'sukses');
		redirect('administrator/pemesanan');
	}
	
	public function cek_rubah_status($status)
	{
		$error_array = array();
		if(($status >= 0 && $status <= 2) === FALSE)
		{
			$error[] = 'Status value harus berupa angka';
		}
		if(empty($error_array) === TRUE)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function lihat($id = '')
	{		
		if($id !== '')
		{
			$this->set_judul('lihat');

			//cek post rubah status
			if($this->input->post('do_rubah_status'))
			{
				$data_post = $this->input->post();
				$allow_rubah = $this->cek_rubah_status($data_post);
				if($allow_rubah === TRUE)
				{
					$this->pemesanan_model->rubah_status_pesanan($id, $data_post['status']);
					$this->set_feedback('Sukses rubah status pesanan', 'sukses');
				}
				else
				{
					$this->set_feedback($allow_rubah, 'error');
				}
				
			}
			
			//cek post hapus produk
			if($this->input->post('do_hapus'))
			{
				$data_post = $this->input->post();
				$this->pemesanan_model->delete_produk($id, $data_post['id_produk']);
				$this->set_feedback('Sukses hapus produk dari pesanan', 'sukses');
			}
			
			$data_view = $this->pemesanan_model->get_pesanan_by_id($id);
			
			if($data_view !== NULL)
			{
				$this->load->model('member_model');
				$this->load->model('produk_model');
				
				$data_view['pesanan'] = $this->pemesanan_model->get_detail_by_id($id);
				$data_view['banyak_barang'] = $this->pemesanan_model->banyak_barang_by_id($id)['kuantitas'];
				$form = $this->load->view('backend/pemesanan/lihat_pesanan', $data_view, TRUE);
				$this->tampil($form);
			}
			else
			{
				$this->set_flash_feedback('Tidak ada pesanan dengan id ' . $id, 'error');
				redirect('administrator/pemesanan');
			}
		}
		else
		{
			redirect('administrator/pemesanan');
		}
	}
	
}

/* End of file members.php */
/* Location: ./application/controllers/members.php */