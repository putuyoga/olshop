<div class="grid_12 detailPemesanan">
	<p>
		<?php echo $kategori['deskripsi']; ?>
	</p>
</div>
<div class="clear"></div>
<?php $class = 'grid_3 imageBox'; $base_path = 'images/produk/'; ?>
<?php if($rows !== NULL): ?>
	<?php $i = 1; ?>
	<?php foreach($rows as $row): ?>
			<?php
				//cek file foto
				if(is_file($base_path . $row['id'] . '.jpg'))
				{
					$path_file = base_url() . $base_path . $row['id'] . '.jpg';
				}
				else
				{
					$path_file = base_url() . $base_path . 'no_foto.jpg';
				}
				//cek sold out
				if($row['tersedia'] === '0')
				{
					$soldout = ' soldout';
				}
				else
				{
					$soldout = '';
				}
			?>
			<div class="<?php echo $class . $soldout; ?>">
				<a href="<?php echo base_url('index.php/produk/lihat/' . $row['id']); ?>"><img src="<?php echo $path_file; ?>" width="160px" height="160px"></a>
			</div>
			<?php if(($i % 4) === 0) : ?>
				<div class="clear"></div>
			<?php endif; $i++; ?>
	<?php endforeach; ?>
<?php endif; ?>
<div class="clear"></div>
<div class="grid_12 list_button">
	<div>
	<?php echo $pagelink; ?>
	</div>
</div>