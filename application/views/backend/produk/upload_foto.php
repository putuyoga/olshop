<?php
	$base_path = 'images/produk/';
	if(is_file($base_path . $id . '.jpg'))
	{
		$path_file = base_url() . $base_path . $id . '.jpg?time=' . time();
	}
	else
	{
		$path_file = base_url() . $base_path . 'no_foto.jpg?time=' . time();
	}
	
	if(is_file($base_path . $id . '_large.jpg'))
	{
		$path_file_large = base_url() . $base_path . $id . '_large.jpg?time=' . time();
	}
	else
	{
		$path_file_large = base_url() . $base_path . 'no_foto_large.jpg?time=' . time();
	}
?>
<?php echo form_open_multipart(current_url());?>
<div class="grid_12">
	<img src="<?php echo $path_file_large; ?>" width="700" height="302" />
	<br/>
	<input type="file" name="foto_large" size="20" />
	<br/>
	<small>Format file .jpg dan ukuran maksimum dimensi gambar 700 x 302</small>
	<input type="submit" class="button" name="upload_foto_large" value="Upload">
</div>
<div class="clear"></div>
<div class="grid_12">
	<img src="<?php echo $path_file; ?>" width="250" height="250" />
	<br/>
	<input type="file" name="foto" size="20" />
	<br/>
	<small>Format file .jpg dan ukuran maksimum dimensi gambar 250 x 250</small>
	<input type="submit" class="button" name="upload_foto" value="Upload">
</div>
<div class="clear"></div>
<div class="grid_12">
	<a href="<?php echo base_url('index.php/administrator/produk/edit/' . $id); ?>" class="button">Kembali ke halaman edit</a>
</div>
<div class="clear"></div>
</form>