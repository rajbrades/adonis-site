<?php
/**
 * Adonis Button — render callback.
 *
 * @var array $attributes
 */
$label   = isset($attributes['label']) ? (string) $attributes['label'] : 'Get started';
$url     = isset($attributes['url']) ? (string) $attributes['url'] : '#';
$variant = (isset($attributes['variant']) && $attributes['variant'] === 'ghost') ? 'ghost' : 'accent';
$italic  = isset($attributes['italicWord']) ? trim((string) $attributes['italicWord']) : '';

$wrapper = get_block_wrapper_attributes([
    'class' => 'adonis-btn adonis-btn--' . $variant,
]);

$inner = esc_html($label);
if ($italic !== '' && stripos($label, $italic) !== false) {
    $inner = preg_replace(
        '/' . preg_quote($italic, '/') . '/i',
        '<em>$0</em>',
        esc_html($label),
        1
    );
}
?>
<a href="<?php echo esc_url($url); ?>" <?php echo $wrapper; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo $inner; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
