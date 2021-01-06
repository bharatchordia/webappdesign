<?php
namespace LWS\WOOREWARDS;

// don't call the file directly
if( !defined( 'ABSPATH' ) ) exit();

/** Fake a WC_Coupon to consume points. */
class PointDiscount
{
	const COUPON_ERR_CODE = 57490; // abitrary code
	const CODE_PREFIX = 'wr_points_on_cart';

	static function install()
	{
		$me = new self();
		// simulate coupons
		\add_filter('woocommerce_cart_totals_coupon_label', array($me, 'asLabel'), 10, 2);
		\add_filter('woocommerce_order_item_get_code', array($me, 'asCode'), 10, 2);
		\add_filter('woocommerce_coupon_error', array($me, 'asError'), 10, 3);
		\add_filter('woocommerce_get_shop_coupon_data', array($me, 'asData'), 10, 3);
		// save coupon meta
		\add_action('woocommerce_checkout_create_order_coupon_item', array($me, 'createOrderItem'), 10, 4);
		// release points
		\add_filter('woocommerce_removed_coupon', array($me, 'remove'), 10, 2);
		// Substract points during checkout
		\add_action('woocommerce_checkout_order_processed', array($me, 'pay'), PHP_INT_MAX-9, 3);
		// prevent add discount with enough points
		\add_filter('woocommerce_coupon_is_valid', array($me, 'preventAddDiscount'), 10, 3);
		// set used point in cart
		\add_action('wp_ajax_lws_woorewards_pointsoncart_reserve_amount', array($me, 'reserveAmount'));
	}

	function asLabel($label, $coupon)
	{
		if( $coupon && isset($coupon->wr_discount_data) )
		{
			$label = sprintf(
				_x('Reward from %s', 'Reward label', 'woorewards-lite'),
				$this->getTitle($coupon->wr_discount_data)
			);
		}
		return $label;
	}

	function asError($msg, $err, $coupon)
	{
		if( $msg && self::COUPON_ERR_CODE == $err )
			return $msg;

		if( $coupon && isset($coupon->wr_discount_data) )
		{
			$label = $this->getTitle($coupon->wr_discount_data);
			$label = \wp_kses($label, array());

			$fake = clone $coupon;
			unset($fake->wr_discount_data);
			$fake->set_code($label);
			$msg = $fake->get_coupon_error($err);
		}
		return $msg;
	}

	/** Use hook 'woocommerce_get_shop_coupon_data'
	 *	to create coupon without db. */
	function asData($coupon, $data, $instance)
	{
		if( !\is_string($data) )
			return $coupon;

		$discount = $this->fromCode($data);
		if( !$discount )
			return $coupon;

		$data = \apply_filters('lws_woorewards_pointdiscount_as_coupon_data', array(
			'code'                 => $discount['code'],
			'description'          => $this->getTitle($discount),
			'discount_type'        => 'fixed_cart',
			'amount'               => $discount['value'],
		), $discount, $discount['code']);

		if( $data )
		{
			$instance->wr_discount_data = $discount;
			return $data;
		}
		else
			return $coupon;
	}

	/** Coupon title, default use Pool title. */
	protected function getTitle($discount)
	{
		if( $discount['pool'] )
			return \apply_filters('lws_woorewards_pointdiscount_title', $discount['pool']->getOption('display_title'), $discount);
		else
			return $discount['pool_name'];
	}

	protected function fromCode($code)
	{
		/// point discount flag - pool name
		$refs = \explode('-', $code);
		if( 2 != count($refs) )
			return false;

		if( self::CODE_PREFIX != $refs[0] )
			return false;

		$userId = \get_current_user_id();
		if( !$userId )
			return false;

		$pool = \apply_filters('lws_woorewards_get_pools_by_args', false, array('system' => $refs[1]), $userId)->last();
		if( !$pool )
			return false;
		if( !$pool->getOption('direct_reward_mode') )
			return false;

		$stackId = $pool->getStackId();
		$points = \intval(\get_user_meta($userId, 'lws_wr_points_on_cart_'.$stackId, true));
		if( $points <= 0 )
			return false;

		$points = \min($points, $pool->getPoints($userId));
		if( $points <= 0 )
			return false;

		$rate = $pool->getOption('direct_reward_point_rate');
		if( $rate == 0.0 )
			return false;

		$discount = array(
			'code'      => $code,
			'pool'      => $pool,
			'pool_name' => $pool->getName(),
			'stack_id'  => $stackId,
			'user_id'   => $userId,
			'points'    => $points,
			'rate'      => $rate,
			'value'     => (float)$points * $rate,
		);

		// clamp to cart total equivalent
		if( \WC()->cart )
		{
			$total = \WC()->cart->get_subtotal();
			if( 'yes' === get_option('woocommerce_prices_include_tax') )//if( \WC()->cart->display_prices_including_tax() )
				$total += \WC()->cart->get_subtotal_tax();
			$max = \ceil((float)$total / $rate);
			if( $max < $points )
				$discount['value'] = $max * $rate;
		}
		return \apply_filters('lws_woorewards_pointdiscount_from_code', $discount, $code);
	}

	/** Hook 'woocommerce_coupon_is_valid' to check if user can use that discount.
	 *	Prevent using from different pool using the same stack. */
	function preventAddDiscount($valid, $coupon, $wcDiscounts)
	{
		if( !$valid )
			return $valid;
		// does it matter for us
		if( !($coupon && isset($coupon->wr_discount_data)) )
			return $valid;
		// get cart
		$cart = false;
		if( $wcDiscounts && \is_a($wcDiscounts->get_object(), '\WC_Cart') )
			$cart = $wcDiscounts->get_object();
		if( !$cart && \WC()->cart )
			$cart =& \WC()->cart;
		if( !$cart )
			return $valid;

		if( $cart->has_discount($coupon->wr_discount_data['code']) )
			return $valid;

		foreach( $cart->get_coupons() as $applied )
		{
			if( isset($applied->wr_discount_data) && $applied->wr_discount_data['stack_id'] == $coupon->wr_discount_data['stack_id'] )
			{
				$msg = sprintf(
					__('%2$s Conflict. Reward from %1$s already uses the same reserve of Point.', 'woorewards-lite'),
					$this->getTitle($applied->wr_discount_data),
					$this->getTitle($coupon->wr_discount_data)
				);
				throw new \Exception($msg, self::COUPON_ERR_CODE); // message thrown not used by WC :'('
				$valid = false;
			}
		}
		return $valid;
	}

	/** For each discount in order, pay the points cost */
	function pay($orderId, $postedData, $order)
	{
		if( !$order )
			return;

		// get discounts in coupons list
		$data = \array_filter(\array_map(array($this, 'fromOrderItem'), $order->get_coupons()));
		if( !$data )
			return;

		// group by stack
		$stacks = array();
		foreach( $data as $discount )
		{
			$pool = $discount['pool'];
			if( !$pool )
				throw new \Exception(sprintf(__('The Reward "%s" in your cart is unknown.', 'woorewards-lite'), $discount['pool_name']));
			if( !$pool->getOption('direct_reward_mode') )
				throw new \Exception(sprintf(__('"%s" does not support this kind of reward anymore.', 'woorewards-lite'), $pool->getOption('display_title')));

			$stack = $pool->getStackId();
			if( isset($stacks[$stack]) )
				$stacks[$stack]['pools'][] = array('pool' => $pool, 'needs' => 0);
			else
				$stacks[$stack] = array('stack' => $stack, 'needs' => 0, 'pools' => array(array('pool' => $pool, 'needs' => 0)));
		}
		if( !$stacks )
			return;

		$userId = $order->get_customer_id('edit');
		if( !$userId )
		{
			throw new \Exception(sprintf(
				__('At least one coupon in your cart requires %s. You must log in to continue.', 'woorewards-lite'),
				\LWS_WooRewards::getPointSymbol(0, reset($stacks)['pools'][0]['pool']->getName())
			));
		}

		// compute point usage on each stack
		foreach( $stacks as $stackId => &$need )
		{
			// Several pool sharing the same stack is prevented, but managed in case
			foreach( $need['pools'] as &$pool )
			{
				$pool['needs'] = \intval(\get_user_meta($userId, 'lws_wr_points_on_cart_'.$stackId, true));
				$need['needs'] += $pool['needs'];
				$need['max'] = $pool['pool']->getPoints($userId);
			}
		}

		// check user get enough to pay
		foreach( $stacks as $stackId => &$need )
		{
			if( $need['needs'] > $need['max'] )
			{
				throw new \Exception(sprintf(
					__('You do not have enough %1$s to purchase the reward %2$s.', 'woorewards-lite'),
					\LWS_WooRewards::getPointSymbol(0, $need['pools'][0]['pool']->getName()),
					$need['pools'][0]['pool']->getOption('display_title')
				));
			}
		}

		// keep total cost with order
		\add_post_meta($orderId, 'lws_woorewards_pointdiscount_costs', \array_column($stacks, 'needs', 'stack'));

		foreach( $stacks as $stackId => &$need )
		{
			foreach( $need['pools'] as &$pool )
			{
				$title = $pool['pool']->getOption('display_title');

				// pay points
				$reason = \LWS\WOOREWARDS\Core\Trace::byReason(
					array('Reward from %1$s on Order #%2$s', $title, $order->get_order_number()),
					LWS_WOOREWARDS_DOMAIN
				)->setOrigin(self::CODE_PREFIX.$pool['pool']->getName())->setOrder($orderId);

				$pool['pool']->usePoints($userId, $pool['needs'], $reason);
				\update_user_meta($userId, 'lws_wr_points_on_cart_'.$stackId, 0);

				// keep note on order
				$order->add_order_note(sprintf(
					_x('Use <i>%1$s</i> from <i>%2$s</i> to get a discount on this order', 'order note', 'woorewards-lite'),
					\LWS_WooRewards::formatPointsWithSymbol($pool['needs'], $pool['pool']->getName()),
					$title
				));
			}
		}
	}

	/* If a coupon is removed and it's a one of use, clear assigned points */
	function remove($code)
	{
		/// point discount flag - pool name
		$refs = \explode('-', $code);
		if( 2 != count($refs) )
			return;

		if( self::CODE_PREFIX != $refs[0] )
			return;

		$userId = \get_current_user_id();
		if( !$userId )
			return;

		$pool = \apply_filters('lws_woorewards_get_pools_by_args', false, array('system' => $refs[1]), $userId)->last();
		if( $pool )
		{
			$stackId = $pool->getStackId();
			\update_user_meta($userId, 'lws_wr_points_on_cart_'.$stackId, 0);
		}
	}

	/** WC set code twice, the second with the filtered code value.
	 * So, we must to look deeper for original code since WooCommerce
	 * never use its own $context argument. */
	function fromOrderItem($item)
	{
		$data = $item->get_meta('wr_discount_data', true, 'edit');
		if( $data )
		{
			if( !$data['pool'] )
			{
				$data['pool'] = \apply_filters('lws_woorewards_get_pools_by_args', false, array('system' => $data['pool_name'], 'force' => true), false)->last();
				if( $data['pool'] )
					$item->update_meta_data('wr_discount_data', $data);
			}
		}
		return $data;
	}

	function createOrderItem($item, $code, $coupon, $order)
	{
		if( isset($coupon->wr_discount_data) )
		{
			$data = $coupon->wr_discount_data;
			$data['pool'] = false; // no need to save such Object, we have pool_name to reload it.
			$item->add_meta_data('wr_discount_data', $coupon->wr_discount_data);
		}
	}

	/** Get a label (instead of raw code) from Order Item */
	function asCode($code, $item)
	{
		if( $item && \is_a($item, '\WC_Order_Item_Coupon') )
		{
			$data = $item->get_meta('wr_discount_data', true, 'edit');
			if( $data )
			{
				if( !$data['pool'] )
				{
					$data['pool'] = \apply_filters('lws_woorewards_get_pools_by_args', false, array('system' => $data['pool_name'], 'force' => true), false)->last();
					if( $data['pool'] )
						$item->update_meta_data('wr_discount_data', $data);
				}
				return $this->getTitle($data);
			}
		}
		return $code;
	}

	private function poedit()
	{
		__('Reward from %1$s on Order #%2$s', 'woorewards-lite');
	}

	/** Ajax
	 */
	function reserveAmount()
	{
		if( !(isset($_REQUEST['nonce']) && \wp_verify_nonce($_REQUEST['nonce'], 'lws_woorewards_reserve_pointsoncart')) )
			\wp_send_json(array('error' => __("Action control failed. Try to refresh the page.", 'woorewards-lite')));

		$userId = \get_current_user_id();
		if( !$userId )
			\wp_send_json(array('error' => __("A connected user is required.", 'woorewards-lite')));

		if( !(isset($_REQUEST['system']) && ($pool = \sanitize_key($_REQUEST['system']))) )
			\wp_send_json(array('error' => __('Missing destination or bad format.', 'woorewards-lite')));

		if( !\WC()->cart )
			\wp_send_json(array('error' => __('Cannot load the Cart. Operation abort.', 'woorewards-lite')));

		$pool = \apply_filters('lws_woorewards_get_pools_by_args', false, array('system' => $pool), $userId)->last();
		if( !$pool )
			\wp_send_json(array('error' => __('Loyalty System missing or access not granted.', 'woorewards-lite')));
		if( !$pool->getOption('direct_reward_mode') )
			\wp_send_json(array('error' => __('Loyalty System does not accept this kind of reward.', 'woorewards-lite')));

		$stackId = $pool->getStackId();
		$max = $pool->getPoints($userId);

		$points = \intval(isset($_REQUEST['amount']) ? $_REQUEST['amount'] : 0);
		$points = \max(0, \min($points, $max));
		\update_user_meta($userId, 'lws_wr_points_on_cart_'.$stackId, $points);

		$code = self::CODE_PREFIX . '-' . $pool->getName();
		if( $points )
		{
			// add coupon if not exists
			if( !\WC()->cart->has_discount($code) )
				\WC()->cart->apply_coupon($code);
		}
		else
		{
			// silently remove coupon if exists
			if( \WC()->cart->has_discount($code) )
				\WC()->cart->remove_coupon($code);
		}

		$formated = \LWS_WooRewards::formatPoints($points, $pool->getName());
		\wp_send_json(array(
			'contribution' => $points,
			'max' => $max,
			'formated' => $formated,
			'dispMax' => \LWS_WooRewards::formatPoints($max, $pool->getName()),
			'success' => sprintf(__('Use %1$s from %2$s', 'woorewards-lite'), $formated, $pool->getOption('display_title')),
		));
	}
}
