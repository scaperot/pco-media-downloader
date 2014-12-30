====================
pco-media-downloader
=====================


Media Downloader for Capital City Church DC (www.capcitychurch.com).  Using the Planning Center Online API, and some inspiration from the Planning Center Online Helper project on github (https://github.com/deboorn/PlanningCenterOnline-API-Helper), putting together a php script that will find the most recent service and then download all visual media attachments (images &amp; videos).


To get started open settings.php and add your keys to the file:

      gedit src/com.capcitychurch/settings.php

Run the program from the command line from the base directory:

      Usage:
      php index.php [option] [value]

      -h, shows this menu
      -s, church site: 'DC' or 'KT' (default is 'DC')
      -p, absolute path where media is downloaded (if nothing is given, then /var/tmp used)

      Example:
      php index.php -s='DC' -p='/home/user'

