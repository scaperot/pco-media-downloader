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

    
    $DC_SITE = 1;
    $KT_SITE = 2;
    $CAPCITY_SUNDAY_SERVICE = 0;
    $CAPCITY_KIDS = 1;
    
    $SERVICETYPE = $CAPCITY_SUNDAY_SERVICE; //$CAPCITY_KIDS = 1;
    $SITE = $DC_SITE; //default
    $SITE_STR = 'DC';
    $DOWNLOAD_DIR = '/var/tmp'; //default
    $BLACK_SLIDE  = 'https://planningcenteronline.com/attachments/23267938';
    
	
	$pco = new PlanningCenterOnline($settings);
	
	/**
	 * BEGIN: Login
	 */
	//$callbackUrl = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}"; //e.g. url to this page on return from auth
        $callbackUrl = ""; 
        $r = $pco->login($callbackUrl,PlanningCenterOnline::TOKEN_CACHE_FILE);//saves access token to file
	
	if(!$r){
		die("Login Failed");
	}
	
	#Query for Organization
	$o = $pco->organization;
                
        //Find the most recent Service for site
        
        echo "Organization: {$o->name} - {$o->owner_name}\n";

        
        echo "Service Types: \n";
        $s = 0;
        foreach($o->service_type_folders as &$site_type) {
            echo "    SITE $s: $site_type->name\n";
            $s = $s+1;
            $t = 0;
            foreach($site_type->service_types as &$service_type){
                echo "        SERVICE $t: {$service_type->name}, ";
	        $plans = $pco->getPlansByServiceId($service_type->id);
                $n = sizeof($plans);
                echo "{$n} Plans Available.\n";
                $t = $t + 1;
            }	
        }
