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

  case 'h':
    print_help_message();
    exit(1);

   

}
        
        $songs = $pco->getSongs();
        $n = sizeof($songs);
        echo "Total Number of Songs: {$n}\n";
        $s = 0;
        foreach($songs as &$song) {
           if ($s==2) {
                echo "Printing the first songs information\n";
		$arrangements = $pco->getArrangementsById($song->id);
                foreach($arrangements as &$arrangement) {
                    foreach($arrangement->attachments as &$attachment) {
                         echo "    Attachment: $attachment->filename.\n";
                         //$j = json_encode($attachment);
                         //echo "{$j}\n";
                    }            
                }
                
            }   
            $s = $s+1;
        }
        //$j = json_encode($songs);
        //echo "{$j}\n";

/*	#Query for Organization
	$o = $pco->organization;
        
        
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
        
        //Iterate through all the items in plan and get image attachments...
        $save_attachments = null;
        $n = 0;
        foreach($plan->items as $item){
            //echo "  Item: $item->title\n";
            //start saving attachments after the 'Service' title.
            if ($item->title == "PW Set") {
                $save_attachments = TRUE;
            }
            
            
            if ($save_attachments == TRUE) {
                //iterate through all attachments to find the media files...for now just images.
                $len = count($item->attachments);
                //echo "      Saving: at least $len attachments.\n";
                foreach($item->attachments as $attachment){
                    //may add other types someday...
                    if (strpos($attachment->content_type,"image") !== FALSE) {
                        $n = $n + 1;
                        echo "      Saving: $DOWNLOAD_DIR/$attachment->filename ($attachment->content_type)\n";
                        //write to file...
                        //Other things I should check for: 
                        //$attachment->downloadable = true, 
                        $new_file_name = "$DOWNLOAD_DIR/$n. $attachment->filename";
                        $url = $attachment->url;
                        //echo "      Saving from URL: $url\n";                        
                        $r = $pco->getAttachment($url,NULL,OAUTH_HTTP_METHOD_GET,$attachment->content_type);
                        //download to $download_dir
                        try {
                            copy($r['redirect_url'],$new_file_name);
                        }
                        catch (Exception $e) {
                            echo "Failed to Download: {$attachment->filename}, {$e->getMessage}\n";
                            return; 
                        }   
                        
                    }      
                }
            }      
        }*/
