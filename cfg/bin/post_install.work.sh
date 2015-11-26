#!/bin/sh
##
#   Setup Magento instance after install with Composer.
#   (all placeholders ${...} should be replaced by real values from template.vars.work.json' file)
##

# type of the deployment (skip some steps when app is deployed in TRAVIS CI, $DEPLOYMENT_TYPE='test')
DEPLOYMENT_TYPE=${DEPLOYMENT_TYPE}
# local specific environment
LOCAL_ROOT=${LOCAL_ROOT}    # root folder for the deployed instance
MAGE_ROOT=$LOCAL_ROOT       # root folder for Magento app (in common case can be other than LOCAL_ROOT)
# The owner of the Magento file system:
#   * Must have full control (read/write/execute) of all files and directories.
#   * Must not be the web server user; it should be a different user.
# Web server:
#   * must be a member of the '${LOCAL_GROUP}' group.
LOCAL_OWNER=${LOCAL_OWNER}
LOCAL_GROUP=${LOCAL_GROUP}
# DB connection params
DB_HOST=${CFG_DB_HOST}
DB_NAME=${CFG_DB_NAME}
DB_USER=${CFG_DB_USER}
# use 'skip_password' to connect to server w/o password.
DB_PASS=${CFG_DB_PASSWORD}
if [ "$DB_PASS" = "skip_password" ]; then
    MYSQL_PASS=""
    MAGE_DBPASS=""
else
    MYSQL_PASS="--password=$DB_PASS"
    MAGE_DBPASS="--db-password=""${CFG_DB_PASSWORD}"""
fi



##
echo "Restore write access on folder 'work/htdocs/app/etc' for owner when launches are repeated."
##
if [ -d "$MAGE_ROOT/app/etc" ]
then
    chmod -R go+w $MAGE_ROOT/app/etc
fi



##
echo "Drop database $DB_NAME."
##
mysqladmin -f -u"$DB_USER" $MYSQL_PASS -h"$DB_HOST" drop "$DB_NAME"
mysqladmin -f -u"$DB_USER" $MYSQL_PASS -h"$DB_HOST" create "$DB_NAME"



##
echo "(Re)install Magento using database '$DB_NAME' (connecting as '$DB_USER')."
##

# Full list of the available options:
# http://devdocs.magento.com/guides/v2.0/install-gde/install/cli/install-cli-install.html#instgde-install-cli-magento

php $MAGE_ROOT/bin/magento setup:install  \
--admin-firstname="${CFG_ADMIN_FIRSTNAME}" \
--admin-lastname="${CFG_ADMIN_LASTNAME}" \
--admin-email="${CFG_ADMIN_EMAIL}" \
--admin-user="${CFG_ADMIN_USER}" \
--admin-password="${CFG_ADMIN_PASSWORD}" \
--base-url="${CFG_BASE_URL}" \
--backend-frontname="${CFG_BACKEND_FRONTNAME}" \
--language="${CFG_LANGUAGE}" \
--currency="${CFG_CURRENCY}" \
--timezone="${CFG_TIMEZONE}" \
--use-rewrites="${CFG_USE_REWRITES}" \
--use-secure="${CFG_USE_SECURE}" \
--use-secure-admin="${CFG_USE_SECURE_ADMIN}" \
--admin-use-security-key="${CFG_ADMI_USE_SECURITY_KEY}" \
--session-save="${CFG_SESSION_SAVE}" \
--cleanup-database \
--db-host="${CFG_DB_HOST}" \
--db-name="${CFG_DB_NAME}" \
--db-user="${CFG_DB_USER}" \
$MAGE_DBPASS \
# 'MAGE_DBPASS' should be placed on the last position to prevent failures if this var is empty.


if [ "$DEPLOYMENT_TYPE" = "test" ]; then
    echo "Skip file system ownership and permissions setup."
else
    ##
    echo "Set file system ownership and permissions."
    ##
    chown -R $LOCAL_OWNER:$LOCAL_GROUP $MAGE_ROOT
    find $MAGE_ROOT -type d -exec chmod 770 {} \;
    find $MAGE_ROOT -type f -exec chmod 660 {} \;
    chmod -R g+w $MAGE_ROOT/var
    chmod -R g+w $MAGE_ROOT/pub
    chmod u+x $MAGE_ROOT/bin/magento
    chmod -R go-w $MAGE_ROOT/app/etc
fi



##
echo "Post installation setup is done."
##