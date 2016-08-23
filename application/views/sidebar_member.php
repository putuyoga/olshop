<?php $base_path = 'images/avatar/'; ?>
<h3>Members</h3>
	<div style="float: right">
		<?php
			//cek file foto
			if(is_file($base_path . $id . '.jpg'))
			{
				$path_file = base_url($base_path . $id . '.jpg?time=' . time());
			}
			else
			{
				$path_file = base_url($base_path . 'no_avatar.jpg');
			}
		?>
		<img src="<?php echo $path_file; ?>" style="width: 50px; height: 50px; margin: 10px">
	</div>
	<p>Hai, <?php echo $username; ?></p>
	<ul class="list">
		<?php if($level >= '255') : ?>
		<li>
			<a href="<?php echo base_url('index.php/administrator/dashboard'); ?>">Admin</a>
		</li>
		<?php else: ?>
		<li>
			<a href="<?php echo base_url('index.php/keranjang'); ?>">Keranjang</a>
		</li>
		<li>
			<a href="<?php echo base_url('index.php/pemesanan'); ?>">Pemesanan</a>
		</li>
		<?php endif; ?>
		<li>
			<a href="<?php echo base_url('index.php/member/pengaturan'); ?>">Pengaturan</a>
		</li>
		<li>
			<a href="<?php echo base_url('index.php/member/logout'); ?>">Logout</a>
		</li>
	</ul>
