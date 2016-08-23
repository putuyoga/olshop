<form action="<?php echo current_url(); ?>" method="POST">
<div class="grid_12">
	<table>
	<tbody>
		<tr>
			<td>Id</td>
			<td><?php echo $id; ?></td>
		</tr>
		<tr>
			<td>Nama</td>
			<td><input type="text" placeholder="Nama" name="nama" value="<?php echo $nama; ?>"></td>
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
