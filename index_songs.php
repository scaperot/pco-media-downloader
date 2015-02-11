<?php


	/*
	 * Media Downloader
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

    
    	$DOWNLOAD_DIR = '/var/tmp'; //default
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
	
        $options = getoptions('h::p::',array(''));
        //var_dump($options);

        foreach (array_keys($options) as $opt) switch ($opt) {

            case 'p':
    		$DOWNLOAD_DIR=$options['p'];
    		if (!is_writable($DOWNLOAD_DIR)) {
        		echo "Directory $DOWNLOAD_DIR is NOT writeable.  Permission Denied.\n";
        		exit(1);
    		}
    		break;

	    //case 'a':
        //	download_all_songs();
       	//	exit(1);

	    case 'h':
		print_song_help_message();
		exit(1);
	}

        $pcoutils->setDownloadDir($DOWNLOAD_DIR);
        $pcoutils->downloadAllSongs();


