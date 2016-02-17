#!/usr/bin/env bash
## *************************************************************************
#   Deploy Magento 2 in development mode.
## *************************************************************************

##
#   Working variables and hardcoded configuration.
##
CUR_DIR="$PWD"
DIR="$( cd "$( dirname "$0" )" && pwd )"
#   Load deployment configuration.
. $DIR/deploy_cfg.sh
# Create shortcuts
M2_ROOT=$DIR/work
DHOME=$DIR/deploy
COMPOSER_MAIN=$M2_ROOT/composer.json
COMPOSER_UNSET=$DHOME/composer_unset.json
COMPOSER_OPTS=$DHOME/composer_opts.json

##
#   Deployment.
##
echo "\nClean up application's root folder ($M2_ROOT)..."
if [ -d "$M2_ROOT" ]
then
    rm -fr $M2_ROOT
    mkdir -p $M2_ROOT
else
    mkdir -p $M2_ROOT
fi
cd $M2_ROOT


echo "\nCreate M2 CE project in '$M2_ROOT' using 'composer install'..."
composer create-project --repository-url=https://repo.magento.com/ magento/project-community-edition $M2_ROOT


echo "\nAdd initial dependencies to M2 CE project..."
composer require flancer32/php_data_object:dev-master


echo "\nFilter original '$COMPOSER_MAIN' on '$COMPOSER_UNSET' set and populate with additional options from '$COMPOSER_OPTS'..."
php $DIR/deploy/merge_json.php $COMPOSER_MAIN $COMPOSER_UNSET $COMPOSER_OPTS


echo "\nUpdate M2 CE project with additional options..."
cd $M2_ROOT
composer update


echo "\nDrop M2 database $DB_NAME..."
if [ -z $DB_PASS ]; then
    MYSQL_PASS=""
    MAGE_DBPASS=""
else
    MYSQL_PASS="--password=$DB_PASS"
    MAGE_DBPASS="--db-password=""$DB_PASS"""
fi
mysqladmin -f -u"$DB_USER" $MYSQL_PASS -h"$DB_HOST" drop "$DB_NAME"
mysqladmin -f -u"$DB_USER" $MYSQL_PASS -h"$DB_HOST" create "$DB_NAME"


echo "\n(Re)install Magento using database '$DB_NAME' (connecting as '$DB_USER')."
# Full list of the available options:
# http://devdocs.magento.com/guides/v2.0/install-gde/install/cli/install-cli-install.html#instgde-install-cli-magento
php $M2_ROOT/bin/magento setup:install  \
--admin-firstname="$ADMIN_FIRSTNAME" \
--admin-lastname="$ADMIN_LASTNAME" \
--admin-email="$ADMIN_EMAIL" \
--admin-user="$ADMIN_USER" \
--admin-password="$ADMIN_PASSWORD" \
--base-url="$BASE_URL" \
--backend-frontname="$BACKEND_FRONTNAME" \
--language="$LANGUAGE" \
--currency="$CURRENCY" \
--timezone="$TIMEZONE" \
--use-rewrites="$USE_REWRITES" \
--use-secure="$USE_SECURE" \
--use-secure-admin="$USE_SECURE_ADMIN" \
--admin-use-security-key="$ADMI_USE_SECURITY_KEY" \
--session-save="$SESSION_SAVE" \
--cleanup-database \
--db-host="$DB_HOST" \
--db-name="$DB_NAME" \
--db-user="$DB_USER" \
$MAGE_DBPASS \
# 'MAGE_DBPASS' should be placed on the last position to prevent failures if this var is empty.


if [ -z "$LOCAL_OWNER" ] || [ -z "$LOCAL_GROUP" ]; then
    echo "Skip file system ownership and permissions setup."
else
    ## http://devdocs.magento.com/guides/v2.0/install-gde/prereq/integrator_install.html#instgde-prereq-compose-access
    echo "Set file system ownership ($LOCAL_OWNER:$LOCAL_GROUP) and permissions..."
    chown -R $LOCAL_OWNER:$LOCAL_GROUP $M2_ROOT
    find $M2_ROOT -type d -exec chmod 770 {} \;
    find $M2ROOT -type f -exec chmod 660 {} \;
    chmod -R g+w $M2_ROOT/var
    chmod -R g+w $M2_ROOT/pub
    chmod u+x $M2_ROOT/bin/magento
    chmod -R go-w $M2_ROOT/app/etc
fi

# Return back
cd $CUR_DIR