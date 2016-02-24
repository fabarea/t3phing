My Phing tasks for TYPO3 CMS. Save me from some tedious work...

Installation
------------

This Phing will best run in the context of the [Speciality Distribution](https://github.com/Ecodev/bootstrap_package) for TYPO3 CMS.

I normally installed as follows, next to the Document Root (htdocs).

```

	# Prepare the ground
	cd /sites/domain.tld
	mkdir build;

	# Fetch the source Luke
	# Alternatively use https://github.com/fabarea/t3phing.git as URL if you don't have an account on github.com
	git clone git@github.com:fabarea/t3phing.git build/Phing

	# Copy the sample build.xml
	cp build/Phing/Sample/Build.xml build.xml

	# Check it works correctly and follows the wizard.
	phing
```

Usage:
------

Will display the usage of the phing:

```

	$> phing help (default)

     [echo] Usage of this Phing:
     [echo]
     [echo] phing help            - display this help message
     [echo]
     [echo] ---------------------------------------------
     [echo] Handle cache
     [echo] ---------------------------------------------
     [echo] phing c               - Clear cache, depth 1: typo3temp/Cache files
     [echo] phing cc              - Clear cache, depth 2: all typo3temp files
     [echo] phing ccc             - Clear cache, depth 3: all typo3temp files + database
     [echo] phing warmup          - Call Frontend to generate necessary files
     [echo]
     [echo] ---------------------------------------------
     [echo] Import website locally
     [echo] ---------------------------------------------
     [echo] phing show            - Show Phing current configuration
     [echo] phing import-dump     - Fetch the database and build it again locally for TYPO3 6.0
     [echo] phing htaccess        - Fetch the htaccess from the remote server
     [echo] phing uploads         - Fetch uploads folder
     [echo] phing fileadmin       - Fetch fileadmin
     [echo] phing user_upload     - Fetch user_upload folder
     [echo]
     [echo] phing d               - import-dump shortcut
     [echo]
     [echo] ---------------------------------------------
     [echo] Possible option
     [echo] ---------------------------------------------
     [echo] -DdryRun=true         - Only display the command to be executed
```