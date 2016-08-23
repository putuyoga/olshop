<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Produk
 *
 * @package		PemesananOnline
 * @subpackage	frontend
 * @category	produk
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Produk extends ADM_Controller {
	
	public function __construct() {
		parent::__construct('produk');
		$this->load->model('produk_model');
		$this->load->model('kategori_model');
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
		if( ! is_numeric($page))
		{
			redirect('administrator/produk');
		}
		
		$this->cek_hapus();
		
		if($this->input->post('do_cari') !== FALSE)
		{
			$post = $this->input->post();
			redirect('administrator/produk/cari/' . $post['pilihan_cari'] . '/' . $post['input_cari']);
		}
		
		//setup page links
		$this->load->library('pagination');

		$config['base_url'] = base_url('index.php/administrator/produk/');
		$config['total_rows'] = $this->produk_model->all_rows_count();
		$config['use_page_numbers'] = TRUE;
		$config['per_page'] = 10; 

		$this->pagination->initialize($config);
		$data_view['pagelink'] = $this->pagination->create_links();
		$data_view['pilihan'] = '';
		$data_view['input_cari'] = '';
		
		$data_view['rows'] = $this->produk_model->get_all_produk($page);
		$this->set_judul('list');
		$main = $this->load->view('backend/produk/main', $data_view, TRUE);
		$this->tampil($main);
	}

	public function cari($pilihan = 'id', $input, $page = 1)
	{
		
		$this->cek_hapus();
		
		switch($pilihan)
		{
			case 'id':
				$data_view['rows'] = $this->produk_model->cari_id($input, $page);
				$config['total_rows'] = $this->produk_model->cari_id_count($input);
			break;
			case 'nama':
				$data_view['rows'] = $this->produk_model->cari_nama($input, $page);
				$config['total_rows'] = $this->produk_model->cari_nama_count($input);
			break;
			default:
				redirect('administrator/produk');
			break;
		}
		
		if( ! is_numeric($page))
		{
			redirect('administrator/produk');
		}
		
		if($data_view['rows'] !== NULL)
		{
			//setup page links
			$this->load->library('pagination');

			$config['base_url'] = base_url() . 'index.php/administrator/produk';
			$config['use_page_numbers'] = TRUE;
			$config['per_page'] = 10; 

			$this->pagination->initialize($config);
			$data_view['pagelink'] = $this->pagination->create_links();
		
			$this->set_judul('pencarian ' . $pilihan);
			$data_view['pilihan'] = $pilihan;
			$data_view['input_cari'] = $input;
			$main = $this->load->view('backend/produk/main', $data_view, TRUE);
			$this->tampil($main);
		}
		else
		{
			$this->set_flash_feedback('Hasil pencarian tidak ada', 'info');
			redirect('administrator/produk');
		}
	}
	
	private function cek_hapus()
	{
		if($this->input->post('do_hapus') !== FALSE)
		{
			$id = $this->input->post('id_hapus');
			if($id !== FALSE)
			{
				$this->produk_model->delete_produk_by_id($id);
				$this->set_feedback('Berhasil hapus ' . count($id) . ' produk', 'sukses');
			}
			else
			{
				$this->set_feedback('Pilih produk yang akan dihapus !', 'error');
			}
		}
	}
	
	public function hapus_semua()
	{
		$this->produk_model->delete_all_produk();
		$this->set_flash_feedback('Sukses hapus semua produk', 'sukses');
		redirect('administrator/produk');
	}
	
	public function cek_edit($data_edit, $data_before)
	{
		extract($data_edit);
		$array_error = array();
		
		//cek nama
		if(trim($nama) === '')
		{
			$array_error[] = 'Nama produk tidak boleh kosong.';
		}
		else if($this->produk_model->exist_nama($nama) === TRUE)
		{
			if($data_before['nama'] !== $nama)
			{
				$array_error[] = 'Nama sudah ada.';
			}
		}
		
		//cek harga
		if(trim($harga) === '')
		{
			$array_error[] = 'Harga tidak boleh kosong';
		}
		else if(is_numeric($harga) === FALSE)
		{
			$array_error[] = 'Harga harus berupa angka';
		}
		
		//Cek kategori
		if($id_kategori !== '0')
		{
			if($this->kategori_model->exist_id($id_kategori) === FALSE)
			{
				$array_erorr[] = 'Id Kategori invalid';
			}
		}
		
		//final cek
		if(empty($array_error) === FALSE)
		{
			return $array_error;
		}
		else
		{
			return TRUE;
		}
	}
	
	private function upload_foto($id = '')
	{
		if( ! is_numeric($id))
		{
			redirect('administrator/produk');
		}
		else
		{
			$data_view = $this->produk_model->get_produk_by_id($id);
			if($data_view === NULL)
			{
				redirect('administrator/produk');
			}
			
			$config['upload_path'] = './images/produk/';
			$config['allowed_types'] = 'jpg';
			$config['max_width']  = '250';
			$config['max_height']  = '250';
			$config['overwrite'] = TRUE;
			$config['file_name'] = $id;
		
			$this->set_judul('upload foto &raquo; ' . $data_view['nama']);
			
			if($this->input->post('upload_foto'))
			{
				//foto kecil
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('foto'))
				{
					$this->set_feedback($this->upload->display_errors('',''), 'error');
				}
				else
				{
					$this->set_feedback('Sukses upload foto kecil', 'sukses');
				}
			}
			else if($this->input->post('upload_foto_large'))
			{
				//foto besar
				$config['file_name'] = $id . '_large';
				$config['max_width']  = '700';
				$config['max_height']  = '302';
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('foto_large'))
				{
					$this->set_feedback($this->upload->display_errors('',''), 'error');
				}
				else
				{
					$this->set_feedback('Sukses upload foto besar', 'sukses');
				}
			}
			
			$form = $this->load->view('backend/produk/upload_foto', $data_view, TRUE);
			$this->tampil($form);
		}
	}
	
	public function edit($id = '')
	{		
		if($id !== '')
		{
			$this->set_judul('edit');
			$data_view = $this->produk_model->get_produk_by_id($id);
			$data_view['kategori'] = $this->kategori_model->get_all_kategori();
			
			//cek post
			if($this->input->post('do_edit'))
			{
				$data_post = $this->input->post();
				$allow_edit = $this->cek_edit($data_post, $data_view);
				if($allow_edit === TRUE)
				{
					$this->produk_model->edit_produk_by_id($id, $data_post);
					$this->set_flash_feedback('Sukses edit produk', 'sukses');
					redirect('administrator/produk/edit/' . $id);
				}
				else
				{
					$this->set_feedback($allow_edit, 'error');
				}
				
			}
			
			if($data_view !== NULL)
			{
				$form = $this->load->view('backend/produk/edit_produk', $data_view, TRUE);
				$this->tampil($form);
			}
			else
			{
				$this->set_flash_feedback('Tidak ada produk dengan id ' . $id, 'error');
				redirect('administrator/produk');
			}
		}
		else
		{
			redirect('administrator/member');
		}
	}
	
	private function cek_baru($data_produk)
	{
		extract($data_produk);
		$array_error = array();
		
		//cek nama
		if(trim($nama) === '')
		{
			$array_error[] = 'Nama produk tidak boleh kosong.';
		}
		else if($this->produk_model->exist_nama($nama) === TRUE)
		{
			$array_error[] = 'Nama sudah ada.';
		}
		
		//cek harga
		if(trim($harga) === '')
		{
			$array_error[] = 'Harga tidak boleh kosong';
		}
		else if(is_numeric($harga) === FALSE)
		{
			$array_error[] = 'Harga harus berupa angka';
		}
		
		//Cek kategori
		if($id_kategori !== '0')
		{
			if($this->kategori_model->exist_id($id_kategori) === FALSE)
			{
				$array_erorr[] = 'Id Kategori invalid';
			}
		}
		
		//final cek
		if(empty($array_error) === FALSE)
		{
			return $array_error;
		}
		else
		{
			return TRUE;
		}
	}
	
	public function baru()
	{
		$this->set_judul('baru');
		
		if($this->input->post('do_create') !== FALSE)
		{
			$data_post = $this->input->post();
			$allow_create = $this->cek_baru($data_post);
			
			if($allow_create === TRUE)
			{
				//perform insert database
				$this->produk_model->buat_produk($data_post);
				$this->set_flash_feedback('Berhasil membuat produk baru.', 'sukses');
				redirect('administrator/produk/edit/' . $this->produk_model->get_last_id());
			}
			else
			{
				$this->set_feedback($allow_create, 'error');
				
			}
		}
		else
		{
			$data_post['nama'] = '';
			$data_post['harga'] = '';
			$data_post['id_kategori'] = '';
			$data_post['deskripsi'] = '';
		}
		
		$data_view = $data_post;
		$data_view['kategori'] = $this->kategori_model->get_all_kategori(0);
		$form = $this->load->view('backend/produk/produk_baru', $data_view, TRUE);
		
		$this->tampil($form);
	}
}

/* End of file members.php */
/* Location: ./application/controllers/members.php */