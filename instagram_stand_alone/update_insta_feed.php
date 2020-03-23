<?php 
function instagram_feed_update($atts = array()){
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
               
               $feed_file = fopen("feed.json" ,"w") or die("Unable to open file!");
               fwrite($feed_file, $instagram_json);
               fclose($feed_file);

          }

     }     

}
$atts = array('insta_url'=>'ENTER URL HERE', 'id'=>'instagram_feed_container');
instagram_feed_update($atts);


?>