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
    $DOWNLOAD_DIR = '/var/tmp'; //default
    $BLACK_SLIDE  = 'https://planningcenteronline.com/attachments/23267938';
    $LIST = FALSE;
    $BYNAME=FALSE;
    $NAME="JOJO.JPG";
	
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
	
        $options = getoptions('h::s::p::l::n::',array(''));
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
    $DOWNLOAD_DIR=$options['p'];
    if (!is_writable($DOWNLOAD_DIR)) {
        echo "Directory $DOWNLOAD_DIR is NOT writeable.  Permission Denied.\n";
        exit(1);
    }
    break;

  case 'n':
    $BYNAME=TRUE;
    $NAME=$options['n'];
    break;

  case 'l':
    $LIST=TRUE;
    break;


  case 'h':
    print_help_message();
    exit(1);

   

}
        


	#Query for Organization
	$o = $pco->organization;
        
        
        //Find the most recent Service for site
        $service = $o->service_type_folders[$SITE]->service_types[$SERVICETYPE];
        echo "$SITE_STR Service. ";          

	//get all plans by service id
	$plans = $pco->getPlansByServiceId($service->id);
        //$n = sizeof($plans);
        //echo "Number of Plans: {$n}\n";
        //$pcoutils->setDownloadDir($DOWNLOAD_DIR);

        //Item 0 of $plans is the most recent plan...
        echo "Fetching Site's Most Recent Plan: {$plans[0]->dates}\n";


        //$j = json_encode($medias[0]);
        //echo "{$j}\n";
        



        
