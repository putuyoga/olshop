<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Member
 *
 * @package		PemesananOnline
 * @subpackage	frontend
 * @category	member
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Member extends BASE_Controller {
	
	public function __construct() {
		parent::__construct('member');
		$this->load->helper('form');
	}
	
	public function index()
	{
		//redirect
	}
	
	private function cek_data($data, $is_register = TRUE)
	{
		extract($data);
		$array_error = array();
		
		if($is_register === TRUE)
		{
			//cek username kosong?
			if(trim($username) === '')
			{
				$array_error[] = 'Username tidak boleh kosong';
			}
			else if($this->member_model->exist_username($username) === TRUE)
			{
				$array_error[] = 'Username sudah ada yang punya';
			}
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
			if($is_register === TRUE)
			{
				$array_error[] = 'Email sudah ada yang punya';
			}
			else
			{
				$current_member = $this->get_current_member();
				if($email !== $current_member['email'])
				{
					$array_error[] = 'Email sudah ada yang punya';
				}
			}
		}
		
		//cek password
		if($is_register === TRUE)
		{
			if(trim($password) === '')
			{
				$array_error[] = 'Password tidak boleh kosong';
			}
			else if($password !== $re_password)
			{
				$array_error[] = 'Password yang diketikkan tidak sama';
			}
		}
		else
		{
			if(trim($password) !== '')
			{
				$current_member = $this->get_current_member();
				if(md5($before_password) !== $tcurrent_member['password'])
				{
					$array_error[] = 'Password lama yang anda masukan salah';
				}
				else if($password !== $re_password)
				{
					$array_error[] = 'Password baru yang diketikkan tidak sama';
				}
			}
			
				
		}
		
		//cek nomor hp
		if(trim($no_hp) === '')
		{
			$array_error[] = 'Nomor hp tidak boleh kosong';
		}
		else if(is_numeric($no_hp) === FALSE)
		{
			$array_error[] = 'Nomor hp harus berupa angka';
		}
		
		//cek alamat
		if(trim($alamat) === '')
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
	
	public function pengaturan()
	{
		$this->set_judul('pengaturan');
		$data_member = $this->get_current_member();
		if($data_member === NULL)
		{
			redirect('/member/login');
		}
		
		if($this->input->post('do_simpan') !== FALSE)
		{
			$data_post = $this->input->post();
			$allow_edit = $this->cek_data($data_post, FALSE);
			if($allow_edit === TRUE)
			{
				$this->member_model->edit_member_by_id($data_member['id'], $data_post, FALSE);
				$this->set_flash_feedback('Berhasil perbarui info member', 'sukses');
				redirect('member/pengaturan');
			}
			else
			{
				$this->set_feedback($allow_edit, 'error');
			}
		}
		elseif($this->input->post('do_upload') !== FALSE)
		{
			$this->upload_foto($data_member['id']);
		}
		
		$form = $this->load->view('frontend/member/pengaturan', $data_member, TRUE);
		$this->tampil($form);
		
	}
	
	public function register() {
		$this->set_judul('register');
		if($this->input->post('do_register') !== FALSE)
		{
			$post = $this->input->post();
			$allow_register = $this->cek_data($post);
			if($allow_register === TRUE)
			{
				$this->member_model->new_member($post);
				$this->set_feedback('Anda telah berhasil registrasi. Sekarang silahkan login', 'sukses');
				$konten = '';
			}
			else
			{
				$this->set_feedback($allow_register, 'error');
				$konten = $this->load->view('frontend/member/register', '', TRUE);
			}
		}
		else
		{
			$konten = $this->load->view('frontend/member/register', '', TRUE);
		}
		$this->tampil($konten);
	}
	
	public function login() {
		$this->set_judul('login');
		$this->load->library('members');
		if($this->input->post('do_login') !== FALSE)
		{
			$post = $this->input->post();
			$data_member = $this->member_model->get_member_by_login($post['username'], $post['password']);
			if($data_member != NULL)
			{
				$this->members->set_session($data_member['id']);
				redirect('/');
			}
			else
			{
				$this->set_feedback('Username / Password salah', 'error');
			}
		}
		$konten = $this->load->view('frontend/member/login', '', TRUE);
		$this->tampil($konten);
	}
	
	public function logout()
	{
		$this->load->library('members');
		$this->load->library('cart');
		$this->cart->destroy();
		$this->members->delete_session();
		
		redirect('/');
	}
	
	public function lupa_password()
	{
		$this->set_judul('Lupa Password');
		if($this->input->post('do_request'))
		{
			$email = $this->input->post('email');
			$member = $this->member_model->get_member_by_email($email);
			if($member !== NULL)
			{
				$token = $this->generate_token($member);
				$config = Array(
				'protocol' => 'mail'
				);
				$random = $this->generate_token($member);
				$this->load->library('email', $config);
				$this->email->set_newline("\r\n");

				$this->email->from('inception99@gmail.com', 'Inception99');
				$this->email->to($email);
				$this->email->subject('Reset Password');
				$this->email->message('Password baru anda adalah ' . $random );

				if($this->email->send())
				{
					$this->member_model->update_password_by_id($member['id'], $random);
					$this->set_flash_feedback('Berhasil reset password, cek email anda.', 'sukses');
					redirect('member/login');
				}
				else
				{
				
				}
			}	
		}
		$form = $this->load->view('frontend/member/lupa_password', '', TRUE);
		$this->tampil($form);
		
	}
	
	public function generate_token($data_member)
	{
		$token = substr(sha1(microtime() . $data_member['password']), 5);
		return $token;
	}
}

/* End of file members.php */
/* Location: ./application/controllers/members.php */
