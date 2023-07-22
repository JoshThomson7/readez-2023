<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * WooCommerce Orders Template
 *
 * @package modules/woocommerce
 * @version 1.0
*/

global $wp_query, $paged;

$customer_id = get_current_user_id();

$current_page = isset($wp_query->query_vars['orders']) ? str_replace('page/', '', $wp_query->query_vars['orders']) : 0;
$customer_orders = get_user_meta($customer_id, get_current_user_id(), 'wc_customer_orders', true);

// DEBUG: 
// print_r($customer_orders);

if(empty($customer_orders)) {
    $customer_orders = wc_orders_to_user_meta($customer_id, true);
}

if ( !empty($customer_orders) && is_array($customer_orders) ) :

    $order_args = array(
        'post_type' => 'shop_order',
        'post_status' => array_keys( wc_get_order_statuses() ),
        'posts_per_page' => 50,
        'paged' => $current_page,
        'post__in' => $customer_orders,
        'orderby' => 'date',
        'order' => 'desc'
    );

    $orders_query = new WP_Query($order_args);
?>

	<table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
		<thead>
			<tr>
				<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
					<th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<tbody>
			<?php
                //foreach (array_slice(array_reverse($customer_orders), $page*$orders_per_page, $orders_per_page) as $customer_order):
                while($orders_query->have_posts()):
                    $orders_query->the_post();

                    $order_id = get_the_ID();
					
					// Bail early if order does not exist
                    //if(get_post_status( $customer_order ) === false) { continue; }
                    if(get_post_status($order_id) === false) { continue; }

                    //$order      = wc_get_order( $customer_order );
                    $order      = wc_get_order($order_id);
    				$item_count = $order->get_item_count();
			?>
				<tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr( $order->get_status() ); ?> order">
					<?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
						<td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
							<?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
								<?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

							<?php elseif ( 'order-number' === $column_id ) : ?>
								<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
									<?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); ?>
								</a>

							<?php elseif ( 'order-date' === $column_id ) : ?>
								<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

							<?php elseif ( 'order-status' === $column_id ) : ?>
								<?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

							<?php elseif ( 'order-total' === $column_id ) : ?>
								<?php
								/* translators: 1: formatted order total 2: total order items */
								printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count );
								?>

							<?php elseif ( 'order-actions' === $column_id ) : ?>
								<?php
								$actions = wc_get_account_orders_actions( $order );

								if ( ! empty( $actions ) ) {
									foreach ( $actions as $key => $action ) {
										echo '<a href="' . esc_url( $action['url'] ) . '" class="woocommerce-button button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
									}
								}
								?>
							<?php endif; ?>
						</td>
					<?php endforeach; ?>
				</tr>
            <?php endwhile; wp_reset_postdata(); ?>
		</tbody>
	</table>

    <?php pagination($orders_query->max_num_pages, 4, null, $current_page); ?>

<?php else : ?>
	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<a class="woocommerce-Button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php _e( 'Go shop', 'woocommerce' ) ?>
		</a>
		<?php _e( 'No order has been made yet.', 'woocommerce' ); ?>
	</div>
<?php endif; ?>
