<?php 
/*
Plugin Name: Instagram Feed
Plugin Name: https://ari-senpa.ninja
Description: Pulls Instagram Feed from public Instagram profiles
Author: Ari
Author URI: https://ari-senpa.ninja
Version: 1.0

*/
global $wpdb;
if(!isset($wpdb)){
    require_once($_SERVER['DOCUMENT_ROOT'] .'/wp-config.php');
    require_once($_SERVER['DOCUMENT_ROOT'] .'/wp-includes/wp-db.php');
}

//INIT SETTING START 

add_action( 'admin_menu', 'instagram_add_admin_menu' );
add_action( 'admin_init', 'instagram_settings_init' );

function instagram_add_admin_menu() { 
     add_options_page( 'instagram', 'instagram', 'manage_options', 'instagram', 'instagram_options_page' );
}
function instagram_settings_init() { 

     register_setting( 'pluginPage_instagram', 'instagram_settings' );

     add_settings_section(
          'instagram_plugin_section_1', 
          __( 'Add your instagram URL', 'wordpress' ), 
          'instagram_settings_section_callback', 
          'pluginPage_instagram'
     );

     add_settings_field("Instagram_URL", "Instagram URL", "instagram_checkbox_field_render",'', 'pluginPage_instagram');

     register_setting("pluginPage_instagram",'', "Instagram_URL");
}

function instagram_checkbox_field_render(array $args) { 
     $options = get_option( 'instagram_settings' );
     ?>
     <input type='text' name='instagram_settings[<?php echo $args['fieldname'];?>]' <?php !empty($options[$args['fieldname']]) ?  checked( $options[$args['fieldname']], 1 ) : ''; ?> value='1' placeholder="">
     <?php
}

function instagram_settings_section_callback(  ) { 
     echo __( '', 'wordpress' );
}


function instagram_options_page(  ) { 
     ?>

          
          <h2>Instagram Feed</h2>
          <?php 
          echo '<form name="update_feed" id="update_feed" action="" class="update_feed" method="GET">';     
          echo '<input value="Refresh Instagram Feed" type="button" class="submit" style="  background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%,#d6249f 60%,#285AEB 90%);color:#fff;padding:8px;border-radius:5px;cursor: pointer;text-shadow: 1px 1px #4f4f4f;font-weight:bold;" onClick="window.open(\'' .plugin_dir_url( __FILE__ ).'update_insta_feed.php' .'\', \'viewbox_iframe\'); return false"  />';               
          echo '<h5>Only hit submit once.</h5>';               
          echo '</form>';         
          echo '<hr />';          
          echo '<iframe width="100%" height="200" name="viewbox_iframe" id="rvct_viewbox_iframe" src="" frameborder="1" scrolling="yes"></iframe>';         
          ?>
          <form action='options.php' method='post'>
               <?php 
               /*
                    settings_fields( 'pluginPage_instagram' );
                    do_settings_sections( 'pluginPage_instagram' );
                    submit_button();
               */
               ?>
          </form>


     <?php
}



//INIT SETTING END



















/**************************************************** CORE FUNCTIONS  *********************************************************/





add_shortcode( 'instagram_feed', 'get_instagram_feed' );
function get_instagram_feed(){
ob_start();     
$instagram_json = get_option('instagram_feed');
$instagram_feed = json_decode($instagram_json);
//print_r($instagram_feed);
?>
     <div class="instagram_feed_container" id="instagram_feed_id"></div>
     <script type="text/javascript">
          var instagram_feed = <?php echo $instagram_json?>;
          //instagram_feed = JSON.stringify(instagram_feed);
          instagram_feed = instagram_feed.toString().slice(0, -1);
          instagram_feed = JSON.parse(instagram_feed);
          //console.log(instagram_feed);  
          //console.log(instagram_feed_1);  
          //console.log(instagram_feed.entry_data.ProfilePage[0].graphql.user.edge_owner_to_timelin0e_media.edges);
          var instagram_feed_images = instagram_feed.entry_data.ProfilePage[0].graphql.user.edge_owner_to_timeline_media.edges;
          for (i = 0; i < 15; i++) {
               var time_increase = 300 * i;                     
               var curr_node = instagram_feed_images[i].node;
               var curr_node_caption = curr_node.edge_media_to_caption.edges[0].node.text;
               if(curr_node_caption.length > 100) curr_node_caption = curr_node_caption.substring(0,100) + '...';
               var curr_node_shortcode = curr_node.shortcode;
               var curr_node_url = 'https://www.instagram.com/p/'+curr_node_shortcode;
               var curr_node_url_encoded = encodeURIComponent('https://www.instagram.com/p/'+curr_node_shortcode);
               var curr_node_timestamp = curr_node.taken_at_timestamp;
               var curr_date = new Date(curr_node_timestamp*1000);
              // console.log(curr_date);
               var curr_node_date = (curr_date.getMonth()+1)+' '+curr_date.getDate()+', '+curr_date.getFullYear();
               var curr_node_comment = curr_node.edge_media_to_comment.count;
               var curr_node_like = curr_node.edge_media_preview_like.count;                    
               var curr_node_display = '<div class="instagram_feed_single" data-aos="fade-right" data-aos-duration="'+time_increase+'">';
                    curr_node_display += '<div class="instagram_feed_single_image" style="background-image: url('+curr_node.display_url+');"></div>'; 
                    curr_node_display += '<div class="instagram_feed_single_modal"><a target="_blank" href="'+curr_node_url+'"></a></div>'; 
                    curr_node_display += '<div class="instagram_feed_single_overlay">';

                         curr_node_display += '<div class="instagram_feed_single_share">';
                              curr_node_display += '<a target="_blank" href="https://www.facebook.com/sharer.php?u='+curr_node_url_encoded+'/"><i class="fa fa-facebook"></i></a>';
                         curr_node_display += '</div>';   

                         curr_node_display += '<a target="_blank" href="'+curr_node_url+'"></a>';

                         curr_node_display += '<div class="instagram_feed_single_caption">';
                              curr_node_display += '<p class="">'+curr_node_caption+'</p>';
                         curr_node_display += '</div>';

                         curr_node_display += '<div class="instagram_feed_single_overlay_icons">';
                              curr_node_display += '<i class="fa fa-instagram"></i>';
                              curr_node_display += '<p><i class="fa fa-clock-o"></i>'+curr_node_date+'</p>';
                              curr_node_display += '<p><i class="fa fa-comment"></i>'+curr_node_comment+'</p>';
                              curr_node_display += '<p><i class="fa fa-heart"></i>'+curr_node_like+'</p>';
                         curr_node_display += '</div>';   

                    curr_node_display += '</div>';                                                                      
               curr_node_display += '</div>';
               //console.log(curr_node_display);
               jQuery('#instagram_feed_id').append(curr_node_display);
          }
     </script>          
     <?php 
     $output = ob_get_contents();
     ob_end_clean();
     return $output;

}
?>