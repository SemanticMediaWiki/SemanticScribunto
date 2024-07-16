#! /bin/bash

MW_BRANCH=$1
EXTENSION_NAME=$2

## install core
wget https://github.com/wikimedia/mediawiki/archive/${MW_BRANCH}.tar.gz -nv

tar -zxf $MW_BRANCH.tar.gz
mv mediawiki-$MW_BRANCH mediawiki
cd $MW_ROOT

composer install
php maintenance/install.php --dbtype sqlite --dbuser root --dbname mw --dbpath $(pwd) --pass AdminPassword WikiName AdminUser

cat <<EOT >> composer.local.json
{
	"extra": {
		"merge-plugin": {
			"merge-dev": true,
			"include": [
				"extensions/${EXTENSION_NAME}/composer.json"
			]
		}
	}
}
EOT

## Vector
cd skins
#	most mw dumps ship with empty skin directories
[[ -e Vector ]] && rm -rf Vector
git clone --branch ${MW_BRANCH} https://github.com/wikimedia/Vector.git


## Scribunto
cd ../extensions
wget https://github.com/wikimedia/mediawiki-extensions-Scribunto/archive/${MW_BRANCH}.tar.gz
tar -zxf ${MW_BRANCH}.tar.gz
[[ -e Scribunto ]] && rm -rf Scribunto
mv mediawiki-extensions-Scribunto* Scribunto
cd ..


## extend LocalSettings.php
echo 'error_reporting(E_ALL| E_STRICT);' >> LocalSettings.php
echo 'ini_set("display_errors", 1);' >> LocalSettings.php
echo '$wgShowExceptionDetails = true;' >> LocalSettings.php
echo '$wgShowDBErrorBacktrace = true;' >> LocalSettings.php
echo '$wgDevelopmentWarnings = true;' >> LocalSettings.php

echo '$wgArticlePath = $wgScriptPath . "/$1";' >> LocalSettings.php
echo '$wgEnableUploads = true;' >> LocalSettings.php

echo 'wfLoadSkin( "Vector" );' >> LocalSettings.php
echo '$wgDefaultSkin = "vector-2022";' >> LocalSettings.php

echo 'wfLoadExtension( "SemanticMediaWiki" );' >> LocalSettings.php
echo 'wfLoadExtension( "Scribunto" );' >> LocalSettings.php
echo 'enableSemantics( "reynolds.serenity.verse" );' >> LocalSettings.php

## add loading of $EXTENSION_NAME
echo 'wfLoadExtension( "'$EXTENSION_NAME'" );' >> LocalSettings.php
