<?php


	/*
	 * Media Setup Assistant 
	 * @license ?
	 * @copyright 2014 ?
	 * @author David S. scaperot@vt.edu 
	 * @requiresPHP PECL OAuth, http://php.net/oauth
	 */

    $download_dir = '';
    $black_slide  = 'https://planningcenteronline.com/attachments/23267938';
    
    if ($argc==2 && in_array($argv[1], array('--help', '-help', '-h'))) {    
?>
      Capcitychurch.com Media Setup Assistant.

      Usage:
      <?php echo $argv[0]; ?> <option> <value>

      Options:    
      --help, -help, -h  - prints this message.
      --destination  - sets the destination path
            to download attachments from PCO.
            
      
<?php
          return;
      } 
      //if the number of args is two (i.e. key value
      elseif ($argc == 3 && in_array($argv[1], array('--destination','-d'))) {
          $download_dir = $argv[2];
      }
      else {
          $download_dir = '/Users/Shared';
      }
      

	ini_set('display_errors','1');

	//session_start();

	require('src/com.rapiddigitalllc/PlanningCenterOnline.php');
	require('src/com.capcitychurch/settings.php');
	

	
	//echo "<pre>";//view formatted debug output
	
	$pco = new PlanningCenterOnline($settings);
	
	/**
	 * BEGIN: Login
	 */
	//$callbackUrl = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['SCRIPT_NAME']}"; //e.g. url to this page on return from auth
        $callbackUrl = ""; //not needed for command line execution.
        $r = $pco->login($callbackUrl,PlanningCenterOnline::TOKEN_CACHE_FILE);//saves access token to file
	
	if(!$r){
		die("Login Failed");
	}
	
	#Query for Organization
	$o = $pco->organization;
        
        //Find the most recent Service for site
        $SITE_FOLDER=0; //DC is 0, KT is 1;
        $dcservice = $o->service_type_folders[$SITE_FOLDER]->service_types[0];
        echo "DC Service: {$dcservice->id}\n";          

//	//get all plans by service id
	$plans = $pco->getPlansByServiceId($dcservice->id);
        
        //Item 0 is the most recent plan...
        echo "Fetching Site's Most Recent Plan: {$plans[0]->id} - {$plans[0]->dates}\n";
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
                        echo "      Saving: $download_dir/$attachment->filename ($attachment->content_type)\n";
                        //write to file...
                        //Other things I should check for: 
                        //$attachment->downloadable = true, 
                        $new_file_name = "$download_dir/$n. $attachment->filename";
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
