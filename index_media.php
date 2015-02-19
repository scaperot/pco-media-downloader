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
    $LIST = FALSE;
    $BYNAME=FALSE;
    $NAME="JOJO.JPG";
	
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
        
        //Item 0 of $plans is the most recent plan...
        echo "Fetching Site's Most Recent Plan: {$plans[0]->dates}\n";
        $medias = $pco->getMedias();

        //$j = json_encode($medias[0]);
        //echo "{$j}\n";
        


        //Iterate through all the media  and get image attachments...
        $save_attachments = null;
        $n = 0;
        foreach($medias as $media){
                //iterate through all attachments to find the media files...for now just images.
                $len = count($media->attachments);

		        //echo "      Saving: at least $len attachments.\n";
			foreach($media->attachments as $attachment){




			    //may add other types someday...
			    if (strpos($attachment->content_type,"image") !== FALSE) {
                            if ($LIST==TRUE) {
                                echo "    {$attachment->filename}\n";

                            }
                            elseif (($BYNAME==TRUE) && ($attachment->filename==$NAME)) {
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
                            elseif ($BYNAME==FALSE) {


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
         
        }
