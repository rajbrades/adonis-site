<?php
/**
 * Plugin Name: Adonis FL-only Checkout Gate
 * Description: Restricts checkout to billing_state == 'FL' (US). Two layers — submit-time validation rejects non-FL submissions with the brand-locked waitlist copy; a pre-render geolocation guard redirects non-FL US visitors away from /checkout to /waitlist. Logged-in users with a saved FL billing address bypass the geo guard so geo-IP false positives don't lock travelers out.
 * Version:     0.1.0
 * Author:      Adonis
 * Requires PHP: 8.0
 *
 * --------------------------------------------------------------------------
 * Install
 * --------------------------------------------------------------------------
 * Drop this file at: wp-content/mu-plugins/adonis-fl-gate.php
 * MU-plugins are auto-loaded by WordPress; no activation step.
 *
 * --------------------------------------------------------------------------
 * Behavior
 * --------------------------------------------------------------------------
 *   Submit-time (woocommerce_after_checkout_validation):
 *     Rejects any submission where billing_country != 'US' OR
 *     billing_state != 'FL'. Error message uses the brand-locked
 *     waitlist copy with "join the list" linked to /waitlist.
 *
 *   Pre-render (template_redirect on /checkout, NOT order-pay/received):
 *     Calls WC_Geolocation::geolocate_ip(). If the visitor geolocates to
 *     a US state other than FL, redirects to /waitlist?from=checkout
 *     before the checkout form renders. International / unknown geo
 *     passes through — submit-time validation will catch it.
 *
 *   Bypass: logged-in users with saved billing_state == 'FL' (and a US
 *     or empty billing_country) skip the geolocation redirect — they've
 *     already self-identified as FL residents and may be traveling.
 *
 * No third-party APIs. WC_Geolocation is built in to WooCommerce.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

const ADONIS_FL_WAITLIST_PATH = '/waitlist';

add_action( 'woocommerce_after_checkout_validation', 'adonis_fl_gate_validate_submit', 10, 2 );

/**
 * Reject any non-FL US billing address (and any non-US address) at submit
 * time. Message is the brand-locked waitlist copy — em-dash (U+2014) is
 * intentional, do not paraphrase.
 *
 * @param array    $data
 * @param WP_Error $errors
 */
function adonis_fl_gate_validate_submit( $data, $errors ) {
	$country = isset( $data['billing_country'] ) ? strtoupper( (string) $data['billing_country'] ) : 'US';
	$state   = isset( $data['billing_state'] )   ? strtoupper( (string) $data['billing_state'] )   : '';

	if ( $country === 'US' && $state === 'FL' ) {
		return;
	}

	$waitlist = esc_url( home_url( ADONIS_FL_WAITLIST_PATH ) );
	$message  = sprintf(
		'We\'ll be in your state soon — <a href="%s">join the list</a>',
		$waitlist
	);

	$errors->add( 'adonis_fl_only', $message );
}

add_action( 'template_redirect', 'adonis_fl_gate_geo_redirect' );

/**
 * Redirect non-FL US visitors away from the checkout page before it
 * renders. Order-pay and order-received endpoints are not gated — those
 * imply an already-validated FL order.
 */
function adonis_fl_gate_geo_redirect() {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return;
	}

	if ( function_exists( 'is_wc_endpoint_url' )
		&& ( is_wc_endpoint_url( 'order-pay' ) || is_wc_endpoint_url( 'order-received' ) ) ) {
		return;
	}

	if ( is_user_logged_in() ) {
		$user_id       = get_current_user_id();
		$saved_state   = strtoupper( (string) get_user_meta( $user_id, 'billing_state',   true ) );
		$saved_country = strtoupper( (string) get_user_meta( $user_id, 'billing_country', true ) );
		if ( $saved_state === 'FL' && ( $saved_country === '' || $saved_country === 'US' ) ) {
			return;
		}
	}

	if ( ! class_exists( 'WC_Geolocation' ) ) {
		return;
	}

	$geo     = WC_Geolocation::geolocate_ip();
	$country = isset( $geo['country'] ) ? strtoupper( (string) $geo['country'] ) : '';
	$state   = isset( $geo['state'] )   ? strtoupper( (string) $geo['state'] )   : '';

	if ( $country !== 'US' || $state === '' ) {
		return;
	}

	// Defense in depth: only act on a known US state code.
	if ( function_exists( 'WC' ) && WC() && method_exists( WC()->countries, 'get_states' ) ) {
		$us_states = WC()->countries->get_states( 'US' );
		if ( ! is_array( $us_states ) || ! isset( $us_states[ $state ] ) ) {
			return;
		}
	}

	if ( $state === 'FL' ) {
		return;
	}

	$target = add_query_arg( 'from', 'checkout', home_url( ADONIS_FL_WAITLIST_PATH ) );
	wp_safe_redirect( $target, 302 );
	exit;
}

/**
 * --------------------------------------------------------------------------
 * /waitlist page setup (manual, one-time)
 * --------------------------------------------------------------------------
 * Create a regular WordPress page titled "Waitlist" with the slug
 * "waitlist" (resulting in /waitlist as the permalink). Drop a Mailchimp
 * or ConvertKit form block on the page to capture email + ZIP — that's
 * the entire requirement; no custom plugin is needed. Both providers ship
 * Gutenberg blocks that work in a stock block theme. Until that page
 * exists, both the inline error link and the pre-render redirect target
 * resolve to a 404 — acceptable during scaffold; ship the waitlist page
 * before the FL gate goes live.
 */
