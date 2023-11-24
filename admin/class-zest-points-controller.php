<?php


class Zest_Points_Controller  {

    private $zest_points;

    public function __construct(Zest_Points $zest_points) {
        $this->Zest_Points = $zest_points;
    }


     public function increase_points( $user_id, $points, $event_type, $order_id = null, $data = null ) {

		global $wpdb;
        global $Zest_Points;

		// ensure the user exists
		$user = get_userdata( $user_id );

		if ( false === $user ) return false;

		$points = apply_filters( 'zest_points_increase_points', $points, $user_id, $event_type, $data, $order_id );

		$_data = array(
			'user_id'        => $user_id,
			'points'         => $points,
			'earned_date'           => current_time( 'mysql', 1 ),
            'expiry_date'    => null
		);

		$format = array(
			'%d',
			'%d',
			'%d',
			'%s',
		);

		if ( $order_id ) {
			$_data['order_id'] = $order_id;
			$format[] = '%d';
		}

		// create the new user points record
		$success = $wpdb->insert(
		   $this->Zest_Points->user_points_db_tablename,
			$_data,
			$format
		);

		// failed to insert the user points record
		if ( 1 != $success ) return false;

		// required log parameters
		$args = array(
			'user_id'        => $user_id,
			'points'         => $points,
			'event_type'     => $event_type,
			'user_points_id' => $wpdb->insert_id,
		);

		// optional associated order
		if ( $order_id )
			$args['order_id'] = $order_id;

		// optional associated data
		if ( $data )
			$args['data'] = $data;

		// log the event
	//	WC_Points_Rewards_Points_Log::add_log_entry( $args );

		// update the current points balance user meta
		$points_balance = (int) get_user_meta( $user_id, 'zest_points_balance' );
		update_user_meta( $user_id, 'zest_points_balance', $points_balance + $points );

		//do_action( 'wc_points_rewards_after_increase_points', $user_id, $points, $event_type, $data, $order_id );

		// success
		return true;
	}


}

