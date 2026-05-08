<?php
/**
 * Adonis Product Card — render callback.
 *
 * Inner content ($content) is the rendered HTML of the inner blocks — the
 * caller is expected to drop a core/html block containing the product-still
 * SVG between the opening and closing block comments. The SVG is echoed
 * unescaped because it's template-author content (this block isn't intended
 * to be edited by site admins).
 *
 * title, description, specs, and priceNum accept inline <em>, <strong>, <b>,
 * <br>. Use HTML entities (&lt; / &amp;) in attribute values where literal
 * angle brackets / ampersands appear in copy.
 *
 * @var array  $attributes
 * @var string $content    Rendered inner blocks (the product-still SVG).
 */
$tags          = (isset($attributes['tags']) && is_array($attributes['tags']))   ? $attributes['tags']  : [];
$category      = isset($attributes['category'])     ? (string) $attributes['category']     : '';
$title         = isset($attributes['title'])        ? (string) $attributes['title']        : '';
$description   = isset($attributes['description'])  ? (string) $attributes['description']  : '';
$specs         = (isset($attributes['specs']) && is_array($attributes['specs']))  ? $attributes['specs'] : [];
$price_num     = isset($attributes['priceNum'])     ? (string) $attributes['priceNum']     : '';
$price_per     = isset($attributes['pricePer'])     ? (string) $attributes['pricePer']     : '';
$price_note    = isset($attributes['priceNote'])    ? (string) $attributes['priceNote']    : '';
$details_url   = isset($attributes['detailsUrl'])   ? (string) $attributes['detailsUrl']   : '#';
$details_label = isset($attributes['detailsLabel']) ? (string) $attributes['detailsLabel'] : 'Details';
$primary_url   = isset($attributes['primaryUrl'])   ? (string) $attributes['primaryUrl']   : '#';
$primary_label = isset($attributes['primaryLabel']) ? trim((string) $attributes['primaryLabel']) : '';
$wide          = ! empty($attributes['wide']);

$wrapper_class = 'adonis-prod' . ($wide ? ' adonis-prod--wide' : '');
$wrapper       = get_block_wrapper_attributes(['class' => $wrapper_class]);

$inline_kses = ['em' => [], 'strong' => [], 'b' => [], 'br' => []];
?>
<article <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>

	<?php if (!empty($tags)) : ?>
		<div class="adonis-prod__tags">
			<?php foreach ($tags as $tag) :
				$tag_label   = isset($tag['label'])   ? (string) $tag['label']   : '';
				$tag_variant = isset($tag['variant']) ? (string) $tag['variant'] : '';
				if ($tag_label === '') { continue; }
				if (!in_array($tag_variant, ['hot', 'idx', ''], true)) { $tag_variant = ''; }
				$tag_class = 'adonis-prod__tag' . ($tag_variant !== '' ? ' adonis-prod__tag--' . $tag_variant : '');
			?>
				<span class="<?php echo esc_attr($tag_class); ?>"><?php echo esc_html($tag_label); ?></span>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="adonis-prod__still"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped — template-author SVG ?></div>

	<div class="adonis-prod__body">
		<?php if ($category !== '') : ?>
			<div class="adonis-prod__cat"><?php echo esc_html($category); ?></div>
		<?php endif; ?>

		<?php if ($title !== '') : ?>
			<h3 class="adonis-prod__title"><?php echo wp_kses($title, $inline_kses); ?></h3>
		<?php endif; ?>

		<?php if ($description !== '') : ?>
			<p class="adonis-prod__desc"><?php echo wp_kses($description, $inline_kses); ?></p>
		<?php endif; ?>

		<?php if (!empty($specs)) : ?>
			<ul class="adonis-prod__specs">
				<?php foreach ($specs as $spec) : ?>
					<li><?php echo wp_kses((string) $spec, $inline_kses); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>

	<div class="adonis-prod__foot">
		<div class="adonis-prod__price">
			<?php if ($price_num !== '') : ?>
				<span class="adonis-prod__price-num"><?php echo wp_kses($price_num, $inline_kses); ?></span>
			<?php endif; ?>
			<?php if ($price_per !== '') : ?>
				<span class="adonis-prod__price-per"><?php echo esc_html($price_per); ?></span>
			<?php endif; ?>
			<?php if ($price_note !== '') : ?>
				<span class="adonis-prod__price-note"><?php echo esc_html($price_note); ?></span>
			<?php endif; ?>
		</div>
		<div class="adonis-prod__buys">
			<a href="<?php echo esc_url($details_url); ?>" class="adonis-prod__btn"><?php echo esc_html($details_label); ?></a>
			<?php if ($primary_label !== '') : ?>
				<a href="<?php echo esc_url($primary_url); ?>" class="adonis-prod__btn adonis-prod__btn--primary"><?php echo esc_html($primary_label); ?></a>
			<?php endif; ?>
		</div>
	</div>

</article>
