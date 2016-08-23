<?php $base_path = 'index.php/administrator/produk/upload_foto/' . $id; ?>
<?php
	$y_sel = '';
	$t_sel = '';
	
	switch($tersedia) {
		case 1: $y_sel = ' selected="selected"'; break;
		case 0: $t_sel = ' selected="selected"'; break;
	}
?>
<?php echo form_open(current_url());?>
<div class="grid_12">
	<table>
	<tbody>
		<tr>
			<td>Id</td>
			<td><?php echo $id; ?></td>
			<td rowspan="4">
				<a class="button" href="<?php echo base_url($base_path); ?>">Upload Foto</a>
			</td>
		</tr>
		<tr>
			<td>Nama</td>
			<td><input type="text" placeholder="Nama" name="nama" value="<?php echo $nama; ?>"></td>
		</tr>
		<tr>
			<td>Harga (Rp)</td>
			<td><input type="text" placeholder="Harga" name="harga" value="<?php echo $harga; ?>"></td>
		</tr>
		<tr>
			<td>Kategori</td>
			<td>
				<select name="id_kategori">
					<?php foreach($kategori as $kat): ?>
					<?php if($id_kategori == $kat['id']) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
					<option value="<?php echo $kat['id']; ?>"<?php echo $selected; ?>><?php echo ucwords($kat['nama']); ?></option>
					<?php $selected = ''; ?>
					<?php endforeach; ?>
					<?php if ($id_kategori == 0) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
					<option value="0"<?php echo $selected; ?>>Tanpa Kategori</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Tersedia</td>
			<td>
				<select name="tersedia">
					<option value="1"<?php echo $y_sel; ?>>Ya</option>
					<option value="0"<?php echo $t_sel; ?>>Tidak</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Deskripsi</td>
			<td colspan="2">
				<textarea name="deskripsi" rows="8"><?php echo $deskripsi; ?></textarea>
			</td>
		</tr>
	</tbody>
	</table>
</div>
<div class="clear"></div>
<div class="grid_12">
	<input type="submit" name="do_edit" value="Edit" class="button"> <input type="reset" value="Reset" class="button">
</div>
<div class="clear"></div>
</form>