# Non-API-Instagram-feeds
Be able to capture public Instagram feeds and display them on your site or application.

Pre-requisites:

*Unsemantic grid framework: https://unsemantic.com/

*Jquery (2.2.4 or higher): https://code.jquery.com/



-----------------------------Blog Post ---------------------------------------------------


Summary

Back in July of 2018, I came across an issue with a client in which they wanted to have an Instagram feed on their website but they didn’t want to provide logins for their facebook. ultimately creating conflict in configuring any wordpress plugin with their API. 

Wordpress Solution:

Previously before Wordpress offered their RestAPI service, I would make non-indexed pages of content I wanted to be loosely coupled and would curl them between servers on a cron job. This would allow me to get whatever data I needed dynamically without having to set up massive data-pull server code on the client side server instance and still keep the front end dynamic and in-sync. 

With this logic in mind I was able to come up with a Curl code script that will initialize whatever Public Instagram profile that was available and curl through their entire instagram feed and collect all image, caption, hashtag nodes I would need. Once the nodes have been gathered in a JSON array I would date stamp them in the WP-options table and save them for client side pulls at any time. This would run on an hourly refresh with a backend manual refresh button under the Settings tab. 

Drupal/Laravel Solution:

I had also ran into the same issue with a client in which they were running a Drupal based dashboard. They wanted to include no extensions but wanted to also display their companies instagram feed for their user base. I was able to deconstruct the saving mechanism in my original Wordpress plugin to then save a .json file in their server’s file system upon a scheduled Cron Job and had written in a php function that pulled the json file and decoded it as a php array.

All other Solution:

As more technologies emerged and clients had different needs for their overall application/website builds I decided to host an instagram mediation server with this Curl code that will re-display anyones instagram feed as a RestAPI instead of just a .json file on their local machine. I made a basic t2 micro ec2 instance and a 5 dollar lightsail instance and created a simple url with a parameter of their instagram user and randomized number schema provided by me. The client can use that API url and pull the json page at their whim. On my Lightsail run a cron every 5 mins on the clients instagram and the EC2 services its clients RestAPI request. This allows the client to retrieve their files without delay and it's the most accurate way to keep the data current. The lightsail server will also reboot if its given a failed response and since there is no static IP attached to the device it will refresh its IP on reboot allowing it to retrieve data on the instagram page without being blocked.




