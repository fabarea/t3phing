My Phing tasks for TYPO3 CMS. Save me from some tedious work...

Usage:
------

$> phing

     [echo] Usage of this Phing:
     [echo] 
     [echo] phing dump6           - fetch the database and build it again locally for TYPO3 6.0
     [echo] phing dump            - fetch the database and build it again locally
     [echo] phing htaccess        - fetch the htaccess from the remote server
     [echo] phing symlink         - create symlinks to the core - /t3core/typo3_src-4.5.git
     [echo] phing typo3temp       - create typo3temp directory
     [echo] phing uploads         - fetch uploads folder
     [echo] phing fileadmin       - fetch fileadmin
     [echo] phing user_upload     - fetch user_upload folder
     [echo] phing clean           - delete unnecessary files
     [echo] 
     [echo] ---------------------------------------------
     [echo] Convenience tasks
     [echo] ---------------------------------------------
     [echo] phing initialize     - dump, localconf, htaccess, symlink, typo3temp, uploads, user_upload
     [echo] phing initialize6    - dump6, htaccess, symlink, uploads, fileadmin
     [echo] phing help           - display this help message
     [echo] 
     [echo] ---------------------------------------------
     [echo] Possible option
     [echo] ---------------------------------------------
     [echo] -DdryRun=true        - will display the command to be executed
