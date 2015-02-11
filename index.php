<?php


	/*
	 * Media Setup Assistant 
	 * @license ?
	 * @copyright 2014 ?
	 * @author David S. scaperot@vt.edu 
	 * @requiresPHP PECL OAuth, http://php.net/oauth
	 */


	ini_set('display_errors','1');

	session_start();

	require('src/com.rapiddigitalllc/PlanningCenterOnline.php');
	require('src/com.capcitychurch/settings.php');
        require('src/com.capcitychurch/options.php');
        require('src/com.capcitychurch/PlanningCenterOnlineUtilities.php');

    
    $DC_SITE = 1;
    $KT_SITE = 2;
    $CAPCITY_SUNDAY_SERVICE = 0;
    $CAPCITY_KIDS = 1;
    
    $SERVICETYPE = $CAPCITY_SUNDAY_SERVICE; //$CAPCITY_KIDS = 1;
    $SITE = $DC_SITE; //default
    $SITE_STR = 'DC';
    //$DOWNLOAD_DIR = '/var/tmp'; //default (built into the utilities class)
    $BLACK_SLIDE  = 'https://planningcenteronline.com/attachments/23267938';
    
	
	$pco = new PlanningCenterOnline($settings);
        $pcoutils = new PlanningCenterOnlineUtilities($pco);

	
	/**
	 * BEGIN: Login
	 */
	//$callbackUrl = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}"; //e.g. url to this page on return from auth
        $callbackUrl = ""; 
        $r = $pco->login($callbackUrl,PlanningCenterOnline::TOKEN_CACHE_FILE);//saves access token to file
	
	if(!$r){
		die("Login Failed");
	}
	
        $options = getoptions('h::s::p::t::',array(''));
        //var_dump($options);

 foreach (array_keys($options) as $opt) switch ($opt) {
  case 's':
    $SITE_STR=$options['s'];
    if ($SITE_STR=='DC') {
        $SITE=$DC_SITE;
    }
    elseif ($SITE_STR=='KT') {
        $SITE=$KT_SITE;
    }
    else {
        echo "$SITE_STR is NOT a Site.\n";
        print_help_message();
        exit(1);
    }
    break;

  case 'p':
    $pcoutils->setDownloadDir($options['p']);
    if (!is_writable($options['p'])) {
        echo "Directory {$options['p']} is NOT writeable.  Permission Denied.\n";
        exit(1);
    }
    break;
  case 't':
      if ($options['t'] == "media") {
          echo "download all media in org.\n";
          $pcoutils->downloadAllMedia();
          return;
      }
      elseif ($options['t'] == "plan") {
          echo "download all media by plan.\n";
      }
      elseif ($options['t'] == "song") {
          echo "download all songs in org";
          $pcoutils->downloadAllSongs();
          return;
      }
      break;

  case 'h':
    print_help_message();
    exit(1);

  default:
    print_help_message();
    exit(1);

}
  
	$o = $pco->organization;
        //By Default attempt to download by plan.
        //Find the most recent Service for site
        $service = $o->service_type_folders[$SITE]->service_types[$SERVICETYPE];
        echo "$SITE_STR Service. ";          

	//get all plans by service id
	$plans = $pco->getPlansByServiceId($service->id);
        //$n = sizeof($plans);
        //echo "Number of Plans: {$n}\n";
        
        //Item 0 of $plans is the most recent plan...
        echo "Fetching Site's Most Recent Plan: {$plans[0]->dates}\n";
        $plan = $pco->getPlanById($plans[0]->id);

        //$j = json_encode($plan);
        //echo "{$j}\n";
        $pcoutils->downloadMediaByPlan($plan);




