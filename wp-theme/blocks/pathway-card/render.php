<?php
/**
 * Adonis Pathway Card — render callback.
 *
 * title, summary, and bestFor accept inline <em>, <strong>, <br>.
 * steps is an array of { title, duration } pairs. CTA delegates to
 * adonis/button via render_block — variant is configurable per card so
 * Precision can be accent while Express + Lab are outline.
 *
 * @var array $attributes
 */
$badge        = isset($attributes['badge'])         ? (string) $attributes['badge']         : '';
$title        = isset($attributes['title'])         ? (string) $attributes['title']         : '';
$summary      = isset($attributes['summary'])       ? (string) $attributes['summary']       : '';
$best_lbl     = isset($attributes['bestForLabel'])  ? (string) $attributes['bestForLabel']  : 'Best for';
$best_for     = isset($attributes['bestFor'])       ? (string) $attributes['bestFor']       : '';
$steps        = (isset($attributes['steps']) && is_array($attributes['steps'])) ? $attributes['steps'] : [];
$cta_label    = isset($attributes['ctaLabel'])      ? trim((string) $attributes['ctaLabel'])      : '';
$cta_url      = isset($attributes['ctaUrl'])        ? trim((string) $attributes['ctaUrl'])        : '#';
$cta_italic   = isset($attributes['ctaItalicWord']) ? trim((string) $attributes['ctaItalicWord']) : '';
$cta_variant  = isset($attributes['ctaVariant'])    ? (string) $attributes['ctaVariant']    : 'outline';
if (!in_array($cta_variant, ['accent', 'ghost', 'outline'], true)) {
    $cta_variant = 'outline';
}

$wrapper = get_block_wrapper_attributes(['class' => 'adonis-path']);

$inline_kses = ['em' => [], 'strong' => [], 'br' => []];

$cta_html = '';
if ($cta_label !== '') {
    $cta_html = render_block([
        'blockName'    => 'adonis/button',
        'attrs'        => [
            'label'      => $cta_label,
            'url'        => $cta_url,
            'variant'    => $cta_variant,
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

	<?php if ($badge !== '') : ?>
		<span class="adonis-path__badge"><?php echo esc_html($badge); ?></span>
	<?php endif; ?>

	<?php if ($title !== '') : ?>
		<h3 class="adonis-path__title"><?php echo wp_kses($title, $inline_kses); ?></h3>
	<?php endif; ?>

	<?php if ($summary !== '') : ?>
		<p class="adonis-path__summary"><?php echo wp_kses($summary, $inline_kses); ?></p>
	<?php endif; ?>

	<?php if ($best_for !== '') : ?>
		<div class="adonis-path__best">
			<div class="adonis-path__best-k"><?php echo esc_html($best_lbl); ?></div>
			<div class="adonis-path__best-v"><?php echo wp_kses($best_for, $inline_kses); ?></div>
		</div>
	<?php endif; ?>

	<?php if (!empty($steps)) : ?>
		<ol class="adonis-path__steps">
			<?php foreach ($steps as $step) :
				$step_title    = isset($step['title'])    ? (string) $step['title']    : '';
				$step_duration = isset($step['duration']) ? (string) $step['duration'] : '';
				if ($step_title === '') { continue; }
			?>
				<li>
					<span class="adonis-path__step-t"><?php echo esc_html($step_title); ?></span>
					<span class="adonis-path__step-d"><?php echo esc_html($step_duration); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>
	<?php endif; ?>

	<?php if ($cta_html !== '') : ?>
		<div class="adonis-path__cta-wrap"><?php echo $cta_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
	<?php endif; ?>

</article>
