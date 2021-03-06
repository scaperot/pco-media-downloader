<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Get options from the command line or web request
 *
 * @param string $options
 * @param array $longopts
 * @return array
 */
function getoptions ($options, $longopts)
{
   if (PHP_SAPI === 'cli' || empty($_SERVER['REMOTE_ADDR']))  // command line
   {
      return getopt($options, $longopts);
   }
/* else if (isset($_REQUEST))  // web script //DAS - NOT TESTED WITH MY MEDIA SETUP ASSISTANT!
   {
      $found = array();

      $shortopts = preg_split('@([a-z0-9][:]{0,2})@i', $options, 0, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
      $opts = array_merge($shortopts, $longopts);

      foreach ($opts as $opt)
      {
         if (substr($opt, -2) === '::')  // optional
         {
            $key = substr($opt, 0, -2);

            if (isset($_REQUEST[$key]) && !empty($_REQUEST[$key]))
               $found[$key] = $_REQUEST[$key];
            else if (isset($_REQUEST[$key]))
               $found[$key] = false;
         }
         else if (substr($opt, -1) === ':')  // required value
         {
            $key = substr($opt, 0, -1);

            if (isset($_REQUEST[$key]) && !empty($_REQUEST[$key]))
               $found[$key] = $_REQUEST[$key];
         }
         else if (ctype_alnum($opt))  // no value
         {
            if (isset($_REQUEST[$opt]))
               $found[$opt] = false;
         }
      }

      return $found;
   }*/

   return false;
}

function print_help_message() {
    echo "      Usage:
      php index.php [option] [value]

      -h, shows this menu
      -s, church site: 'DC' or 'KT' (default is 'DC')
      -p, absolute path where media is downloaded (if nothing is given, then /var/tmp used)
      -t, type of download: all media in org (=media), all songs in org (=song), by default all media in most recent plan.
";
}
