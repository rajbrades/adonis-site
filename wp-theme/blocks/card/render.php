<?php
/**
 * Adonis Card — render callback.
 *
 * @var array $attributes
 */
$idx       = isset($attributes['idx'])       ? (string) $attributes['idx']       : '';
$eyebrow   = isset($attributes['eyebrow'])   ? (string) $attributes['eyebrow']   : '';
$title     = isset($attributes['title'])     ? (string) $attributes['title']     : '';
$body      = isset($attributes['body'])      ? (string) $attributes['body']      : '';
$cta_label = isset($attributes['ctaLabel'])  ? trim((string) $attributes['ctaLabel']) : '';
$cta_url   = isset($attributes['ctaUrl'])    ? trim((string) $attributes['ctaUrl'])   : '';
$tone      = (isset($attributes['tone']) && $attributes['tone'] === 'elevated') ? 'elevated' : 'default';

$wrapper = get_block_wrapper_attributes([
    'class' => 'adonis-card adonis-card--' . $tone,
]);
?>
<article <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
  <?php if ($idx !== '' || $eyebrow !== '') : ?>
    <header class="adonis-card__head">
      <?php if ($idx !== '') : ?>
        <span class="adonis-card__idx idx-label"><?php echo esc_html($idx); ?></span>
      <?php endif; ?>
      <?php if ($eyebrow !== '') : ?>
        <span class="adonis-card__eyebrow idx-label"><?php echo esc_html($eyebrow); ?></span>
      <?php endif; ?>
    </header>
  <?php endif; ?>

  <?php if ($title !== '') : ?>
    <h3 class="adonis-card__title"><?php echo wp_kses_post($title); ?></h3>
  <?php endif; ?>

  <?php if ($body !== '') : ?>
    <div class="adonis-card__body"><?php echo wp_kses_post(wpautop($body)); ?></div>
  <?php endif; ?>

  <?php if ($cta_label !== '' && $cta_url !== '') : ?>
    <a class="adonis-card__cta" href="<?php echo esc_url($cta_url); ?>">
      <span><?php echo esc_html($cta_label); ?></span>
      <span aria-hidden="true">&rarr;</span>
    </a>
  <?php endif; ?>
</article>
