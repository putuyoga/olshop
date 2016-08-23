<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Keranjang
 *
 * @package		PemesananOnline
 * @subpackage	frontend
 * @category	keranjang
 * @author		I Putu Yoga Permana
 * @link		http://labs.putuyoga.com
 */
class Keranjang extends BASE_Controller {
	
	public function __construct() {
		parent::__construct('keranjang');
		$this->load->library('cart');
		$this->load->model('produk_model');
		$this->load->model('pemesanan_model');
		$this->load->helper('form');
	}
	
	public function index()
	{
		//cek update
		if($this->input->post('do_update') !== FALSE)
		{
			$this->update_kuantitas_produk();
		}
		//cek checkout
		elseif($this->input->post('do_checkout') !== FALSE)
		{
			$this->update_kuantitas_produk();
			redirect('keranjang/checkout');
		}
		$data_view['keranjang'] = $this->cart->contents();
		
		//kalau kosong, warning!
		if(empty($data_view['keranjang']) === TRUE)
		{
			$this->set_feedback('Keranjang masih kosong', 'info');
		}
		$tabel = $this->load->view('frontend/keranjang/isi_keranjang', $data_view, TRUE);
		$this->set_judul('list');
		$this->tampil($tabel);
		
	}
	
	public function update_kuantitas_produk()
	{
		foreach($this->input->post() as $rowid => $qty)
		{
			$data = array(
				'rowid' => $rowid,
				'qty' => $qty
			);
			$this->cart->update($data);
		}
		$this->set_feedback('Berhasil update keranjang', 'sukses');
	}
	
	public function tambah($id = '')
	{
		if( ! is_numeric($id))
		{
			redirect('/');
		}
		else
		{
			//ambil dari db
			$produk = $this->produk_model->get_produk_by_id($id);
			if($produk !== NULL)
			{
				if($produk['tersedia'] === '0')
				{
					$soldout = 'soldout';
				}
				else
				{
					$soldout = '';
				}
				$data_cart = array(
					'id' => $produk['id'],
					'qty' => 1,
					'price' => $produk['harga'],
					'name' => $produk['nama'],
					'soldout' => $soldout
				);
				$this->cart->insert($data_cart);
				$this->set_flash_feedback('Berhasil menambahkan ' . $produk['nama'] . ' ke keranjang', 'sukses');
				redirect('keranjang');
			}
			else
			{
				$this->set_flash_feedback('Produk yang ingin anda tambahkan ke keranjang tidak ada ! Mungkin produk sudah di hapus atau tidak tersedia lagi', 'error');
				redirect('/');
			}
		}
	
	}
	
	public function batal($rowid = '')
	{
		if($rowid !== '')
		{
			$data = array(
				'rowid' => $rowid,
				'qty' => 0
			);
			$this->cart->update($data);
		}
		redirect('keranjang');
	}
	
	public function batal_semua()
	{
		$this->cart->destroy();
		$this->set_flash_feedback('Keranjang telah dikosongkan.', 'sukses');
		redirect('keranjang');
	}
	
	public function checkout()
	{
		$this->set_judul('Checkout');
		$member = $this->get_current_member();
		if($member === NULL)
		{
			$this->set_flash_feedback('Silahkan login untuk melakukan check out. Atau register jika belum memiliki akun', 'error');
			redirect('member/login');
		}
		else
		{
			$keranjang = $this->cart->contents();
			if(empty($keranjang) === FALSE)
			{
				if($this->input->post('do_pesan') !== FALSE)
				{
					$id_member = $member['id'];
					$catatan = $this->input->post('catatan');
					$this->pemesanan_model->buat_pemesanan($id_member, $catatan, $keranjang);
					$this->set_flash_feedback('Pesanan anda sudah dibuat, silahkan kirimkan pembayaran ke rekening kami, dan konfirmasi melalui nomor telepon yang disediakan dengan menyertakan id pemesanan anda.', 'sukses');
					$this->cart->destroy();
					redirect('/');
				}
				$data_view['keranjang'] = $this->cart->contents();
				$tabel = $this->load->view('frontend/keranjang/checkout', $data_view, TRUE);
				$this->tampil($tabel);
			}
			else
			{
				$this->set_flash_feedback('Anda harus menambahkan produk ke keranjang, untuk melakukan checkout', 'error');
				redirect('/');
			}
		}
	}
	
}

/* End of file cart.php */
/* Location: ./application/controllers/cart.php */
