

<div class="wrap zest-admin-page">

    <?php 
        include_once( 'components/admin-header.php' );
    ?>

    <form method="post" action="admin-post.php" enctype="multipart/form-data">
		<input type="hidden" name="action" value="zest_points_settings" />
			<?php
				wp_nonce_field( 'zest-points-settings-nonce' );
				$this->render_zest_setting_fields();
			?>
		<input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'woocommerce' ) ?>" />
	</form>

    <?php include_once( 'components/admin-footer.php' ); ?>
    
</div>


<!--
SPEC....
Each user gets assigned an amount of points based on some action
Clients will want to define what amount of points per action
The website tracks the date of the action, the amount of points accrued, the user account, the related order, all from the action that accrued the points
Points must be redeemable in some way, and with some exchange rate. That allows a discount on products
An area to convey to the user, how many points they have, how to redeem
Some functionality that allows users to redeem points, e.g on the cart page
Needs to be independent of the orders table as otherwise you may run into an issue similar to Sumo. 

-->

<!-- 

ACTIONS....
On Signup
On Purchase


LOG PAGE....



USERS POINTS PAGE....


 -->


 <!-- 
TASKS....
M
Get point assigned to tables



 -->