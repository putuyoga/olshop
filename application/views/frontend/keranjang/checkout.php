
<?php echo form_open(current_url()); ?>
<?php $base_path = 'images/produk/'; ?>
<?php $i = 1; $harga_total = 0; ?>
<div class="grid_12">
	<table class="lists">
	<tbody>
		<!-- Results table headers -->
		<tr>
			<th>Nama</th>
			<th>Harga</th>
			<th>Kuantitas</th>
			<th>Total</th>
		</tr>
<?php foreach($keranjang as $items): ?>
	<?php $harga_produk = $items['qty'] * $items['price']; ?>
	<?php $harga_total += $harga_produk; ?>
	<tr>
		<td>
			<?php echo $items['name']; ?>
		</td>
		<td>
			Rp. <?php echo number_format($items['price'], 0, ',', '.'); ?>
		</td>
		<td>
			<?php echo $items['qty']; ?>
		</td>
		<td>
			Rp. <?php echo number_format($harga_produk, 0, ',', '.'); ?>
		</td>
	</tr>
<?php endforeach; ?>
	<tr>
		<td colspan="3" style="text-align: right; ">
			Total
		</td>
		<td>
			Rp. <?php echo number_format($harga_total, 0, ',', '.'); ?>
	</tbody>
	</table>
</div>
<div class="clear"></div>
<div class="grid_12" style="text-align: center; ">
	<small>Catatan untuk kami </small><br/>
	<textarea name="catatan" rows="6"></textarea><br/>
	<input type="submit" name="do_pesan" value="Pesan" class="button"> 
	<a href="<?php echo base_url('index.php/keranjang'); ?>" class="button">Kembali</a>
</div>
<div class="clear"></div>
</form>
