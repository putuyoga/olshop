<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Main
 *
 * @package		PemesananOnline
 * @subpackage	frontend
 * @category	main
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Main extends BASE_Controller {
	
	private $pageData = array();
	
	public function __construct() {
		parent::__construct('produk terbaru');
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
		if( ! is_numeric($page) || $page < 1)
		{
			redirect('/');
		}
		$this->load->model('produk_model');
		$data_view['rows'] = $this->produk_model->get_latest_produk($page);
		if($data_view['rows'] !== NULL)
		{
			$this->load->library('pagination');
			$config['base_url'] = base_url('index.php/main/page/');
			$config['total_rows'] = $this->produk_model->latest_produk_count();
			$config['use_page_numbers'] = TRUE;
			$config['per_page'] = 12;

			$this->pagination->initialize($config);
			$data_view['pagelink'] = $this->pagination->create_links();
			$grid = $this->load->view('frontend/main', $data_view, TRUE);
			$this->tampil($grid);
		}
		else
		{
			$this->set_feedback('Tidak ada produk tersedia', 'info');
			$this->tampil('');
		}
	}
	
	public function page($page = 1)
	{
		$this->index($page);
	}
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */