<?php
/**
 * Plugin Name: Adonis Precision Care Toggle
 * Description: Adds a wp-admin dashboard widget that lets a manage_woocommerce user toggle whether Adonis is accepting new Precision Care intakes. The toggle is wired to the WC stock status of the product with SKU "ADX-PRC-01" — instock = accepting, outofstock = not accepting. Front-of-site stock-status logic in WC + theme handles the user-facing rendering.
 * Version:     0.1.0
 * Author:      Adonis
 * Requires PHP: 8.0
 *
 * Install: drop at wp-content/mu-plugins/adonis-precision-toggle.php.
 * MU-plugins are auto-loaded; no activation step.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

const ADONIS_PRECISION_SKU    = 'ADX-PRC-01';
const ADONIS_PRECISION_NONCE  = 'adonis_precision_toggle';
const ADONIS_PRECISION_ACTION = 'adonis_precision_toggle_save';

add_action( 'wp_dashboard_setup', 'adonis_precision_register_dashboard_widget' );

function adonis_precision_register_dashboard_widget() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		return;
	}
	wp_add_dashboard_widget(
		'adonis_precision_toggle_widget',
		'Precision Care availability',
		'adonis_precision_render_dashboard_widget'
	);
}

function adonis_precision_render_dashboard_widget() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		return;
	}

	if ( ! function_exists( 'wc_get_product_id_by_sku' ) ) {
		echo '<p style="margin:0"><strong>WooCommerce not active.</strong> Activate WooCommerce to use this widget.</p>';
		return;
	}

	$product_id = wc_get_product_id_by_sku( ADONIS_PRECISION_SKU );
	if ( ! $product_id ) {
		echo '<p style="margin:0"><strong>Product not found</strong> — create the Precision Care product with SKU '
			. esc_html( ADONIS_PRECISION_SKU )
			. ' (Products &rarr; Add New). The widget will take over from there.</p>';
		return;
	}

	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		echo '<p style="margin:0"><strong>Product lookup failed.</strong> Refresh and retry.</p>';
		return;
	}

	$current = $product->get_stock_status() === 'instock' ? 'yes' : 'no';

	// Inline notice from a prior submit redirect.
	if ( ! empty( $_GET['adonis_precision_status'] ) ) {
		$status = sanitize_key( wp_unslash( $_GET['adonis_precision_status'] ) );
		if ( $status === 'updated' ) {
			$saved = isset( $_GET['adonis_precision_value'] )
				? sanitize_key( wp_unslash( $_GET['adonis_precision_value'] ) )
				: '';
			$label = $saved === 'yes' ? 'Accepting new visits.' : 'Not accepting new visits.';
			echo '<div class="notice notice-success inline" style="margin:0 0 8px"><p style="margin:.4em 0">Saved. ' . esc_html( $label ) . '</p></div>';
		} elseif ( $status === 'failed' ) {
			echo '<div class="notice notice-error inline" style="margin:0 0 8px"><p style="margin:.4em 0">Save failed. Please try again.</p></div>';
		}
	}
	?>
	<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin:0">
		<?php wp_nonce_field( ADONIS_PRECISION_NONCE ); ?>
		<input type="hidden" name="action" value="<?php echo esc_attr( ADONIS_PRECISION_ACTION ); ?>" />

		<fieldset style="border:0;padding:0;margin:0 0 12px">
			<legend style="padding:0;margin:0 0 8px;font-weight:600">Accepting new Precision visits — yes/no</legend>
			<label style="margin-right:16px">
				<input type="radio" name="adonis_precision_value" value="yes" <?php checked( $current, 'yes' ); ?> />
				Yes
			</label>
			<label>
				<input type="radio" name="adonis_precision_value" value="no" <?php checked( $current, 'no' ); ?> />
				No
			</label>
		</fieldset>

		<p style="margin:0">
			<button type="submit" class="button button-primary">Save</button>
		</p>
	</form>
	<?php
}

add_action( 'admin_post_' . ADONIS_PRECISION_ACTION, 'adonis_precision_handle_toggle_save' );

function adonis_precision_handle_toggle_save() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		wp_die( 'You do not have permission to update this setting.', 'Forbidden', array( 'response' => 403 ) );
	}
	check_admin_referer( ADONIS_PRECISION_NONCE );

	$value = isset( $_POST['adonis_precision_value'] )
		? sanitize_key( wp_unslash( $_POST['adonis_precision_value'] ) )
		: '';
	if ( $value !== 'yes' && $value !== 'no' ) {
		adonis_precision_redirect_back( 'failed' );
	}

	if ( ! function_exists( 'wc_get_product_id_by_sku' ) ) {
		adonis_precision_redirect_back( 'failed' );
	}

	$product_id = wc_get_product_id_by_sku( ADONIS_PRECISION_SKU );
	if ( ! $product_id ) {
		adonis_precision_redirect_back( 'failed' );
	}

	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		adonis_precision_redirect_back( 'failed' );
	}

	$product->set_stock_status( $value === 'yes' ? 'instock' : 'outofstock' );
	$product->save();

	adonis_precision_redirect_back( 'updated', $value );
}

function adonis_precision_redirect_back( $status, $value = '' ) {
	$args = array( 'adonis_precision_status' => $status );
	if ( $value !== '' ) {
		$args['adonis_precision_value'] = $value;
	}
	wp_safe_redirect( add_query_arg( $args, admin_url( 'index.php' ) ) );
	exit;
}

/**
 * --------------------------------------------------------------------------
 * Creating the Precision Care product (manual, one-time)
 * --------------------------------------------------------------------------
 * In wp-admin → Products → Add New, create a regular WooCommerce product
 * with SKU ADX-PRC-01 (matching ADONIS_PRECISION_SKU above). Title
 * "Precision Care", price as configured, stock-status field set to either
 * In stock or Out of stock — the dashboard widget takes over from there.
 * No special product type is required; the widget reads and writes only
 * stock_status, so Simple, Variable, or Subscription product types all
 * work. Until that product exists, the widget renders a friendly "create
 * the product" message instead of a form, so deploying this file before
 * the product is created is safe.
 */
