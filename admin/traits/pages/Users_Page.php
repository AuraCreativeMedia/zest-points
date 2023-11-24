<?php


trait Users_Page {
    	/* @var \WC_Points_Rewards_Manage_Points_List_Table the manage points list table object */
	//private $manage_points_list_table;


    public function zest_points_list_table_page()
    {
         $exampleListTable = new Zest_Manage_Points_List_Table();
         $exampleListTable->prepare_items();
  
         

        // $zestClassInstance = new Zest_Points();
        // $zestController = new Zest_Points_Controller($zestClassInstance);
        // $zestController->increase_points();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>Example List Table Page</h2>
                <?php  $exampleListTable->display(); ?>
            </div>
        <?php

    }
}

