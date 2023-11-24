

<div class="wrap zest-admin-page">

    <?php 
        include_once( 'components/admin-header.php' );
    


    $Admin = new Zest_Points_Admin('plugin', 'plugin');
    $Admin->zest_points_list_table_page();


     include_once( 'components/admin-footer.php' ); ?>
    
</div>

