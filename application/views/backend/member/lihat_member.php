<?php
	$level_status = '';
	
	switch($level)
	{
		case '1': $level_status = 'Member"'; break;
		case '255': $level_status = 'Admin'; break;
		case '256': $level_status = 'Super Admin'; break;
	}
	$base = 'index.php/administrator/member';
	$base_path = 'images/avatar/';
?>
<div class="grid_12">
	<table>
	<tbody>
		<tr>
			<td>Id</td>
			<td><?php echo $id; ?></td>
			<td rowspan="6"style="text-align: center;">
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
			</td>
		</tr>
		<tr>
			<td>Level</td>
			<td><?php echo $level_status; ?></td>
		</tr>
		<tr>
			<td>Username</td>
			<td><?php echo $username; ?></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><?php echo $email; ?></td>
		</tr>
		<tr>
			<td>Nama Lengkap</td>
			<td><?php echo $nama_lengkap; ?></td>
		</tr>
		<tr>
			<td>No. Hp</td>
			<td><?php echo $no_hp; ?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td colspan="2">
				<?php echo $alamat; ?>
			</td>
		</tr>
	</tbody>
	</table>
</div>
<div class="clear"></div>
