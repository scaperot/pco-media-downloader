<?php

/**
 * Utility Class for processing data from Planning Center Online API
 * @class PlanningCenterOnlineUtilities
 * @license Non-Commercial Creative Commons, http://creativecommons.org/licenses/by-nc/2.0/, code is distributed "as is", use at own risk, all rights reserved
 * @copyright 2015 David Scaperoth
 * @author David Scaperoth scaperot@vt.edu - Available for hire. Email for more info.

 */
class PlanningCenterOnlineUtilities {

    /**
     * @var object
     */
    private $download_dir="/var/tmp";

    /**
     * @var object
     */
    private $pco;

    /**
     * @param $settings
     */
    public function __construct($pco_input) {
        $this->pco = (object)$pco_input;
    }

    /**
     * @dir string path for downloading files from PCO API.
     */
    public function setDownloadDir($str) {
        //TODO: should check to make sure its a string...
        //TODO: make sure you have write permissions...
        $this->download_dir=$str;
    }

    private function getDownloadDir() {
        return $this->download_dir;
    }

    private function downloadAttachmentByContentType($attachment,$type='audio/mpeg') {

                 //echo "    Attachment: Name: $attachment->filename, Content: $attachment->content_type, Upload Date: \n";
                 //$j = json_encode($attachment);
                 //echo "{$j}\n";
                 if (strpos($attachment->content_type,$type) !== FALSE) {
	                //write to file...
                	echo "      Saving: $this->download_dir/$attachment->filename ($attachment->content_type)\n";
	                //Other things I should check for: 
	                //$attachment->downloadable = true, 
	                $new_file_name = "$this->download_dir/$attachment->filename";
	                $url = $attachment->url;
	                //echo "      Saving from URL: $url\n";                        
	                $r = $this->pco->getAttachment($url,NULL,OAUTH_HTTP_METHOD_GET,$attachment->content_type);
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

    public function downloadAllSongs() {
        //download mp3 content_type in each song/arrangement
        $songs = $this->pco->getSongs();
        $n = sizeof($songs);
        echo "    Total Number of Songs: {$n}\n";
        $s = 0;
        foreach($songs as &$song) {
		$arrangements = $this->pco->getArrangementsById($song->id);
                echo "    Song: $song->title\n";

                foreach($arrangements as &$arrangement) {
                    foreach($arrangement->attachments as &$attachment) {
                        $this->downloadAttachmentByContentType($attachment,'audio/mpeg');
                    }
                }
                
            
            $s = $s+1;
        }
    }


    public function downloadAllMedia() {
        //Iterate through all the media  and get image attachments...
        $save_attachments = null;
        $n = 0;
        $medias = $this->pco->getMedias();
        foreach($medias as $media){
                //iterate through all attachments to find the media files...for now just images.
                $len = count($media->attachments);

		//echo "      Saving: at least $len attachments.\n";
		foreach($media->attachments as $attachment){
		    $this->downloadAttachmentByContentType($attachment,"image");
                 }
           }

    }

    public function downloadMediaByPlan($plan) {
        //Iterate through all the items in plan and get image attachments...
        $n = 0;
        foreach($plan->items as $item){
                //iterate through all attachments to find the media files...for now just images.
                $len = count($item->attachments);
                //echo "      Saving: at least $len attachments.\n";
                foreach($item->attachments as $attachment){
                    //may add other types someday...
                    $this->downloadAttachmentByContentType($attachment,"image");     
                }     
        }
    }

}
