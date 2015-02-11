====================
pco-media-downloader
====================

PHP CLI scripts to hit the PCO site (focused on download not updload).  Work being done for Capital City Church DC (www.capcitychurch.com).  Using the Planning Center Online API, and some inspiration from the Planning Center Online Helper project on github (https://github.com/deboorn/PlanningCenterOnline-API-Helper).  Three major functions: (1)  find the most recent service and then download visual media attachments (images &amp; videos), (2) download all media from PCO, (3) download all songs from PCO.

To get started open settings.php and add your keys to the file:

      gedit src/com.capcitychurch/settings.php

Run the program from the command line from the base directory:

      Usage:
      php index.php [option] [value]

      -h, shows this menu
      -s, church site: 'DC' or 'KT' (default is 'DC')
      -p, absolute path where media is downloaded (if nothing is given, then /var/tmp used)
      -t, type of download: all media in org (=media), all songs in org (=song), by default all media in most recent plan (=plan).

      Example:
      php index.php -s='DC' -p='/home/user'
      php index.php -t=song
      php index.php -t=media -p='home/user'

