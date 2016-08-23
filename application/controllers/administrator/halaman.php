<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Halaman
 *
 * @package		PemesananOnline
 * @subpackage	backend
 * @category	halaman
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Halaman extends ADM_Controller {
	
	public function __construct() {
		parent::__construct('halaman');
		$this->load->model('halaman_model');
		$this->load->helper('string');
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
		
		//setup page links
		$this->load->library('pagination');

		$config['base_url'] = base_url() . 'index.php/administrator/halaman';
		$config['total_rows'] = $this->halaman_model->all_rows_count();
		$config['use_page_numbers'] = TRUE;
		$config['per_page'] = 10; 

		$this->pagination->initialize($config);
		$data_view['pagelink'] = $this->pagination->create_links();
		$data_view['pilihan'] = '';
		$data_view['input_cari'] = '';
		
		$data_view['rows'] = $this->halaman_model->get_all_halaman($page);
		$this->set_judul('list');
		$main = $this->load->view('backend/halaman/main', $data_view, TRUE);
		$this->tampil($main);
	}
	
	
	private function cek_hapus()
	{
		if($this->input->post('do_hapus') !== FALSE)
		{
			$id = $this->input->post('id_hapus');
			if($id !== FALSE)
			{
				$this->halaman_model->delete_halaman_by_id($id);
				$this->set_feedback('Berhasil hapus ' . count($id) . ' halaman', 'sukses');
			}
			else
			{
				$this->set_feedback('Pilih halaman yang akan dihapus !', 'error');
			}
		}
	}
	
	public function hapus_semua()
	{
		$this->halaman_model->delete_all_halaman();
		$this->set_flash_feedback('Sukses hapus semua halaman', 'sukses');
		redirect('administrator/halaman');
	}
	
	public function cek_edit($data_edit, $data_before)
	{
		extract($data_edit);
		$array_error = array();
		
		//cek nama
		if(trim($judul) === '')
		{
			$array_error[] = 'Judul tidak boleh kosong.';
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
			$data_view = $this->halaman_model->get_halaman_by_id($id);
			
			//cek post
			if($this->input->post('do_edit'))
			{
				$data_post = $this->input->post();
				$allow_edit = $this->cek_edit($data_post, $data_view);
				if($allow_edit === TRUE)
				{
					$this->halaman_model->edit_halaman_by_id($id, $data_post);
					$this->set_flash_feedback('Sukses edit halaman', 'sukses');
					redirect('administrator/halaman/edit/' . $id);
				}
				else
				{
					$this->set_feedback($allow_edit, 'error');
				}
				
			}
			
			if($data_view !== NULL)
			{
				$form = $this->load->view('backend/halaman/edit_halaman', $data_view, TRUE);
				$this->tampil($form);
			}
			else
			{
				$this->set_flash_feedback('Tidak ada halaman dengan id ' . $id, 'error');
				redirect('administrator/halaman');
			}
		}
		else
		{
			redirect('administrator/halaman');
		}
	}
	
	private function cek_buat_halaman($data_halaman)
	{
		extract($data_halaman);
		$array_error = array();
		
		//cek username
		if(trim($judul) === '')
		{
			$array_error[] = 'Judul tidak boleh kosong';
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
			$allow_create = $this->cek_buat_halaman($data_post);
			
			if($allow_create === TRUE)
			{
				$this->halaman_model->buat_halaman($data_post);
				$this->set_flash_feedback('Berhasil membuat halaman baru', 'sukses');
				redirect('administrator/halaman');
			}
			else
			{
				$this->set_feedback($allow_create, 'error');
				$form = $this->load->view('backend/halaman/halaman_baru', '', TRUE);
			}
		}
		else
		{
			$form = $this->load->view('backend/halaman/halaman_baru', '', TRUE);
		}
		
		$this->tampil($form);
	}
}

/* End of file members.php */
/* Location: ./application/controllers/members.php */
