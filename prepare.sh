#!/bin/bash

# evaluate script dir
SCRIPT_DIR="$( cd "$( dirname "$0" )" && pwd )"

# set default env params
DB_HOST="${DB_HOST:-localhost}"
DB_PORT="${DB_PORT:-3306}"
BASE_STORAGE_DIR="${BASE_STORAGE_DIR:-$SCRIPT_DIR/storage}"

# check if mandatory env params isset
if [ -z "$DB_NAME"  ] || [ -z "$DB_USER" ] || [ -z "$DB_PASSWORD" ];
  then echo "Environment is not setup properly";
  exit 1;
fi

echo 'Prepare database structure'

echo "Create database - $DB_NAME"
mysql -u "$DB_USER" -p"$DB_PASSWORD" -h "$DB_HOST" --port="$DB_PORT" -e "DROP DATABASE IF EXISTS $DB_NAME; CREATE DATABASE $DB_NAME"

echo "Run SQL scripts"
for entry in "$SCRIPT_DIR/database/"*
do
  echo "Run $entry"
  mysql -u "$DB_USER" -p"$DB_PASSWORD" -h "$DB_HOST" --port="$DB_PORT" "$DB_NAME" < "$entry"
done


echo "Create symlinc $BASE_STORAGE_DIR/public/images -> $SCRIPT_DIR/public/images"
rm -rf "$SCRIPT_DIR/public/images"
ln -s "$BASE_STORAGE_DIR/public/images" "$SCRIPT_DIR/public/images"