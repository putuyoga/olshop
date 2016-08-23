<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Kategori
 *
 * @package		PemesananOnline
 * @subpackage	frontend
 * @category	kategori
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Kategori extends BASE_Controller {
	
	private $pageData = array();
	
	public function __construct() {
		parent::__construct('kategori');
		$this->load->model('kategori_model');
		$this->load->model('produk_model');
	}
	
	public function index()
	{
		
	}
	
	/* Melihat produk berdasarkan kategori */
	public function lihat($id = '', $page = 1)
	{
		if(is_numeric($page) === FALSE || is_numeric($id) === FALSE)
		{
			redirect('/');
		}
		
		$data_view['rows'] = $this->produk_model->get_latest_produk_by_kategori($id, $page);
		$data_view['kategori'] = $this->kategori_model->get_kategori_by_id($id);
		$this->set_judul($data_view['kategori']['nama']);
		
		//jika ada produk dari kategori ini
		if($data_view['rows'] !== NULL)
		{
			$this->kategori_model->inc_dilihat($id);
			$this->load->library('pagination');
			$config['base_url'] = base_url('index.php/kategori/' . $id);
			$config['total_rows'] = $this->produk_model->latest_produk_by_kategori_count($id);
			$config['use_page_numbers'] = TRUE;
			$config['per_page'] = 12;

			$this->pagination->initialize($config);
			$data_view['pagelink'] = $this->pagination->create_links();
			$grid = $this->load->view('frontend/kategori/lihat_kategori', $data_view, TRUE);
			$this->tampil($grid);
		}
		else
		{
			$this->set_feedback('Tidak ada produk tersedia di kategori ini.', 'info');
			$this->tampil('');
		}
	}
	
}

/* End of file kategori.php */
/* Location: ./application/controllers/kategori.php */