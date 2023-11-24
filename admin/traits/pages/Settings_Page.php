<?php



trait Settings_Page {


    public function save_zest_settings() {

		// Check the nonce.
		check_admin_referer( 'zest-points-settings-nonce' );

		// Save the settings.
		woocommerce_update_options( $this->get_zest_settings() );

		// Go back to the settings page.
		wp_redirect( admin_url( 'admin.php?page=zest-settings' ) );

		exit;
	}

    public function render_zest_setting_fields() {
		woocommerce_admin_fields( $this->get_zest_settings() );

	}

    public static function get_zest_settings() {

        // OPTIONS PAGE....
          // Point conversion rate - earned. for each point get 1p for example.
          // Redmeption conversion rate - spent. how much the points redeem for (it can be 1:1 with above or different) 
          // Allow partial redemption?
          // Min/Max points allowed to spend. 
          // Min/Max order value required.
          // Based on Pre or post tax price
          // Points expire after?


		$settings = array(

			array(
				'title' => __( 'Zest Points Settings', 'zest-points' ),
				'type'  => 'title',
				'id'    => 'zest_points_points_settings_start'
			),
            	// earn points conversion.
			array(
				'title'    => __( 'Earn Points Conversion Rate', 'zest-points' ),
				'desc_tip' => __( 'Set the number of points awarded based on the product price.', 'zest-points' ),
				'id'       => 'zest_points_earn_points_ratio',
				'default'  => '1:1',
				'type'     => 'conversion_ratio'
			),

            // earn points conversion.
			array(
				'title'    => __( 'Earn Points Rounding Mode', 'zest-points' ),
				'desc_tip' => __( 'Set how points should be rounded.', 'zest-points' ),
				'id'       => 'zest_points_earn_points_rounding',
				'default'  => 'round',
				'options'  => array(
					'round' => 'Round to nearest integer',
					'floor' => 'Always round down',
					'ceil'  => 'Always round up',
				),
				'type'     => 'select'
			),

            	// redeem points conversion.
			array(
				'title'    => __( 'Redemption Conversion Rate', 'zest-points' ),
				'desc_tip' => __( 'Set the value of points redeemed for a discount.', 'zest-points' ),
				'id'       => 'zest_points_redeem_points_ratio',
				'default'  => '100:1',
				'type'     => 'conversion_ratio'
			),

			// redeem points conversion.
			array(
				'title'    => __( 'Partial Redemption', 'zest-points' ),
				'desc'     => __( 'Enable partial redemption', 'zest-points' ),
				'desc_tip' => __( 'Lets users enter how many points they wish to redeem during cart/checkout.', 'zest-points' ),
				'id'       => 'zest_points_partial_redemption_enabled',
				'default'  => 'no',
				'type'     => 'checkbox'
			),

			// Minimum points discount.
			array(
				'title'    => __( 'Minimum Points Discount', 'zest-points' ),
				'desc_tip' => __( 'Set the minimum amount a user\'s points must add up to in order to redeem points. Use a fixed monetary amount or leave blank to disable.', 'zest-points' ),
				'id'       => 'zest_points_cart_min_discount',
				'default'  => '',
				'type'     => 'text',
			),

			// maximum points discount available.
			array(
				'title'    => __( 'Maximum Points Discount', 'zest-points' ),
				'desc_tip' => __( 'Set the maximum product discount allowed for the cart when redeeming points. Use either a fixed monetary amount or a percentage based on the product price. Leave blank to disable.', 'zest-points' ),
				'id'       => 'zest_points_cart_max_discount',
				'default'  => '',
				'type'     => 'text',
			),

			// maximum points discount available.
			array(
				'title'    => __( 'Maximum Product Points Discount', 'zest-points' ),
				'desc_tip' => __( 'Set the maximum product discount allowed when redeeming points per-product. Use either a fixed monetary amount or a percentage based on the product price. Leave blank to disable. This can be overridden at the category and product level.', 'zest-points' ),
				'id'       => 'zest_points_max_discount',
				'default'  => '',
				'type'     => 'text',
			),

			// Tax settings.
			array(
				'title'    => __( 'Tax Setting', 'zest-points' ),
				'desc_tip' => __( 'Whether or not points should apply to prices inclusive of tax.', 'zest-points' ),
				'id'       => 'zest_points_points_tax_application',
				'default'  => wc_prices_include_tax() ? 'inclusive' : 'exclusive',
				'options'  => array(
					'inclusive' => 'Apply points to price inclusive of taxes.',
					'exclusive' => 'Apply points to price exclusive of taxes.',
				),
				'type'     => 'select',
			),

			// points label.
			array(
				'title'    => __( 'Points Label', 'zest-points' ),
				'desc_tip' => __( 'The label used to refer to points on the frontend, singular and plural.', 'zest-points' ),
				'id'       => 'zest_points_points_label',
				'default'  => sprintf( '%s:%s', __( 'Point', 'zest-points' ), __( 'Points', 'zest-points' ) ),
				'type'     => 'singular_plural',
			),

			// Expire Points.
			array(
				'title'    => __( 'Points Expire After', 'zest-points' ),
				'desc_tip' => __( 'Set the period after which points expire once granted to a user', 'zest-points' ),
				'type'     => 'points_expiry',
				'id'       => 'zest_points_expiry',
			),

			array( 'type' => 'sectionend', 'id' => 'zest_points_points_settings_end' ),

	

		);


		return apply_filters( 'zest_points_rewards_settings', $settings );
	}


    public function render_conversion_ratio_field( $field ) {
		if ( isset( $field['title'] ) && isset( $field['id'] ) ) :

			$ratio = get_option( $field['id'], $field['default'] );

			list( $points, $monetary_value ) = explode( ':', $ratio );

			$monetary_value = str_replace( '.', wc_get_price_decimal_separator(), $monetary_value );

			?>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for=""><?php echo wp_kses_post( $field['title'] ); ?></label>
						<!-- <img class="help_tip" data-tip="<?php echo wc_sanitize_tooltip( $field['desc_tip'] ); ?>" src="<?php echo esc_url( WC()->plugin_url() . '/assets/images/help.png' ); ?>" height="16" width="16" /> -->
					</th>
					<td class="forminp forminp-text">
						<fieldset>
							<input name="<?php echo esc_attr( $field['id'] . '_points' ); ?>" id="<?php echo esc_attr( $field['id'] . '_points' ); ?>" type="number" style="max-width: 70px;" value="<?php echo esc_attr( $points ); ?>" min="0" step="0.01" />&nbsp;<?php esc_html_e( 'Points', 'zest_points' ); ?>
							<span>&nbsp;&#61;&nbsp;</span>&nbsp;<?php echo get_woocommerce_currency_symbol(); ?>
							<input class="wc_input_price" name="<?php echo esc_attr( $field['id'] . '_monetary_value' ); ?>" id="<?php echo esc_attr( $field['id'] . '_monetary_value' ); ?>" type="number" style="max-width: 70px;" value="<?php echo esc_attr( $monetary_value ); ?>" min="0" step="0.01" />
						</fieldset>
					</td>
				</tr>
			<?php

		endif;
	}

    /**
	 * Render the 'Points Expry' section
	 *
	 * @since 1.4.2
	 * @param array $field associative array of field parameters
	 */
	public function render_points_expiry( $field ) {

		if ( isset( $field['title'] ) && isset( $field['id'] ) ) :	

			$expiry = get_option( $field['id'] );
			
			if ( ! $expiry ) {
				$number = '';
				$period = '';
			}
			else {
				list( $number, $period ) = explode( ':', $expiry );
			}
			
			$periods = array(
				'DAY'   => 'Day(s)',
				'WEEK'  => 'Week(s)',
				'MONTH' => 'Month(s)',
				'YEAR'  => 'Year(s)'
			);
			
			$expire_since = get_option( 'zest_points_expire_points_since', '' );
			?>
			<tr valign="top">
				<th scope="row" class="titledesc">
					<label for="expire_points"><?php echo wp_kses_post( $field['title'] ); ?></label>
					<!-- <img class="help_tip" data-tip="<?php echo wc_sanitize_tooltip( $field['desc_tip'] ); ?>" src="<?php echo esc_url( WC()->plugin_url() . '/assets/images/help.png' ); ?>" height="16" width="16" /> -->
				</th>
				<td class="forminp forminp-text" style="width: 50%; float: left;">
					<fieldset id="expire_points">
						<select name="<?php echo esc_attr( $field[ 'id' ] . '_number' ); ?>" id="<?php echo esc_attr( $field[ 'id' ] ); ?>_number">
							<option value=""></option>
							<?php
								for ( $num = 1; $num < 100; $num++ ) :
									$selected = '';
									if ( $num == $number ) {
										$selected = ' selected="selected" ';
									}
							?>
								<option value="<?php echo esc_attr( $num ); ?>" <?php echo $selected; ?>><?php echo $num; ?></option> 
							<?php endfor; ?>
						</select>
						<select name="<?php echo esc_attr( $field[ 'id' ] . '_period' ); ?>" id="<?php echo esc_attr( $field[ 'id' ] ); ?>_period">
							<option value=""></option>
							<?php
								foreach ( $periods as $period_id => $period_text ) :
									$selected = '';
									if ( $period_id == $period ) {
										$selected = ' selected="selected" ';
									}
							?>
								<option value="<?php echo esc_attr( $period_id ); ?>" <?php echo $selected; ?>><?php _e( $period_text, 'zest_points' ); ?></option>
							<?php endforeach; ?>
						</select>

						<fieldset>
							<p class="form-field expire-points-since">
								<label for="expire_points_since"><?php printf( __( '%sOnly apply to points earned since%s - %sOptional%s', 'zest_points' ), '<strong>', '</strong>', '<em>', '</em>' ); ?></label>
								<input type="text" class="date-picker" style="width: 200px;" name="expire_points_since" id="expire_points_since" value="<?php echo esc_attr( $expire_since ); ?>" placeholder="<?php echo _x( 'YYYY-MM-DD', 'placeholder', 'zest_points' ); ?>" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" />
								<p class="description"><?php _e( 'Leave blank to apply to all points', 'zest_points' ); ?></p>
							</p>
						</fieldset>

					</fieldset>
				</td>
			</tr>
		<?php
		endif;
	}
	



    public function save_conversion_ratio_field( $value, $option, $raw_value ) {

		if ( isset( $_POST[ $option['id'] . '_points' ] ) && ! empty( $_POST[ $option['id'] . '_monetary_value' ] ) )
			$points         = wc_clean( $_POST[ $option['id'] . '_points' ] );
			$monetary_value = wc_clean( $_POST[ $option['id'] . '_monetary_value' ] );
			$monetary_value = str_replace( wc_get_price_decimal_separator(), '.', $monetary_value );

			return $points . ':' . $monetary_value;
	}



	public function save_points_expiry( $value, $option, $raw_value ) {

		if ( isset( $_POST[ $option['id'] . '_number' ] ) && isset( $_POST[ $option['id'] . '_period' ] ) ) {
			if ( is_numeric( $_POST[ $option['id'] . '_number' ] ) && in_array( $_POST[ $option['id'] . '_period' ], array( 'DAY', 'WEEK', 'MONTH', 'YEAR' ) ) ) {

				// Check if expire points since has been set
				if ( isset( $_POST[ 'expire_points_since' ] ) && DateTime::createFromFormat( 'Y-m-d', $_POST[ 'expire_points_since' ] ) ) {
					update_option( 'zest_points_expire_points_since', wc_clean( $_POST[ 'expire_points_since' ] ) );
				}
				
				return wc_clean( $_POST[ $option['id'] . '_number' ] ) . ':' . wc_clean( $_POST[ $option['id'] . '_period' ] );
			}
			else {
				update_option( 'zest_points_expire_points_since', '' );
				return '';
			}
		}
	}
  
}




