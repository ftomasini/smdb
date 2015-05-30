#!/bin/sh
# setsebool -P httpd_can_network_connect_db 1

hostname=""
while [ ! -n "$hostname" ]; do
	echo -n "Please enter the host name of the application (localhost): "
	read hostname
	if [ ! -n "$hostname" ]; then
		hostname="localhost"
	fi
done
echo Host name will be \"$hostname\"

folder=""
while [ -z "$folder" ]; do
	folder=`basename \`pwd\``
	echo -n "Please enter the folder name inside the host this app is located in ($folder): "
	read folder
	if [ -z "$folder" ]; then
		folder=`basename \`pwd\``
	fi
done
echo Folder name will be \"$folder\"
echo
echo Application url: http://$hostname/$folder

PG_PATH=`dirname \`which createdb\``

bin_path=""
while [ ! -x "$bin_path/createdb" ]; do
	echo -n "Path to PostgreSQL binaries ($PG_PATH)? "
	read bin_path
	if [ ! -n "$bin_path" ]; then
		bin_path=$PG_PATH
	fi
	if [ ! -x "$bin_path/createdb" ]; then
		echo wrong path: $bin_path
	fi
done

echo Binaries will be used in \"$bin_path\"

dbhost=""
while [ -z "$dbhost" ]; do
	dbhost="localhost"
	echo -n "Database server host ($dbhost)? "
	read dbhost
	if [ ! -n "$dbhost" ]; then
		dbhost="localhost"
	fi
done
echo "Connection will go to host $dbhost"

dbport=""
while [ -z "$dbport" ]; do
	dbport=5432
	echo -n "Database server port ($dbport)? "
	read dbport
	if [ ! -n "$dbport" ]; then
		dbport=5432
	fi
done
echo "Connection will work through port $dbport"

dbname=""
while [ ! -n "$dbname" ]; do
	echo -n "Please enter the database name to create (pgwatch): "
	read dbname
	if [ ! -n "$dbname" ]; then
		dbname="pgwatch"
	fi
done
echo Database name will be \"$dbname\"

dbpass=""
while [ ! -n "$dbpass" ]; do
	echo -n "Please enter the database user's password: "
	read dbpass
done
echo Database password will be \"$dbpass\"

createuser=""
while [ ! -n "$createuser" ]; do
	echo -n "Create new user to use pgwatch (Y/n)? "
	read createuser
	if [ "$createuser" = "" ] || [ "$createuser" = "y" ] || [ "$createuser" = "Y" ]; then
		createuser="y"
		echo New user will be created.
	else
		createuser="n"
		echo Existing user will be used.
	fi
done

dbuser=""
while [ ! -n "$dbuser" ]; do
	echo -n "Please enter the database user to connect by (pgwatch): "
	read dbuser
	if [ ! -n "$dbuser" ]; then
		dbuser="pgwatch"
	fi
done
echo Database user will be \"$dbuser\"

sed config/config.ini.template -e "s/__DBUSER__/$dbuser/g" \
	| sed -e "s/__DBPASS__/$dbpass/g" \
	| sed -e "s/__DBNAME__/$dbname/g" \
	| sed -e "s/__DBPORT__/$dbport/g" \
	| sed -e "s/__DBHOST__/$dbhost/g" \
	| sed -e "s/__HOSTNAME__/$hostname/g" \
	| sed -e "s/__TMPDIR__/\/tmp\//g" \
	| sed -e "s/__FOLDER__/$folder/g" > config/config.ini

###################################

if [ "$createuser" = "y" ]; then
	echo Creating user $dbuser...
	$bin_path/createuser -U postgres -h localhost $dbuser -l
fi

echo Creating database $dbname with owner $dbuser...
$bin_path/createdb -h localhost -U postgres --owner=$dbuser $dbname

echo Creating database content...
sed sql/setup.sql.template -e "s/__DBUSER__/$dbuser/g" | $bin_path/psql -U $dbuser -h localhost $dbname

php_bin=`which php5`
if [ ! -n "$php_bin" ]; then
	php_bin=`which php`
fi
if [ -n "$php_bin" ]; then
	php_ver=`$php_bin -version | head -1 | cut -d ' ' -f 2`;
	if [ "$php_ver" \< "5" ]; then
		echo "Warning: PHP ($php_bin) version must be at least 5 (this is $php_ver)" >2
	fi
else
	echo "Warning: PHP is not accessible in PATH!" > 2
	exit 0;
fi

chmod 777 templates_c

echo "Remember to add the following row to cron of user www-data (or so):"
echo "0 * * * * cd `pwd` && $php_bin util/getraw_d.php"

echo
echo You can acces now the application on http://$hostname/$folder
echo


echo Ready.