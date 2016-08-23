<?php
	$mem_selected = '';
	$adm_selected = '';
	$sup_selected = '';
	
	switch($level)
	{
		case '1': $mem_selected = ' selected="selected"'; break;
		case '255': $adm_selected = ' selected="selected"'; break;
		case '256': $sup_selected = ' selected="selected"'; break;
	}
	$base = 'index.php/administrator/member';
	$base_path = 'images/avatar/';
?>
<?php echo form_open_multipart(current_url()); ?>
<div class="grid_12">
	<table>
	<tbody>
		<tr>
			<td>Id</td>
			<td><?php echo $id; ?></td>
			<td rowspan="7" class="box" style="text-align: center;">
				<?php
					//cek file foto
					if(is_file($base_path . $id . '.jpg'))
					{
						$path_file = base_url($base_path . $id . '.jpg');
					}
					else
					{
						$path_file = base_url($base_path . 'no_avatar.jpg');
					}
				?>
				<img src="<?php echo $path_file; ?>" style="width: 150px; height: 150px; margin: 10px; ">
				<br/>
				<input type="file" name="avatar"> <input type="submit" name="do_upload" value="Upload" class="button">
				<br/>
				<small>Tipe file .jpg, ukuran gambar maks 60kb, dengan dimensi maks 150x150</small>
			</td>
		</tr>
		<tr>
			<td>Level</td>
			<td>
				<select name="level">
					<option value="1"<?php echo $mem_selected; ?>>Member</option>
					<option value="255"<?php echo $adm_selected; ?>>Admin</option>
					<option value="256"<?php echo $sup_selected; ?>>Super Admin</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Username</td>
			<td><input type="text" placeholder="Username" name="username" value="<?php echo $username; ?>"></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input type="text" placeholder="Email" name="email" value="<?php echo $email; ?>"></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><a href="<?php echo base_url($base . '/reset_password/' . $id); ?>">Reset password</a></td>
		</tr>
		<tr>
			<td>Nama Lengkap</td>
			<td><input type="text" placeholder="Nama Lengkap" name="nama_lengkap" value="<?php echo $nama_lengkap; ?>"></td>
		</tr>
		<tr>
			<td>No. Hp</td>
			<td><input type="text" placeholder="No. HP" name="no_hp" value="<?php echo $no_hp; ?>"></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td colspan="2">
				<textarea name="alamat" rows="5"><?php echo $alamat; ?></textarea>
			</td>
		</tr>
	</tbody>
	</table>
</div>
<div class="clear"></div>
<div class="grid_12">
	<input type="submit" name="do_edit" value="Edit" class="button"> <input type="reset" value="Reset" class="button"> 
	<a href="<?php echo base_url('index.php/administrator/member/hapus/' . $id); ?>" class="button" onclick="return confirm('Anda yakin ingin menghapus member?')">Hapus</a>
</div>
<div class="clear"></div>
</form>
