<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Kategori
 *
 * @package		PemesananOnline
 * @subpackage	backend
 * @category	kategori
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Kategori extends ADM_Controller {
	
	public function __construct() {
		parent::__construct('kategori');
		$this->load->model('kategori_model');
		$this->load->helper('string');
		$this->load->helper('form');
	}
	
	function _remap($method)
	{
		$param_offset = 2;

		// Default to index
		if ( ! method_exists($this, $method))
		{
			// We need one more param
			$param_offset = 1;
			$method = 'index';
		}

		// Since all we get is $method, load up everything else in the URI
		$params = array_slice($this->uri->rsegment_array(), $param_offset);

		// Call the determined method with all params
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
			redirect('administrator/kategori/cari/' . $post['pilihan_cari'] . '/' . $post['input_cari']);
		}
		
		//setup page links
		$this->load->library('pagination');

		$config['base_url'] = base_url() . 'index.php/administrator/kategori';
		$config['total_rows'] = $this->kategori_model->all_rows_count();
		$config['use_page_numbers'] = TRUE;
		$config['per_page'] = 10; 

		$this->pagination->initialize($config);
		$data_view['pagelink'] = $this->pagination->create_links();
		$data_view['pilihan'] = '';
		$data_view['input_cari'] = '';
		
		$data_view['rows'] = $this->kategori_model->get_all_kategori($page);
		$this->set_judul('list');
		$main = $this->load->view('backend/kategori/main', $data_view, TRUE);
		$this->tampil($main);
	}
	
	public function cari($pilihan = 'id', $input, $page = 1)
	{
		
		$this->cek_hapus();
		
		switch($pilihan)
		{
			case 'id':
				$data_view['rows'] = $this->kategori_model->cari_id($input, $page);
				$config['total_rows'] = $this->kategori_model->cari_id_count($input);
			break;
			case 'nama':
				$data_view['rows'] = $this->kategori_model->cari_nama($input, $page);
				$config['total_rows'] = $this->kategori_model->cari_nama_count($input);
			break;
			default:
				redirect('administrator/kategori');
			break;
		}
		
		if( ! is_numeric($page))
		{
			redirect('administrator/kategori');
		}
		
		if($data_view['rows'] !== NULL)
		{
			//setup page links
			$this->load->library('pagination');

			$config['base_url'] = base_url() . 'index.php/administrator/kategori';
			$config['use_page_numbers'] = TRUE;
			$config['per_page'] = 10; 

			$this->pagination->initialize($config);
			$data_view['pagelink'] = $this->pagination->create_links();
		
			$this->set_judul('pencarian ' . $pilihan);
			$data_view['pilihan'] = $pilihan;
			$data_view['input_cari'] = $input;
			$main = $this->load->view('backend/kategori/main', $data_view, TRUE);
			$this->tampil($main);
		}
		else
		{
			$this->set_flash_feedback('Hasil pencarian tidak ada', 'info');
			redirect('administrator/kategori');
		}
	}
	
	private function cek_hapus()
	{
		if($this->input->post('do_hapus') !== FALSE)
		{
			$id = $this->input->post('id_hapus');
			if($id !== FALSE)
			{
				$this->kategori_model->delete_kategori_by_id($id);
				$this->set_feedback('Berhasil hapus ' . count($id) . ' kategori', 'sukses');
			}
			else
			{
				$this->set_feedback('Pilih kategori yang akan dihapus !', 'error');
			}
		}
	}
	
	public function hapus_semua()
	{
		$this->kategori_model->delete_all_kategori();
		$this->set_flash_feedback('Sukses hapus semua kategori', 'sukses');
		redirect('administrator/kategori');
	}
	
	public function cek_edit($data_edit, $data_before)
	{
		extract($data_edit);
		$array_error = array();
		
		//cek nama
		if(trim($nama) === '')
		{
			$array_error[] = 'Nama tidak boleh kosong.';
		}
		else if($this->kategori_model->exist_nama($nama) === TRUE
			&& $data_before['nama'] != $nama)
		{
			$array_error[] = 'Nama sudah ada.';
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
	
	public function edit($id = '')
	{		
		if($id !== '')
		{
			$this->set_judul('edit');
			$data_view = $this->kategori_model->get_kategori_by_id($id);
			
			//cek post
			if($this->input->post('do_edit'))
			{
				$data_post = $this->input->post();
				$allow_edit = $this->cek_edit($data_post, $data_view);
				if($allow_edit === TRUE)
				{
					$this->kategori_model->edit_kategori_by_id($id, $data_post);
					$this->set_flash_feedback('Sukses edit kategori', 'sukses');
					redirect('administrator/kategori/edit/' . $id);
				}
				else
				{
					$this->set_feedback($allow_edit, 'error');
				}
				
			}
			
			if($data_view !== NULL)
			{
				$form = $this->load->view('backend/kategori/edit_kategori', $data_view, TRUE);
				$this->tampil($form);
			}
			else
			{
				$this->set_flash_feedback('Tidak ada kategori dengan id ' . $id, 'error');
				redirect('administrator/kategori');
			}
		}
		else
		{
			redirect('administrator/kategori');
		}
	}
	
	private function cek_buat_kategori($data_kategori)
	{
		extract($data_kategori);
		$array_error = array();
		
		//cek username
		if(trim($nama) === '')
		{
			$array_error[] = 'Nama tidak boleh kosong';
		}
		else if($this->kategori_model->exist_nama($nama) === TRUE)
		{
			$array_error[] = 'Nama sudah ada.';
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
			$allow_create = $this->cek_buat_kategori($data_post);
			
			if($allow_create === TRUE)
			{
				$this->kategori_model->buat_kategori($data_post);
				$this->set_flash_feedback('Berhasil membuat kategori baru', 'sukses');
				redirect('administrator/kategori');
			}
			else
			{
				$this->set_feedback($allow_create, 'error');
				$form = $this->load->view('backend/kategori/kategori_baru', '', TRUE);
			}
		}
		else
		{
			$form = $this->load->view('backend/kategori/kategori_baru', '', TRUE);
		}
		
		$this->tampil($form);
	}
}

/* End of file members.php */
/* Location: ./application/controllers/members.php */
