<?php 

	$base_path = 'images/produk/';
	$status_txt = '';
	
	switch($status)
	{
		case '0': $status_txt = '<span class="merah">Belum bayar</span>'; break;
		case '1': $status_txt = '<span class="biru">Lunas</span>'; break;
		case '2': $status_txt = '<span class="hijau">Sudah Kirim</span>'; break;
	}
?>

<div class="grid_12 detailPemesanan">
	<p>
		Id Pesanan: <?php echo $id_pesanan; ?><br/>
		Banyak Barang : <?php echo $banyak_barang; ?> buah<br/>
		Total Harga : Rp. <?php echo $harga_total; ?><br/>
		Status : <?php echo $status_txt; ?>
	</p>
</div>
<div class="clear"></div>
<div class="grid_12 detailPemesanan">
	<small>Catatan dari pemesan : </small>
	<p><?php echo $catatan; ?></p>
</div>
<div class="clear"></div>
<?php $i = 1; ?>
<?php if(empty($pesanan) === FALSE): ?>
<?php foreach($pesanan as $items) : ?>
<?php
	//cek file foto
	if(is_file($base_path . $items['id'] . '.jpg'))
	{
		$path_file = base_url() . $base_path . $items['id'] . '.jpg';
	}
	else
	{
		$path_file = base_url() . $base_path . 'no_foto.jpg';
	}
	//cek sold out
	if($items['tersedia'] === '0')
	{
		$soldout = ' soldout';
	}
	else
	{
		$soldout = '';
	}
?>
<div class="grid_3 imageBox<?php echo $soldout; ?>">

	<a href="<?php echo base_url('index.php/produk/lihat/' . $items['id']); ?>"><img src="<?php echo $path_file; ?>" style="width: 160px; height: 160px;"></a>
	
	<span style="font-weight: bold;">
		<?php echo $items['nama']; ?>
	</span>
	<br/>
	@ Rp. <?php echo $items['harga']; ?>
	<br/>
	<?php echo $items['kuantitas']; ?> buah

</div>
<?php if(($i % 4) === 0) : ?>
<div class="clear"></div>
<?php endif; ?>
<?php endforeach; ?>
<?php else: ?>
<div class="grid_12 detailPemesanan">
	<p>
		Tidak ada Produk!!
	</p>
</div>
<?php endif; ?>
