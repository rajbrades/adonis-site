<?php
/**
 * Adonis Condition Card — render callback.
 *
 * Title and bestFor accept inline <em>, <strong>, <br> for italics + emphasis.
 * Checks and rxList accept arrays; rxList items are { name, formula } pairs.
 *
 * @var array $attributes
 */
$image_tag      = isset($attributes['imageTag'])      ? (string) $attributes['imageTag']      : '';
$image_label    = isset($attributes['imageLabel'])    ? (string) $attributes['imageLabel']    : '';
$title          = isset($attributes['title'])         ? (string) $attributes['title']         : '';
$best_for_label = isset($attributes['bestForLabel'])  ? (string) $attributes['bestForLabel']  : 'Best for';
$best_for       = isset($attributes['bestFor'])       ? (string) $attributes['bestFor']       : '';
$checks         = (isset($attributes['checks']) && is_array($attributes['checks']))  ? $attributes['checks']  : [];
$rx_list        = (isset($attributes['rxList']) && is_array($attributes['rxList']))  ? $attributes['rxList']  : [];
$cta_label      = isset($attributes['ctaLabel'])      ? trim((string) $attributes['ctaLabel'])      : '';
$cta_url        = isset($attributes['ctaUrl'])        ? trim((string) $attributes['ctaUrl'])        : '#';
$cta_italic     = isset($attributes['ctaItalicWord']) ? trim((string) $attributes['ctaItalicWord']) : '';

$wrapper = get_block_wrapper_attributes(['class' => 'adonis-cond']);

$inline_kses = ['em' => [], 'strong' => [], 'br' => []];

// CTA delegates to adonis/button (variant=outline) via render_block, so the
// button primitive remains the single source of truth for pill markup,
// hover/focus, and italic-em behavior.
$cta_html = '';
if ($cta_label !== '') {
    $cta_html = render_block([
        'blockName'    => 'adonis/button',
        'attrs'        => [
            'label'      => $cta_label,
            'url'        => $cta_url,
            'variant'    => 'outline',
            'italicWord' => $cta_italic,
            'arrow'      => true,
        ],
        'innerBlocks'  => [],
        'innerHTML'    => '',
        'innerContent' => [],
    ]);
}
?>
<article <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

	<?php if ($image_tag !== '' || $image_label !== '') : ?>
		<div class="adonis-cond__img">
			<div class="adonis-cond__grain" aria-hidden="true"></div>
			<?php if ($image_tag !== '') : ?>
				<span class="adonis-cond__tag"><?php echo esc_html($image_tag); ?></span>
			<?php endif; ?>
			<?php if ($image_label !== '') : ?>
				<div class="adonis-cond__lbl"><em><?php echo esc_html($image_label); ?></em></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="adonis-cond__body">

		<?php if ($title !== '') : ?>
			<h3 class="adonis-cond__title"><?php echo wp_kses($title, $inline_kses); ?></h3>
		<?php endif; ?>

		<?php if ($best_for !== '') : ?>
			<div class="adonis-cond__best-for">
				<div class="adonis-cond__best-for-k"><?php echo esc_html($best_for_label); ?></div>
				<div class="adonis-cond__best-for-v"><?php echo wp_kses($best_for, $inline_kses); ?></div>
			</div>
		<?php endif; ?>

		<?php if (!empty($checks)) : ?>
			<ul class="adonis-cond__checks">
				<?php foreach ($checks as $check) : ?>
					<li><?php echo wp_kses((string) $check, $inline_kses); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if (!empty($rx_list)) : ?>
			<ul class="adonis-cond__rx">
				<?php foreach ($rx_list as $rx) :
					$name    = isset($rx['name'])    ? (string) $rx['name']    : '';
					$formula = isset($rx['formula']) ? (string) $rx['formula'] : '';
					if ($name === '') { continue; }
				?>
					<li>
						<span class="adonis-cond__rx-n"><?php echo esc_html($name); ?></span>
						<span class="adonis-cond__rx-f"><?php echo esc_html($formula); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ($cta_html !== '') : ?>
			<div class="adonis-cond__cta-wrap"><?php echo $cta_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		<?php endif; ?>

	</div>

</article>
