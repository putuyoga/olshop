<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Controler
 *
 * @package		PemesananOnline
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class BASE_Controller extends CI_Controller
{
	protected $judul;
	protected $halaman;
	protected $feedback;
	protected $type_feedback;
	protected $current_member;
	
	public function __construct($halaman)
	{
		parent::__construct();
		$this->load->library('members');
		$this->set_halaman($halaman);
		$this->set_feedback('','');
		$this->current_member = $this->members->get_current_member();
	}
	
	protected function set_judul($judul)
	{
		$this->judul = $judul;
	}
	
	protected function get_current_member()
	{
		return $this->current_member;
	}
	
	protected function get_sidebar_member_view()
	{
		$member = $this->get_current_member();
		if($member !== NULL)
		{
			return $this->load->view('sidebar_member', $member, TRUE);
		}
		else
		{
			return $this->load->view('sidebar_guest', '', TRUE);
		}
	}
	
	protected function get_sidebar_kategori_view()
	{
		$this->load->model('kategori_model');
		//fetch all
		$kategori['rows'] = $this->kategori_model->get_all_kategori(0);
		//var_dump($kategori);
		return $this->load->view('sidebar_kategori', $kategori, TRUE);
	}
	
	protected function get_sidebar_halaman_view()
	{
		$this->load->model('halaman_model');
		//fetch all
		$halaman['rows'] = $this->halaman_model->get_all_halaman(0);
		return $this->load->view('sidebar_halaman', $halaman, TRUE);
	}
	
	public function set_halaman($halaman)
	{
		$this->halaman = $halaman;
	}
	
	public function get_judul()
	{
		$out = ucwords($this->halaman);
		if($this->judul != NULL)
		{
			$out .= ' &raquo; ' . ucwords($this->judul);
		}
		
		return $out;
	}
	
	public function set_feedback($feedback, $type_feedback)
	{
		$this->feedback = $feedback;
		$this->type_feedback = $type_feedback;
	}
	
	public function set_flash_feedback($feedback, $type_feedback)
	{
		$this->session->set_flashdata('feedback', $feedback);
		$this->session->set_flashdata('type_feedback', $type_feedback);
	}
	
	protected function get_feedback_view()
	{
		if($this->session->flashdata('feedback') === FALSE)
		{
			$data_view = array(
				'type' => $this->type_feedback,
				'data_feedback' => $this->feedback
			);
		}
		else
		{
			$data_view = array(
				'type' => $this->session->flashdata('type_feedback'),
				'data_feedback' => $this->session->flashdata('feedback')
			);
		}
		return $this->load->view('feedback', $data_view, TRUE);
	}
	
	protected function exist_flash_feedback()
	{
		if($this->session->flashdata('feedback') === FALSE)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
    public function tampil($konten = '')
	{
		$data_view['konten'] = $konten;
		$data_view['judul'] = $this->get_judul();
		$data_view['sidebar_member'] = $this->get_sidebar_member_view();
		$data_view['sidebar_halaman'] = $this->get_sidebar_halaman_view();
		$data_view['sidebar_kategori'] = $this->get_sidebar_kategori_view();
		if($this->feedback !== '' || $this->exist_flash_feedback() === TRUE)
		{
			$data_view['feedback'] = $this->get_feedback_view();
		}
		$this->load->view('template', $data_view);
	}

}

class ADM_Controller extends BASE_Controller
{
	
	public function __construct($halaman = '')
	{
		parent::__construct($halaman);
		//hanya admin yang bisa akses controller ADM
		$member = $this->get_current_member();
		if($member['level'] < 255)
		{
			redirect('member/login');
		}
	}
	
	public function get_judul()
	{
		$out = 'Administrator &raquo; ' . ucwords($this->halaman);
		if($this->judul != NULL)
		{
			$out .= ' &raquo; ' . ucwords($this->judul);
		}
		
		return $out;
	}
	
	private function get_menu_view()
	{
		return $this->load->view('backend/menu_admin', array('active' => $this->halaman), TRUE);
	}
	
    public function tampil($konten = '')
	{
		$data_view['konten'] = $konten;
		$data_view['judul'] = $this->get_judul();
		$data_view['sidebar_member'] = $this->get_sidebar_member_view();
		$data_view['sidebar_halaman'] = $this->get_sidebar_halaman_view();
		$data_view['sidebar_kategori'] = $this->get_sidebar_kategori_view();
		$data_view['menu'] = $this->get_menu_view();
		
		if($this->feedback !== '' || $this->exist_flash_feedback() === TRUE)
		{
			$data_view['feedback'] = $this->get_feedback_view();
		}
		$this->load->view('template', $data_view);
	}
}