<?php

/*
Plugin Name: Weather Place
Plugin URI: 
Description: Display a text sub button 
Version: 1.0.0
Author: Denis
Author URI: 

 */

/* Globa vars */



$plugin_url = WP_PLUGIN_URL . '/my-weather';

//$images_day = array('./images/day/clear.png', './images/day/clear.png', './images/day/clouds.png', './images/day/mist.png', './images/day/rain.png', './images/day/shower-rain.png', './images/day/snow.png', './images/day/storm.png');

$imagesDay = $plugin_url . '/images/day/' ;
$imagesNight = $plugin_url . '/images/night/' ;

$location = 'Miami,us';



function wp_mywheather_menu()
{


    add_options_page(
        'My Weather Plugin',
        'Class WP Avanzed',
        'manage_options',
        'my-weather',
        'wpweather_get_profile'
    );
}

add_action('admin_menu', 'wp_mywheather_menu');





function wpweather_get_profile($location)
{

    global $plugin_url;
    global $images_day;
    global $imagesDay;
    global $imagesNight; 
    global $location;

  

    echo ('

    <style> 
        .container {
            margin: 25px;
            paddin: 15px;
        }
        table, td, th {  
            border: 1px solid #ddd;
            text-align: center;
          }
          
          table {
            border-collapse: collapse;
            width: 50%;
          }
          
          th, td {
            padding: 15px;
          }
    </style>
       
    <div class="container">
        <form   method="post">
           <h1> Enter New Location </h1>
            Location: <input type="text" name="location"  placeholder = "Example: Miami,us">
            <input type="submit">
        </form>

        <br>
        <hr>
    </div>


');

    $location = $_POST["location"] ;

    $json_feed_url = 'https://api.openweathermap.org/data/2.5/weather?q=' . $location . '&appid=42cf2654dc52a783c605577b3dbf51d7';


    $args = array('timeoute' => 120);

    $json_feed = wp_remote_get($json_feed_url, $args);

    $wpweather_profile = json_decode($json_feed['body']);

  // var_dump($wpweather_profile);


    ?>

    <div class = "container">

     <h1> <?php echo $wpweather_profile->name . '/' . $wpweather_profile->sys->country ?> </h1>

     <img src="<?php echo $images . $wpweather_profile->weather[0]->main . '.png'; ?>" alt = '' />
    
     <br>
    
     <h2> Temperature </h2>
     <?php $f = floor((1.8 * $wpweather_profile->main->temp) - 459.67) . 'F';?>
     <h1> <?php echo $f; ?> </h1> 
     <?php $c = floor(($wpweather_profile->main->temp) - 273.15) . 'C';?>
     <h1> <?php echo $c; ?> </h1> 
    </div>

    <br>
    
    <hr>
    
      <div class="container">

          <h1> Weather </h1> 

          <table> 
          <th> Location </th>
          <th>Day </th>
          <th>Night</th>
          <tr>
            <td>  <?php echo $wpweather_profile->name . '/' . $wpweather_profile->sys->country ?></td>
            <td><img src="<?php echo $imagesDay . $wpweather_profile->weather[0]->main . '.png'; ?>" alt = '' /></td>
            <td><img src="<?php echo $imagesNight . $wpweather_profile->weather[0]->main . '.png'; ?>" alt = '' /> </td>
          </tr>
          <tr>
        
          </tr>
          <tr> 
             <th>Temperature </th>
             <th>Winds </th>
             <th>Humidity</th>
          </tr>
          <tr>
             <td> <?php echo $f . '/'. $c; ?></td>
             <td><?php echo $wpweather_profile->wind->speed ; ?></td>
             <td><?php echo $wpweather_profile->main->humidity ; ?></td>
         
          </tr>
          </table>

          <br>
          <hr>


          <p> DESCRIPTION </p>
          <p><?php echo $wpweather_profile->weather[0]->main; ?></p>
          <p><?php echo $wpweather_profile->weather[0]->description; ?></p>

      </div>
    <?php
  
   return $wpweather_profile;
}


add_action('wp_enqueue_scripts', 'wpweather_get_profile');
