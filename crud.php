<?php
/*
Plugin Name: Murad WP CRUD v1
Plugin URI: #
Description: This is Simple CRUD Plugin
Version: 0.1
Author URI: https://www.facebook.com/engrmurad.info/
Author: Md.Murad Hosen
License: GPL2
Text Domain: 
*/
register_activation_hook( __FILE__, 'crudOperationsTable');

function crudOperationsTable() {
  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();
  $table_name = $wpdb->prefix . 'userstable';
  $sql = "CREATE TABLE `$table_name` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` varchar(220) DEFAULT NULL,
  `n_cus_name` varchar(220) DEFAULT NULL,
  `n_cus_mobile` varchar(220) DEFAULT NULL,
  `n_cus_address` varchar(220) DEFAULT NULL,
  PRIMARY KEY(id)
  ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
  ";
  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }
}



register_deactivation_hook( __FILE__, 'my_plugin_remove_database' );
function my_plugin_remove_database() {
     global $wpdb;
     $table_name = $wpdb->prefix . 'userstable';
     $sql = "DROP TABLE IF EXISTS $table_name";
     $wpdb->query($sql);
     delete_option("my_plugin_db_version");
}   



add_action('admin_menu', 'addAdminPageContent');

function addAdminPageContent() {
  add_menu_page('CRUD', 'CRUD', 'manage_options' ,__FILE__, 'crudAdminPage', 'dashicons-wordpress');
}

function crudAdminPage() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'userstable';
  if (isset($_POST['newsubmit'])) {
    $customer_id = $_POST['customer_id'];
    $n_cus_name = $_POST['n_cus_name'];
    $n_cus_mobile = $_POST['n_cus_mobile'];
    $n_cus_address = $_POST['n_cus_address'];
    $wpdb->query("INSERT INTO $table_name(customer_id,n_cus_name,n_cus_mobile,n_cus_address) VALUES('$customer_id','$n_cus_name','$n_cus_mobile','$n_cus_address')");
    echo "<script>location.replace('admin.php?page=mcrud%2Fcrud.php');</script>";
  }

  if (isset($_POST['uptsubmit'])) {
    $id = $_POST['uptid'];
    $customer_id = $_POST['customer_id'];
    $n_cus_name = $_POST['n_cus_name'];
    $n_cus_mobile = $_POST['n_cus_mobile'];
    $n_cus_address = $_POST['n_cus_address'];
    $wpdb->query("UPDATE $table_name SET customer_id='$customer_id',n_cus_name='$n_cus_name',n_cus_mobile='$n_cus_mobile',n_cus_address='$n_cus_address' WHERE id='$id'");
    echo "<script>location.replace('admin.php?page=mcrud%2Fcrud.php');</script>";
  }

  if (isset($_GET['del'])) {
    $del_id = $_GET['del'];
    $wpdb->query("DELETE FROM $table_name WHERE id='$del_id'");
    echo "<script>location.replace('admin.php?page=mcrud%2Fcrud.php');</script>";
  }

  ?>
  <div class="wrap">
    <h2>CRUD Operations</h2>
    <table class="wp-list-table widefat striped">
      <thead>
        <tr>
          <th width="20%">User ID</th>
          <th width="20%">Customer ID</th>
          <th width="20%">Customer Name</th>
          <th width="20%">Customer Mobile</th>
          <th width="20%">Customer Address</th>
          <th width="20%">Actions</th>
        </tr>
      </thead>
      <tbody>
        <form action="" method="post">
          <tr>
            <td><input type="text" value="AUTO_GENERATED" disabled></td>
            <td><input type="text" id="customer_id" name="customer_id"></td>
            <td><input type="text" id="n_cus_name" name="n_cus_name"></td>
            <td><input type="text" id="n_cus_mobile" name="n_cus_mobile"></td>
            <td><input type="text" id="n_cus_address" name="n_cus_address"></td>

            <td><button id="newsubmit" name="newsubmit" type="submit">INSERT</button></td>
          </tr>
        </form>
        <?php
          $result = $wpdb->get_results("SELECT * FROM $table_name");
          foreach ($result as $print) {
            echo "
              <tr>
                <td width='20%'>$print->customer_id</td>
                <td width='20%'>$print->n_cus_name</td>
                <td width='20%'>$print->n_cus_mobile</td>
                <td width='20%'>$print->n_cus_address</td>
                <td width='20%'><a href='admin.php?page=mcrud%2Fcrud.php&upt=$print->id'><button type='button'>UPDATE</button></a> <a href='admin.php?page=mcrud%2Fcrud.php&del=$print->customer_id'><button type='button'>DELETE</button></a></td>
              </tr>
            ";
          }
        ?>
      </tbody>  
    </table>
    <br>
    <br>
    <?php
      if (isset($_GET['upt'])) {
        $upt_id = $_GET['upt'];
        $result = $wpdb->get_results("SELECT * FROM $table_name WHERE id='$upt_id'");
        foreach($result as $print) {
          $customer_id = $print->customer_id;
          $n_cus_name = $print->n_cus_name;
          $n_cus_mobile = $print->n_cus_mobile;
          $n_cus_address = $print->n_cus_address;
        }
        echo "
        <table class='wp-list-table widefat striped'>
          <thead>
            <tr>
              <th width='20%'> ID</th>
              <th width='20%'> Customer ID</th>
              <th width='20%'>Name</th>
              <th width='20%'>Email Address</th>
              <th width='20%'>Actions</th>
            </tr>
          </thead>
          <tbody>
            <form action='' method='post'>
              <tr>
                <td width='20%'>$print->id <input type='hidden' id='uptid' name='uptid' value='$print->id'></td>
                <td width='20%'><input type='text' id='customer_id' name='customer_id' value='$print->customer_id'></td>
                <td width='20%'><input type='text' id='n_cus_name' name='n_cus_name' value='$print->n_cus_name'></td>
                <td width='20%'><input type='text' id='n_cus_mobile' name='n_cus_mobile' value='$print->n_cus_mobile'></td>
                <td width='20%'><input type='text' id='n_cus_address' name='n_cus_address' value='$print->n_cus_address'></td>

                <td width='20%'><button id='uptsubmit' name='uptsubmit' type='submit'>UPDATE</button> <a href='admin.php?page=mcrud%2Fcrud.php'><button type='button'>CANCEL</button></a></td>
              </tr>
            </form>
          </tbody>
        </table>";
      }
    ?>
  </div>
  <?php
}