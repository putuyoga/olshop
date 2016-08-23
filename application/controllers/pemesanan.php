<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Pemesanan
 *
 * @package		PemesananOnline
 * @subpackage	frontend
 * @category	pemesanan
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Pemesanan extends BASE_Controller {
	
	public function __construct() {
		parent::__construct('pemesanan');
		$this->load->model('pemesanan_model');
		$this->load->helper('string');
		$this->load->helper('form');
	}

	
	public function index($page = 1)
	{
		if(is_numeric($page) === FALSE)
		{
			$page = 1;
		}
		
		$member = $this->get_current_member();
		
		if($member === NULL)
		{
			redirect('/member/login');
		}
		
		//setup page links
		$this->load->library('pagination');
		$config['base_url'] = base_url() . 'index.php/pemesanan';
		$config['total_rows'] = $this->pemesanan_model->all_rows_count();
		$config['use_page_numbers'] = TRUE;
		$config['per_page'] = 10; 

		$this->pagination->initialize($config);
		$data_view['pagelink'] = $this->pagination->create_links();
		$data_view['rows'] = $this->pemesanan_model->get_all_pesanan($page, $member['id']);
		$this->set_judul('list');
		$main = $this->load->view('frontend/pemesanan/main', $data_view, TRUE);
		$this->tampil($main);
	}
	
	public function lihat($id = '')
	{		
		if($id !== '')
		{
			$this->set_judul('lihat');
			$member = $this->get_current_member();
			$data_view = $this->pemesanan_model->get_pesanan_by_id($id);
			
			//preventif, agar user lain tidak bisa ngintip pesanan orang lain
			if($data_view['id_member'] !== $member['id'])
			{
				$this->set_flash_feedback('Tidak ada pesanan dengan id ' . $id, 'error');
				redirect('pemesanan');
			}
			
			if($data_view !== NULL)
			{
				$this->load->model('member_model');
				$this->load->model('produk_model');
				
				$data_view['pesanan'] = $this->pemesanan_model->get_detail_by_id($id);
				$data_view['banyak_barang'] = $this->pemesanan_model->banyak_barang_by_id($id)['kuantitas'];
				$form = $this->load->view('frontend/pemesanan/lihat_pesanan', $data_view, TRUE);
				$this->tampil($form);
			}
			else
			{
				$this->set_flash_feedback('Tidak ada pesanan dengan id ' . $id, 'error');
				redirect('pemesanan');
			}
		}
		else
		{
			redirect('pemesanan');
		}
	}
	
}

/* End of file members.php */
/* Location: ./application/controllers/members.php */