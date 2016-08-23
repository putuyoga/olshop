<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Dashboard
 *
 * @package		PemesananOnline
 * @subpackage	backend
 * @category	dashboard
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Dashboard extends ADM_Controller {
	
	public function __construct() {
		parent::__construct('dashboard');
		$this->load->model('kategori_model');
		$this->load->model('pemesanan_model');
		$this->load->model('produk_model');
		$this->load->model('member_model');
	}
	
	public function index()
	{
		$data_view['kategori_dipesan'] = $this->kategori_model->get_paling_dipesan();
		$data_view['kategori_dilihat'] = $this->kategori_model->get_paling_dilihat();
		$data_view['produk_dipesan'] = $this->produk_model->get_paling_dipesan();
		$data_view['produk_dilihat'] = $this->produk_model->get_paling_dilihat();
		$data_view['total_pesanan'] = $this->pemesanan_model->all_rows_count();;
		$data_view['total_member'] = $this->member_model->all_rows_count();;
		$data_view['total_produk'] = $this->produk_model->all_rows_count();;
		$data_view['total_kategori'] = $this->kategori_model->all_rows_count();
		$this->load->helper('counter');
		$data_view['pengunjung'] = get_counter();
		$konten = $this->load->view('backend/dashboard', $data_view, TRUE);
		$this->tampil($konten);
	}
	
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */
