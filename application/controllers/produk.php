<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Produk
 *
 * @package		PemesananOnline
 * @subpackage	frontend
 * @category	produk
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Produk extends BASE_Controller {
	
	private $pageData = array();
	
	public function __construct() {
		parent::__construct('produk');
		$this->load->model('produk_model');
	}
	
	public function index()
	{
		//fetch all product
	}
	
	public function lihat($id = '')
	{
		if( ! is_numeric($id))
		{
			redirect('/');
		}
		else
		{
			$produk = $this->produk_model->get_produk_by_id($id);
			$this->load->model('member_model');
			if($this->get_current_member() !== NULL)
			{
				$produk['member_level'] = $this->get_current_member()['level'];
			}
			else
			{
				$produk['member_level'] = 0;
			}
			if($produk !== NULL)
			{
				$this->produk_model->inc_dilihat($id);
				$konten = $this->load->view('frontend/produk/lihat_produk', $produk, TRUE);
				$this->load->model('kategori_model');
				$nama_kategori = $this->kategori_model->get_nama_by_id($produk['id_kategori']);
				$this->set_judul($nama_kategori . ' &raquo; ' . $produk['nama']);
				$this->tampil($konten);
			}
			else
			{
				redirect('/');
			}
		}
			
	}
}

/* End of file product.php */
/* Location: ./application/controllers/product.php */