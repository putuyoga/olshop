<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Member
 *
 * @package		PemesananOnline
 * @subpackage	backend
 * @category	member
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Member extends ADM_Controller {
	
	public function __construct() {
		parent::__construct('member');
		$this->load->model('member_model');
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
			redirect('administrator/member');
		}
		
		$this->cek_hapus();
		
		if($this->input->post('do_cari') !== FALSE)
		{
			$post = $this->input->post();
			redirect('administrator/member/cari/' . $post['pilihan_cari'] . '/' . $post['input_cari']);
		}
		//setup page links
		$this->load->library('pagination');

		$config['base_url'] = base_url() . 'index.php/administrator/member';
		$config['total_rows'] = $this->member_model->all_rows_count();
		$config['use_page_numbers'] = TRUE;
		$config['per_page'] = 10; 

		$this->pagination->initialize($config);
		$data_view['pagelink'] = $this->pagination->create_links();
		$data_view['pilihan'] = '';
		$data_view['input_cari'] = '';
		
		$data_view['rows'] = $this->member_model->get_all_member($page);
		$this->set_judul('list');
		$main = $this->load->view('backend/member/main', $data_view, TRUE);
		$this->tampil($main);
	}
	
	public function cari($pilihan = 'id', $input, $page = 1)
	{
		
		$this->cek_hapus();
		
		switch($pilihan)
		{
			case 'id':
				$data_view['rows'] = $this->member_model->cari_id($input, $page);
				$config['total_rows'] = $this->member_model->cari_id_count($input);
			break;
			case 'username':
				$data_view['rows'] = $this->member_model->cari_username($input, $page);
				$config['total_rows'] = $this->member_model->cari_username_count($input);
			break;
			default:
				redirect('administrator/member');
			break;
		}
		
		if( ! is_numeric($page))
		{
			redirect('administrator/member');
		}
		
		if($data_view['rows'] !== NULL)
		{
			//setup page links
			$this->load->library('pagination');

			$config['base_url'] = base_url() . 'index.php/administrator/member';
			$config['use_page_numbers'] = TRUE;
			$config['per_page'] = 10; 

			$this->pagination->initialize($config);
			$data_view['pagelink'] = $this->pagination->create_links();
		
			$this->set_judul('pencarian ' . $pilihan);
			$data_view['pilihan'] = $pilihan;
			$data_view['input_cari'] = $input;
			$main = $this->load->view('backend/member/main', $data_view, TRUE);
			$this->tampil($main);
		}
		else
		{
			$this->set_flash_feedback('Hasil pencarian tidak ada', 'info');
			redirect('administrator/member');
		}
	}
	
	private function cek_hapus()
	{
		if($this->input->post('do_hapus') !== FALSE)
		{
			$id = $this->input->post('id_hapus');
			if($id !== FALSE)
			{
				$this->member_model->delete_member_by_id($id);
				$this->set_feedback('Berhasil hapus ' . count($id) . ' members', 'sukses');
			}
			else
			{
				$this->set_feedback('Pilih member yang akan dihapus !', 'error');
			}
		}
	}
	
	public function reset_password($id = '')
	{
		if($id !== '')
		{
			$data_member = $this->member_model->get_member_by_id($id);
			
			if($data_member !== NULL)
			{
				//admin can't edit same level or higher
				if($this->is_authorized($data_member, $this->get_current_member()) === FALSE)
				{
					$this->set_flash_feedback('Anda tidak dapat mereset password member dengan level setara atau lebih tinggi!', 'error');
					redirect('administrator/member');
					return;
				}
				$this->set_judul('reset password');
				$data_member['password'] = random_string('alnum', 6);
				$this->member_model->update_password_by_id($id, $data_member['password']);
				$form = $this->load->view('backend/member/reset_password', $data_member, TRUE);
				$this->tampil($form);
			}
			else
			{
				$this->set_feedback('Tidak ada member dengan id ' . $id, 'error');
				redirect('administrator/member');
			}
		}
		else
		{
			redirect('administrator/member');
		}
	}
	
	public function lihat($id = '')
	{		
		if(is_numeric($id) === TRUE)
		{
			$this->set_judul('lihat');
			$data_view = $this->member_model->get_member_by_id($id);
			
			if($data_view !== NULL)
			{
				$form = $this->load->view('backend/member/lihat_member', $data_view, TRUE);
				$this->tampil($form);
			}
			else
			{
				$this->set_flash_feedback('Tidak ada member dengan id ' . $id, 'error');
				redirect('administrator/member');
			}
		}
		else
		{
			redirect('administrator/member');
		}
	}
	
	public function hapus($id = '')
	{
		if(is_numeric($id))
		{
			$data_member = $this->member_model->get_member_by_id($id);
			
			//admin can't edit same level or higher
			if($this->is_authorized($data_member, $this->get_current_member()) === FALSE)
			{
				$this->set_flash_feedback('Anda tidak dapat menghapus member dengan level setara atau lebih tinggi!', 'error');
				redirect('administrator/member');
				return;
			}
			$this->pemesanan_model->delete_pesanan_by_id($id);
			$this->set_flash_feedback('Berhasil hapus pesanan', 'sukses');
		}
		redirect('/administrator/pemesanan');
	}
	
	public function hapus_semua()
	{
		$this->member_model->delete_all_member();
		$this->set_flash_feedback('Sukses hapus semua member', 'sukses');
		redirect('administrator/member');
	}
	
	public function cek_edit($data_edit, $data_before)
	{
		extract($data_edit);
		$array_error = array();
		
		//cek username
		if(trim($username) === '')
		{
			$array_error[] = 'Username tidak boleh kosong';
		}
		else if($this->member_model->exist_username($username) === TRUE
			&& $data_before['username'] != $username)
		{
			$array_error[] = 'Username sudah ada yang punya';
		}
		
		//cek email
		$this->load->helper('email');
		if(trim($email) === '')
		{
			$array_error[] = 'Email tidak boleh kosong';
		}
		else if(valid_email($email) === FALSE)
		{
			$array_error[] = 'Email tidak valid';
		}
		else if($this->member_model->exist_email($email) === TRUE
			&& $data_before['email'] != $email)
		{
			$array_error[] = 'Email sudah ada yang punya';
		}
		
		
		//cek nomor hp, except for admin
		if(trim($no_hp) === '' && $level < 255)
		{
			$array_error[] = 'Nomor hp tidak boleh kosong';
		}
		else if(is_numeric($no_hp) === FALSE)
		{
			$array_error[] = 'Nomor hp harus berupa angka';
		}
		
		//cek alamat, except for admin
		if(trim($alamat) === '' && $level < 255)
		{
			$array_error[] = 'Alamat tidak boleh kosong';
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
	
	private function upload_foto($id)
	{
		$config['upload_path'] = './images/avatar/';
		$config['allowed_types'] = 'jpg';
		$config['max_width']  = '150';
		$config['max_height']  = '150';
		$config['overwrite'] = TRUE;
		$config['file_name'] = $id;
		
		$this->load->library('upload', $config);
		
		if ( ! $this->upload->do_upload('avatar'))
		{
			$this->set_feedback($this->upload->display_errors('',''), 'error');
		}
		else
		{
			$this->set_feedback('Sukses upload avatar', 'sukses');
		}	
	}
	
	public function is_authorized($target_member,$current_member)
	{
		if($target_member['level'] < $current_member['level'] ||
			$target_member['id'] === $current_member['id'])
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function edit($id = '')
	{		
		if($id !== '')
		{
			$this->set_judul('edit');
			$data_view = $this->member_model->get_member_by_id($id);
			
			//admin can't edit same level or higher
			if($this->is_authorized($data_view, $this->get_current_member()) === FALSE)
			{
				$this->set_flash_feedback('Anda tidak dapat mengedit member dengan level setara atau lebih tinggi!', 'error');
				redirect('administrator/member');
				return;
			}
			
			//cek post
			if($this->input->post('do_edit'))
			{
				$data_post = $this->input->post();
				$allow_edit = $this->cek_edit($data_post, $data_view);
				if($allow_edit === TRUE)
				{
					$this->member_model->edit_member_by_id($id, $data_post);
					$this->set_flash_feedback('Sukses edit member', 'sukses');
					redirect('administrator/member/edit/' . $id);
				}
				else
				{
					$this->set_feedback($allow_edit, 'error');
				}
				
			}
			elseif($this->input->post('do_upload') !== FALSE)
			{
				$this->upload_foto($data_view['id']);
			}
			
			if($data_view !== NULL)
			{
				$form = $this->load->view('backend/member/edit_member', $data_view, TRUE);
				$this->tampil($form);
			}
			else
			{
				$this->set_flash_feedback('Tidak ada member dengan id ' . $id, 'error');
				redirect('administrator/member');
			}
		}
		else
		{
			redirect('administrator/member');
		}
	}
	
	private function cek_buat_admin($data_admin)
	{
		extract($data_admin);
		$array_error = array();
		
		//cek username
		if(trim($username) === '')
		{
			$array_error[] = 'Username tidak boleh kosong';
		}
		else if($this->member_model->exist_username($username) === TRUE)
		{
			$array_error[] = 'Username sudah ada yang punya';
		}
		
		//cek email
		$this->load->helper('email');
		if(trim($email) === '')
		{
			$array_error[] = 'Email tidak boleh kosong';
		}
		else if(valid_email($email) === FALSE)
		{
			$array_error[] = 'Email tidak valid';
		}
		else if($this->member_model->exist_email($email) === TRUE)
		{
			$array_error[] = 'Email sudah ada yang punya';
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
	
	public function buat_admin()
	{
		$this->set_judul('admin baru');
		if($this->input->post('do_create') !== FALSE)
		{
			$data_post = $this->input->post();
			$data_post['password'] = random_string('alnum', 6);
			$allow_create = $this->cek_buat_admin($data_post);
			
			if($allow_create === TRUE)
			{
				$this->member_model->new_admin($data_post);
				$form = $this->load->view('backend/member/sukses_buat_admin', $data_post, TRUE);
			}
			else
			{
				$this->set_feedback($allow_create, 'error');
				$form = $this->load->view('backend/member/buat_admin', '', TRUE);
			}
		}
		else
		{
			$form = $this->load->view('backend/member/buat_admin', '', TRUE);
		}
		
		$this->tampil($form);
	}
}

/* End of file members.php */
/* Location: ./application/controllers/members.php */