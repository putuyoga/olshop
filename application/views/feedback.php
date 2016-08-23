<div class="grid_12 feedback_<?php echo $type; ?>">
	<?php if(is_array($data_feedback)) : ?>
	<ul class="list">
	<?php foreach($data_feedback as $feedback): ?>
		<li><?php echo $feedback; ?></li>
	<?php endforeach; ?>
	</ul>
	<?php else: ?>
		<p>
			<?php echo $data_feedback; ?>
		</p>
	<?php endif; ?>
</div>
<div class="clear"></div>