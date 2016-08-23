<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Halaman
 *
 * @package		PemesananOnline
 * @subpackage	frontend
 * @category	halaman
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Halaman extends BASE_Controller {
	
	private $pageData = array();
	
	public function __construct() {
		parent::__construct('halaman');
		$this->load->model('halaman_model');
		$this->load->helper('typography');
	}
	
	public function index()
	{
		
	}
	
	public function lihat($id = '')
	{
		if(is_numeric($id) === FALSE)
		{
			redirect('/');
		}
		
		$data_view = $this->halaman_model->get_halaman_by_id($id);
		if($data_view !== NULL)
		{
			$this->set_judul($data_view['judul']);
			$konten = $this->load->view('frontend/halaman/lihat_halaman', $data_view, TRUE);
			$this->tampil($konten);
		}
		else
		{
			redirect('/');
		}
	}
	
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */