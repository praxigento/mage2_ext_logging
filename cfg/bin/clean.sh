#!/usr/bin/env bash
##
#   Clear Magento files if Magento app is deployed into current directory
#   (https://github.com/magento/magento2/issues/2433)
#
#   (all placeholders ${...} should be replaced by real values from ./live/template.json file)
##

# local specific environment
LOCAL_ROOT=${LOCAL_ROOT}

rm -fr $LOCAL_ROOT/app
rm -fr $LOCAL_ROOT/bin/.htaccess
rm -fr $LOCAL_ROOT/bin/magento
rm -fr $LOCAL_ROOT/dev
rm -fr $LOCAL_ROOT/lib
rm -fr $LOCAL_ROOT/phpserver
rm -fr $LOCAL_ROOT/pub
rm -fr $LOCAL_ROOT/setup
rm -fr $LOCAL_ROOT/var
rm -fr $LOCAL_ROOT/vendor
rm -fr $LOCAL_ROOT/.htaccess
rm -fr $LOCAL_ROOT/.htaccess.sample
rm -fr $LOCAL_ROOT/.php_cs
rm -fr $LOCAL_ROOT/.travis.yml
rm -fr $LOCAL_ROOT/CHANGELOG.md
rm -fr $LOCAL_ROOT/CONTRIBUTING.md
rm -fr $LOCAL_ROOT/CONTRIBUTOR_LICENSE_AGREEMENT.html
rm -fr $LOCAL_ROOT/COPYING.txt
rm -fr $LOCAL_ROOT/Gruntfile.js
rm -fr $LOCAL_ROOT/index.php
rm -fr $LOCAL_ROOT/LICENSE.txt
rm -fr $LOCAL_ROOT/LICENSE_AFL.txt
rm -fr $LOCAL_ROOT/nginx.conf.sample
rm -fr $LOCAL_ROOT/package.json
rm -fr $LOCAL_ROOT/php.ini.sample
rm -fr $LOCAL_ROOT/composer.lock

echo "Magento 2 files are removed."