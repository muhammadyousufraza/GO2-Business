<?php
/**
 * Override notice fragment.
 *
 * @package WPE_Update_Source_Selector
 */

namespace WPE_Update_Source_Selector;

/**
 * The svg icon to be displayed.
 *
 * @var string $svgsrc
 */

/**
 * The dash icon to be displayed.
 *
 * @var string $dashicon
 */

/**
 * The title to be displayed.
 *
 * @var string $title
 */

/**
 * The message to be displayed.
 *
 * @var string $msg
 */
?>

<div class="notice notice-warning wpe-update-source-selector-host-override-warning">
	<?php
	if ( ! empty( $title ) ) {
		?>
		<h2>
			<?php
			if ( ! empty( $imgsrc ) ) {
				?>
				<img class="icon" src="<?php echo esc_attr( $imgsrc ); ?>" aria-hidden="true">
				<?php
			} elseif ( ! empty( $dashicon ) ) {
				?>
				<span class="dashicons <?php echo esc_attr( $dashicon ); ?>" aria-hidden="true"></span>
				<?php
			}
			echo esc_html( $title );
			?>
		</h2>
		<?php
	}
	?>
	<p>
		<?php
		echo esc_html( $msg );
		?>
	</p>
</div>
