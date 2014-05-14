My Phing tasks for TYPO3 CMS. Save me from some tedious work...

Usage:
------

Will display the usage of the phing:

::

	$> phing help (default)


     [echo] Usage of this Phing:
     [echo]
     [echo] phing help            - display this help message
     [echo]
     [echo] ---------------------------------------------
     [echo] Compile Assets (css, js, images, ...)
     [echo] ---------------------------------------------
     [echo] phing asset-build     - Package assets for this website. This will compile css and js files and package them
     [echo] phing asset-watch     - Watch you assets and compile as you edit. Equivalent to:
     [echo]                         cd htdocs/typo3conf/ext/speciality; grunt watch
     [echo] phing bower-install   - Install all Web Components configured in EXT:speciality/bower.json
     [echo] phing bower-update    - Update all Web Components configured in EXT:speciality/bower.json
     [echo] phing bower-status    - Check whether Web Components must be updated
     [echo] phing check-system    - Check dependencies are correctly met. This will ensure Phing to work.
     [echo]
     [echo] ---------------------------------------------
     [echo] Handle cache
     [echo] ---------------------------------------------
     [echo] phing clear_cache     - Flush cached files along with database cache (depth 3)
     [echo] phing warmup          - Call Frontend to generate necessary files
     [echo]
     [echo] phing c               - Clear cache, depth 1: typo3temp/Cache files
     [echo] phing cc              - Clear cache, depth 2: all typo3temp files
     [echo] phing ccc             - Clear cache, depth 3: all typo3temp files + database
     [echo]
     [echo] ---------------------------------------------
     [echo] Import website locally
     [echo] ---------------------------------------------
     [echo] phing show            - Show Phing current configuration
     [echo] phing import-dump     - Fetch the database and build it again locally for TYPO3 6.0
     [echo] phing htaccess        - Fetch the htaccess from the remote server
     [echo] phing symlink         - Create symlinks to the core, current value "/t3core/typo3_src-6.1"
     [echo] phing uploads         - Fetch uploads folder
     [echo] phing fileadmin       - Fetch fileadmin
     [echo] phing user_upload     - Fetch user_upload folder
     [echo]
     [echo] phing d               - import-dump
     [echo] phing initialize6     - import-dump, htaccess, symlink, uploads, fileadmin
     [echo] phing reset           - import-dump, clear_cache, warmup
     [echo]
     [echo] ---------------------------------------------
     [echo] Commands on remote
     [echo] ---------------------------------------------
     [echo] phing status             - git remote status
     [echo] phing diff               - git remote diff
     [echo]
     [echo] phing st                 - Shortcut of "phing status"
     [echo] phing df                 - Shortcut of "phing diff"
     [echo]
     [echo] ---------------------------------------------
     [echo] Possible option
     [echo] ---------------------------------------------
     [echo] -DdryRun=true        - will display the command to be executed


Installation
------------

This Phing is meant to be run and installed in the context of the `Speciality Distribution`_ for TYPO3 CMS.

.. _Speciality Distribution: https://github.com/Ecodev/bootstrap_package


