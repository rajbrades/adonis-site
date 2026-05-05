<?php
/**
 * Plugin Name: Adonis Order ID
 * Description: Assigns Adonis-formatted order numbers (AD-YYYY-MM-NNNNN) to every WooCommerce order. Sequence is per calendar month, zero-padded to 5 digits, and reflects the order's own creation month (so a backdated order keeps its real month).
 * Version:     0.1.0
 * Author:      Adonis
 * Requires PHP: 8.0
 *
 * --------------------------------------------------------------------------
 * Install
 * --------------------------------------------------------------------------
 * Drop this file at: wp-content/mu-plugins/adonis-order-id.php
 *
 * MU-plugins are auto-loaded by WordPress on every request — there is no
 * activation step. To remove, delete the file. The plugin appears under
 * "Must-Use" in the wp-admin Plugins screen.
 *
 * --------------------------------------------------------------------------
 * What it stores
 * --------------------------------------------------------------------------
 *   • Per-order meta:  _adonis_order_number   (e.g. "AD-2026-05-00001")
 *   • Per-month option: adonis_order_seq_YYYYMM  (autoload = no)
 *
 * Old monthly counters stay in wp_options forever; they're tiny and not
 * autoloaded, so cleanup is unnecessary.
 *
 * --------------------------------------------------------------------------
 * Concurrency
 * --------------------------------------------------------------------------
 * The per-month counter is incremented via INSERT ... ON DUPLICATE KEY
 * UPDATE on wp_options — a single InnoDB statement, atomic at the row
 * level. Two concurrent order creations cannot lose an increment.
 *
 * The read-back (SELECT option_value) is a separate statement. In the
 * narrow window between A's increment and A's read, B can also increment;
 * both then read the same post-increment value. If that ever matters,
 * swap to LAST_INSERT_ID(option_value + 1) + SELECT LAST_INSERT_ID() for
 * a strictly per-connection atomic claim.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

const ADONIS_ORDER_META_KEY   = '_adonis_order_number';
const ADONIS_ORDER_SEQ_PREFIX = 'adonis_order_seq_';

add_action( 'woocommerce_new_order', 'adonis_assign_order_number', 10, 2 );

/**
 * Assign AD-YYYY-MM-NNNNN to the order on creation. Idempotent — never
 * overwrites an existing assignment.
 *
 * @param int           $order_id
 * @param WC_Order|null $order    WC 3+ passes the order; older callers may not.
 */
function adonis_assign_order_number( $order_id, $order = null ) {
	if ( (int) $order_id <= 0 ) { return; }

	if ( ! ( $order instanceof WC_Order ) ) {
		$order = wc_get_order( $order_id );
		if ( ! $order ) { return; }
	}

	$existing = $order->get_meta( ADONIS_ORDER_META_KEY, true );
	if ( is_string( $existing ) && $existing !== '' ) { return; }

	$created   = $order->get_date_created();
	$timestamp = ( $created instanceof WC_DateTime ) ? $created->getTimestamp() : time();

	$year_month = gmdate( 'Ym', $timestamp );
	$year       = gmdate( 'Y',  $timestamp );
	$month      = gmdate( 'm',  $timestamp );

	$option_key = ADONIS_ORDER_SEQ_PREFIX . $year_month;

	global $wpdb;
	// Atomic increment on InnoDB: single statement, no PHP-level race.
	$wpdb->query( $wpdb->prepare(
		"INSERT INTO {$wpdb->options} (option_name, option_value, autoload)
		 VALUES (%s, 1, 'no')
		 ON DUPLICATE KEY UPDATE option_value = option_value + 1",
		$option_key
	) );
	$next = (int) $wpdb->get_var( $wpdb->prepare(
		"SELECT option_value FROM {$wpdb->options} WHERE option_name = %s",
		$option_key
	) );
	// Direct wp_options writes bypass WP's options cache; invalidate so a
	// later get_option() in the same request sees the fresh value.
	wp_cache_delete( $option_key, 'options' );

	$formatted = sprintf( 'AD-%s-%s-%05d', $year, $month, $next );

	$order->update_meta_data( ADONIS_ORDER_META_KEY, $formatted );
	$order->save();
}

add_filter( 'woocommerce_order_number', 'adonis_filter_order_number', 10, 2 );

/**
 * Replace the displayed order number with the formatted ID. Falls back to
 * the WC default for orders created before this plugin was installed (no
 * meta set).
 *
 * @param string   $order_number Default WC order number.
 * @param WC_Order $order
 * @return string
 */
function adonis_filter_order_number( $order_number, $order ) {
	if ( ! ( $order instanceof WC_Order ) ) { return $order_number; }
	$stored = $order->get_meta( ADONIS_ORDER_META_KEY, true );
	return ( is_string( $stored ) && $stored !== '' ) ? $stored : $order_number;
}
