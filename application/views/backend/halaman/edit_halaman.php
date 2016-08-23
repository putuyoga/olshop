<form action="<?php echo current_url(); ?>" method="POST">
<div class="grid_12">
	<table>
	<tbody>
		<tr>
			<td><input type="text" placeholder="Judul" name="judul" value="<?php echo $judul; ?>"></td>
		</tr>
		<tr>
			<td>
				<textarea name="konten" rows="8"><?php echo $konten; ?></textarea>
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
