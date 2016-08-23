<?php echo form_open(current_url()); ?>
<?php $base_path = 'images/produk/'; ?>
<?php $i = 1; ?>
<?php foreach($keranjang as $items): ?>
<?php
	if(is_file($base_path . $items['id'] . '.jpg'))
	{
		$path_file = base_url() . $base_path . $items['id'] . '.jpg';
	}
	else
	{
		$path_file = base_url() . $base_path . 'no_foto.jpg';
	}
?>
<div class="grid_2 imageBox items <?php echo $items['soldout'] ?>">
	<a href="produk.html"><img src="<?php echo $path_file; ?>"></a>
</div>
<div class="grid_4 items">
	<h3>
		<?php echo $items['name']; ?>
	</h3>
	<p>
		Rp. <?php echo number_format($items['price'], 0, ',', '.'); ?> x <input type="text" value="<?php echo $items['qty']; ?>" size="1" name="<?php echo $items['rowid']; ?>"> buah
	</p>
	<p style="margin-top: 10px; ">
		<a href="<?php echo base_url('index.php/keranjang/batal/' . $items['rowid']); ?>" class="button">Batalkan</a>
	</p>
</div>
<?php if(($i % 2 ) === 0) : ?>
<div class="clear"></div>
<?php endif; $i++; ?>
<?php endforeach; ?>
<div class="clear"></div>
<?php if( ! empty($keranjang)): ?>
<div class="grid_12 itemsOpt">
	<input type="submit" class="button" name="do_checkout" value="Checkout"/>
	<input type="submit" class="button" name="do_update" value="Update"/> 
	<a href="<?php echo base_url(); ?>" class="button">Lanjutkan belanja</a> 
	<a href="<?php echo base_url('index.php/keranjang/batal_semua'); ?>" class="button">Kosongkan keranjang</a>
</div>
<?php endif; ?>
</form>