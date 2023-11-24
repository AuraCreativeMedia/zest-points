<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://digitalzest.co.uk
 * @since      1.0.0
 *
 * @package    zest-points
 * @subpackage zest-points/admin/partials/components
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php

// Check for necessary privileges
if (!current_user_can('manage_options')) {
    return;
}

?>

<div class="zest-points-admin-header">
    
    <nav class="bg-gray-800">
        <div class="max-w-7xl px-2 sm:px-6 lg:px-8 pt-4 pb-2">
            <div class="relative flex h-16 items-center justify-between">
            
            <div class="flex flex-1 items-center justify-center sm:justify-start sm:items-end">
                <div class="flex flex-shrink-0 items-center">
                <img  class="h-16 w-auto" src="<?php echo ZEST_PLUGIN_ROOT_URI . 'admin/assets/img/zestpoints-logo.png'; ?>" alt="Zest Points" width="150"/>
                </div>
                <div class="hidden sm:ml-12 sm:block pb-2">
                <div class="flex space-x-4">
                    <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                    <a href="<?php echo admin_url('admin.php?page=zest-settings'); ?>" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-base font-medium">Settings</a>
                    <a href="<?php echo admin_url('admin.php?page=zest-user-control'); ?>" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-base font-medium">Users</a>
                    <a href="<?php echo admin_url('admin.php?page=zest-log-track'); ?>" class="text-gray-300 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-base font-medium">Logs</a>
                </div>
                </div>
            </div>
            
            </div>
        </div>


        </nav>

</div>

<div class="inner-wrap px-8 pt-6">

    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900"><?php echo $page_title; ?></h1>

    <div class="woomio-content px-8 py-8 bg-[#d8dee5]">

