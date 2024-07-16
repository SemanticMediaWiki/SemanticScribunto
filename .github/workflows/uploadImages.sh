#! /bin/bash

cd $MW_ROOT

## inject Resources
php maintenance/importImages.php extensions/BootstrapComponents/tests/resources/ png
php maintenance/rebuildall.php
php maintenance/runJobs.php --quiet

cd ..
