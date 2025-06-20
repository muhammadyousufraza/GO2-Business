<?php
/**
 * Settings tab content.
 *
 * @package WPE_Update_Source_Selector
 */

namespace WPE_Update_Source_Selector;

use WPE_Update_Source_Selector\Sources\Source;

/**
 * The enabled sources.
 *
 * @var array<string,Source> $sources
 */

/**
 * The current source.
 *
 * @var Source $current_source
 */

/**
 * The default source's description.
 *
 * @var string $default_source_desc
 */

/**
 * Site preference source key.
 *
 * @var string $site_preference
 */

/**
 * Should saving settings be disabled?
 *
 * @var bool $disabled
 */

// If site preference not set, use current source as default selection.
$wpe_uss_preferred_source = empty( $site_preference ) ? $current_source::get_key() : $site_preference;
?>

<h2><?php esc_html_e( 'WordPress Update Source Settings', 'wpe-update-source-selector' ); ?></h2>

<p>
	<?php
	esc_html_e(
		'When you install or update WordPress core, plugins, and themes, those assets are downloaded from a remote source via an API. If you do not wish to use the default source for this site, choose the source that works best for your site and workflows.',
		'wpe-update-source-selector'
	);
	?>
</p>

<div class="wpe-update-source-selector-source-settings-wrapper<?php echo $disabled ? ' disabled' : ''; ?>">
	<form method="post">
		<input type="hidden" name="action" value="save"/>
		<?php wp_nonce_field( 'wpe-update-source-selector-nonce', '_wpe_uss_nonce' ); ?>

		<div class="wpe-update-source-selector-settings-section wpe-update-source-selector-source-setting">
			<div id="wpe-update-source-selector-source-setting-title" class="wpe-update-source-selector-setting-title">
				<?php esc_html_e( 'Source settings', 'wpe-update-source-selector' ); ?>
			</div>
			<div role="radiogroup" aria-labelledby="wpe-update-source-selector-source-setting-title" class="wpe-update-source-selector-setting-options">
				<div class="wpe-update-source-selector-setting-option">
					<input
						type="radio"
						id="wpe-update-source-selector-default"
						name="select-source"
						value="no"
						<?php echo $site_preference ? '' : ' checked="checked"'; ?>
						<?php echo $disabled ? ' disabled="disabled"' : ''; ?>
					>
					<label for="wpe-update-source-selector-default">
						<span class="option-title">
							<?php
							esc_html_e(
								'Use the default source configured for this site',
								'wpe-update-source-selector'
							);
							?>
						</span>
						<br>
						<span class="option-detail">
							<?php echo esc_html( $default_source_desc ); ?>
						</span>
					</label>
				</div>
				<div class="wpe-update-source-selector-setting-option">
					<input
						type="radio"
						id="wpe-update-source-selector-select"
						name="select-source"
						value="yes"
						<?php echo $site_preference ? ' checked="checked"' : ''; ?>
						<?php echo $disabled ? ' disabled="disabled"' : ''; ?>
					>
					<label for="wpe-update-source-selector-select">
					<span class="option-title">
						<?php esc_html_e( 'Select a specific source', 'wpe-update-source-selector' ); ?>
					</span>
					</label>
				</div>
			</div>
		</div>
		<div class="wpe-update-source-selector-settings-section wpe-update-source-selector-preferred-source<?php echo $site_preference ? '' : ' hidden'; ?>">
			<div id="wpe-update-source-selector-preferred-source-title" class="wpe-update-source-selector-setting-title">
				<?php esc_html_e( 'Source options', 'wpe-update-source-selector' ); ?>
			</div>
			<div role="radiogroup" aria-labelledby="wpe-update-source-selector-preferred-source-title" class="wpe-update-source-selector-setting-options">
				<?php foreach ( $sources as $wpe_uss_source_key => $wpe_uss_source ) : ?>
					<div class="wpe-update-source-selector-setting-option">
						<input
							type="radio"
							id="wpe-update-source-selector-prefer-<?php echo esc_attr( $wpe_uss_source_key ); ?>"
							name="preferred-source"
							value="<?php echo esc_attr( $wpe_uss_source_key ); ?>"
							<?php echo $wpe_uss_source_key === $wpe_uss_preferred_source ? ' checked="checked"' : ''; ?>
							<?php echo $disabled ? ' disabled="disabled"' : ''; ?>
						>
						<label for="wpe-update-source-selector-prefer-<?php echo esc_attr( $wpe_uss_source_key ); ?>">
							<span class="option-title">
								<?php echo esc_html( $wpe_uss_source::get_name() ); ?>
							</span>
							<br>
							<code class="option-detail">
								<?php echo esc_html( $wpe_uss_source::get_display_domain() ); ?>
							</code>
						</label>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<div class="wpe-update-source-selector-settings-section wpe-update-source-selector-save-changes">
			<input
				type="submit"
				value="<?php esc_html_e( 'Save Changes', 'wpe-update-source-selector' ); ?>"
				class="button-primary"
				<?php echo $disabled ? ' disabled="disabled"' : ''; ?>
			>
		</div>
	</form>
</div>
