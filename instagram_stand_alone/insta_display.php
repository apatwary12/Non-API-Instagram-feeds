<?php 
// display code 
require_once('../header.php'); 
require_once('/var/www/html/instagram/instagram.php');
echo '<div style="height:150px;"></div>';
if (function_exists('get_instagram_feed')) {
     echo get_instagram_feed();
}
require_once('../footer.php'); 
?>