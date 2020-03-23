<?php 
/*
Plugin Name:  Instagram Feed
Plugin Name: https://ari-senpai.ninja
Description: Pulls Instagram Feed from public Instagram profiles. Wordpress independent. Saves content files
Author: Ari
Author URI: https://ari-senpai.ninja
Version: 1.0

/**************************************************** CORE FUNCTIONS  *********************************************************/




function get_instagram_feed(){
ob_start();     
//Read in JSON value
$URL = $_SERVER['REQUEST_URI'];
$parse = parse_url($URL);
$w_protocol = $_SERVER['SERVER_PROTOCOL'].$parse
$instagram_json = file_get_contents("".$w_protocol."/instagram/feed.json");
$instagram_feed = json_decode($instagram_json);



//print_r($instagram_feed);
?>
     <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
     <link href="<?php echo $w_protocol; ?>/instagram/insta.css" rel="stylesheet">
     <div class="instagram_feed_container" id="instagram_feed_id"></div>
     <script type="text/javascript">
          var instagram_feed = <?php echo $instagram_feed ?>;
          console.log(instagram_feed);
          instagram_feed = JSON.stringify(instagram_feed);
          //instagram_feed = instagram_feed.stringify().slice(0, -1);
          instagram_feed = JSON.parse(instagram_feed);
          //console.log(instagram_feed);  
          //console.log(instagram_feed_1);  
          //console.log(instagram_feed.entry_data.ProfilePage[0].graphql.user.edge_owner_to_timelin0e_media.edges);
          var instagram_feed_images = instagram_feed.entry_data.ProfilePage[0].graphql.user.edge_owner_to_timeline_media.edges;
          for (i = 0; i < 5; i++) {
               var time_increase = 300 * i;                     
               var curr_node = instagram_feed_images[i].node;
               var curr_node_caption = curr_node.edge_media_to_caption.edges[0].node.text;
               if(curr_node_caption.length > 100) curr_node_caption = curr_node_caption.substring(0,100) + '...';
               var curr_node_shortcode = curr_node.shortcode;
               var curr_node_url = 'https://www.instagram.com/p/'+curr_node_shortcode;
               var curr_node_url_encoded = encodeURIComponent('https://www.instagram.com/p/'+curr_node_shortcode);
               var curr_node_timestamp = curr_node.taken_at_timestamp;
               var curr_date = new Date(curr_node_timestamp*1000);
               //console.log(curr_date);
               var curr_node_date = (curr_date.getMonth()+1)+' '+curr_date.getDate()+', '+curr_date.getFullYear();
               var curr_node_comment = curr_node.edge_media_to_comment.count;
               var curr_node_like = curr_node.edge_media_preview_like.count;                    
               var curr_node_display = '<div class="instagram_feed_single" data-aos="fade-right" data-aos-duration="'+time_increase+'">';
                    curr_node_display += '<div class="instagram_feed_single_image" style="background-image: url('+curr_node.display_url+');"></div>'; 
                    //curr_node_display += '<img src="'+curr_node.display_url+'" class="instagram_feed_single_image">';
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
     <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
     <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
     <script type="text/javascript">
          /*jQuery('#instagram_feed_id').owlCarousel({
               loop:true,
               nav:false,
               items:5,
               mouseDrag:false,
               lazyLoad: true,
               dots: false,
               navText: ['<i class="fa fa-chevron-left" aria-hidden="true">','<i class="fa fa-chevron-right" aria-hidden="true">'],
               responsive:{
                  0:{
                      items:2,
                      nav:true
                  },
                  768:{
                      items:5,
                      nav:false
                  },
                  1200:{
                      items:5,
                      nav:true,
                      loop:false
                  }
              }
          });*/
     </script>
     <?php 
     $output = ob_get_contents();
     ob_end_clean();
     
     return $output;
     
}
?>