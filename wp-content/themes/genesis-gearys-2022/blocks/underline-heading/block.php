<?php
	/**
	 * The markup for our "underline heading" custom block in Genesis Framework.
	 */
?>

<div class="genesis-custom-block block-title text-<?php block_field( 'alignment' ); ?> line-<?php block_field( 'line-color' ); ?>">
	<p><?php block_field( 'over-heading' ); ?></p>
	<h3><?php block_field( 'main-heading' ); ?></h3>
</div>
