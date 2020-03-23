<?php 
global $wpdb, $table_prefix;
if(!isset($wpdb)){
    require_once($_SERVER['DOCUMENT_ROOT'] .'/wp-config.php');
    require_once($_SERVER['DOCUMENT_ROOT'] .'/wp-includes/wp-db.php');
}

function instagram_feed_update($atts){
     //ob_start();
     if ($atts['insta_url']) {
          $instagram_body = file_get_contents($atts['insta_url']);
          $instagram_feed_id = ($atts['id'] != '' ? $atts['id'] : 'instagram_feed_container');
          $d = new DOMDocument;
          libxml_use_internal_errors(true);
          $d->loadHTML($instagram_body);
          libxml_clear_errors();
          $x = new DOMXPath($d);
          $lots = $x->query('//script[contains(@type, "text/javascript")]');
          $instagram_json = '';

          foreach ($lots as $lot) { 
               $curr_lot = $lot; 
               $json_check = strpos($lot->nodeValue, 'window._sharedData');
               if ($json_check !== false) {
                    $instagram_json = $lot->nodeValue;
                    break;
               }
          }
          if ($instagram_json != '') {
               $instagram_json_explode = explode('window._sharedData = ', $instagram_json);
               $instagram_json = $instagram_json_explode[1];
               $instagram_json = json_encode($instagram_json, true);  
               
               $current_date = date('Y-m-d H:i:s');
               update_option( 'instagram_feed_time', $current_date);
               update_option( 'instagram_feed', $instagram_json);  

          }

     }     

}
echo '<p>Connecting...</p>';
echo '<p>Retrieving Grams from the Insta...</p>';
$atts = array('insta_url'=>'ENTER URL HERE', 'id'=>'instagram_feed_container');
instagram_feed_update($atts);
echo '<p>We have all the Grams from Insta...</p>';
echo '<p>Completed!</p>';

 ?>