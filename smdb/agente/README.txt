https://github.com/tcdent/php-restclient
postgresql-server-dev-9.3

http://blog.ijasoneverett.com/2013/02/rest-api-a-simple-php-tutorial/

https://varunver.wordpress.com/2013/02/13/php-restclient-and-exception-handling/

#http://stackoverflow.com/questions/2036654/run-php-script-as-daemon-process

#Create a new script file at /etc/init/myphpworker.conf. Here is an example:

#Starting & stopping your daemon:

#sudo service myphpworker start
#sudo service myphpworker stop

#Check if your daemon is running:

#sudo service myphpworker status



# Info
description "My PHP Worker"
author      "Jonathan"

# Events
start on startup
stop on shutdown

# Automatically respawn
respawn
respawn limit 20 5

# Run the script!
# Note, in this example, if your PHP script returns
# the string "ERROR", the daemon will stop itself.
script
    [ $(exec /usr/bin/php -f /home/script.php) = 'ERROR' ] && ( stop; exit 1; )
end script





