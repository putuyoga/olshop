<form action="<?php echo current_url(); ?>" method="POST">
<div class="grid_12">
	<table>
	<tbody>
		<tr>
			<td>Username</td>
			<td><input type="text" placeholder="Username" name="username"></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input type="text" placeholder="Email" name="email"></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" placeholder="password" name="password"></td>
		</tr>
		<tr>
			<td>Retype Password</td>
			<td><input type="password" placeholder="Ketikkan ulang password" name="re_password"></td>
		</tr>
		<tr>
			<td>Nama Lengkap</td>
			<td><input type="text" placeholder="Nama Lengkap" name="nama_lengkap"></td>
		</tr>
		<tr>
			<td>No. Hp</td>
			<td><input type="text" placeholder="No. HP" name="no_hp"></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td><textarea name="alamat"></textarea></td>
		</tr>
	</tbody>
	</table>
</div>
<div class="clear"></div>
<div class="grid_12">
	<input type="submit" name="do_register" value="Register" class="button"> <input type="reset" value="Reset" class="button">
</div>
<div class="clear"></div>
</form>