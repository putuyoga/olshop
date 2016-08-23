<?php
	$base_path = 'images/produk/';
	if(is_file($base_path . $id . '_large.jpg'))
	{
		$path_file = base_url() . $base_path . $id . '_large.jpg?time=' . time();
	}
	else
	{
		$path_file = base_url() . $base_path . 'no_foto_large.jpg';
	}
?>
	<div class="grid_12 imageBigBox">
		<img src="<?php echo $path_file; ?>">
	</div>
	<div class="clear"></div>	
	<div class="grid_8">
		<p>
			<?php if(trim($deskripsi) !== '') { echo $deskripsi; } else { echo 'Tidak ada deskripsi'; }?>
		</p>
	</div>
			
	<div class="grid_4">
		<p class="harga">
			Rp.<?php echo number_format($harga, 0, ',', '.'); ?>
		</p>
		<p class="addtocart">
			<?php if($member_level < 255): ?>
				<?php if($tersedia == 1): ?>
					<a href="<?php echo base_url('index.php/keranjang/tambah/' . $id); ?>" class="button">Tambah ke Keranjang</a>
				<?php else: ?>
					<a class="soldout_txt">Sold Out</a>
				<?php endif; ?>
			<?php else: ?>
			<a href="<?php echo base_url('index.php/administrator/produk/edit/' . $id); ?>" class="button">Edit produk</a>
			<?php endif; ?>
		</p>
	</div>
