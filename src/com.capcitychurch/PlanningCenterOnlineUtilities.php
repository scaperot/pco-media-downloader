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
    private $download_dir;

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

    public function downloadArrangementByContentType($arrangement,$type='audio/mpeg') {
            foreach($arrangement->attachments as &$attachment) {
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

        }
 
    }


/*
 * mode_flag: three possible ways to download [all,first arrangement,earliest date]
 *
 
function downloadSongs($songs,'all') {

}

function getSongAge($song){
    
}

function getAttachments($obj) {
}
*/
