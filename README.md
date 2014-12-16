====================
media-setup-assistant
=====================

Media Setup Assistant for Capital City Church DC (www.capcitychurch.com).  Using the Planning Center Online API, and some inspiration from the Planning Center Online Helper project on github (https://github.com/scaperot/PlanningCenterOnline-API-Helper), putting together a php script that will find the most recent service and then download all media attachments (images &amp; videos).

To get started open settings.php and add your keys to the fields:
>> gedit src/com.capcitychurch/settings.php

Run the program from the command line from the base directory:

Usage:
      index.php [option] [value]

      Options:    
      --help, -help, -h  - prints this message.
      --site - sets the site.  Valid values are: 'DC' or 'KT'.  Default is 'DC'
      --destination  - sets the destination path
            to download attachments from PCO.
  

