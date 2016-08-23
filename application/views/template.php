<html>
	<head>
		<title>Shop</title>
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/960.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/text.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css" />
	</head>
	<body>
	<div id="wrap" class="container_16">
		<div id="header" class="grid_16">
			<a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>images/logo.png"></a>
		</div>
		<div id="left" class="grid_4">
			<?php echo $sidebar_member; ?>
			<?php echo $sidebar_halaman; ?>
			<?php echo $sidebar_kategori; ?>
		</div>
		<div id="right">
			<div class="grid_12 pageTitle">
				<h3>
					<?php echo $judul; ?>
				</h3>
			</div>
			<div class="clear"></div>
			<?php if(isset($menu)) { echo $menu; } ?>
			<?php if(isset($feedback)) { echo $feedback; } ?>
			<?php echo $konten; ?>
		</div>
		
	</div>
	</body>
</html>