<?php 

	$base_path = 'images/produk/';
	$bb_selected = '';
	$l_selected = '';
	$sk_selected = '';
	
	switch($status)
	{
		case '0': $bb_selected = ' selected="selected"'; break;
		case '1': $l_selected = ' selected="selected"'; break;
		case '2': $sk_selected = ' selected="selected"'; break;
	}
	$base = 'index.php/administrator/member';
?>

<div class="grid_12 detailPemesanan">
	<?php echo form_open(current_url()); ?>
	<p>
		<a href="<?php echo base_url('index.php/administrator/pemesanan/hapus/' . $id_pesanan); ?>" style="float: right; " class="button" onclick="return confirm('Anda yakin ingin menghapus pesanan?')">Hapus Pesanan</a>
		Id Pesanan: <?php echo $id_pesanan; ?><br/>
		Pemesan : <a href="<?php echo base_url('index.php/administrator/member/lihat/' . $id_member); ?>" target="_blank"><?php echo $username; ?></a><br/>
		Banyak Barang : <?php echo $banyak_barang; ?> buah<br/>
		Total Harga : Rp. <?php echo $harga_total; ?><br/>
		Status : 
		<select name="status">
			<option value="0"<?php echo $bb_selected; ?>>Belum Bayar</option>
			<option value="1"<?php echo $l_selected; ?>>Lunas</option>
			<option value="2"<?php echo $sk_selected; ?>>Sudah Kirim</option>
		</select>
		<input type="submit" name="do_rubah_status" value="Ubah" class="Button">
	</p>
	</form>
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
<?php echo form_open(current_url()); ?>
	<a href="<?php echo base_url('index.php/administrator/produk/edit/' . $items['id']); ?>"><img src="<?php echo $path_file; ?>" style="width: 160px; height: 160px;"></a>
	<?php echo form_hidden('id_produk', $items['id']); ?>
	<input type="submit" name="do_hapus" value="Hapus" class="button" style="float: right;" onclick="return confirm('Anda yakin ingin menghapus items ini di pesanan?')">
	<span style="font-weight: bold;">
		<?php echo $items['nama']; ?>
	</span>
	<br/>
	@ Rp. <?php echo $items['harga']; ?>
	<br/>
	<?php echo $items['kuantitas']; ?> buah
</form>
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
