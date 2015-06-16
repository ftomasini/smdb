#!/bin/bash

TIMEOUT=60
#Para criar a chave xml = http://hostname/api/?type=keygen&user=USERNAME&password=PASSWORD
KEY=LUFRPT1NaXpWbWRKdEx6bENZL1BOSkpJZEM0cFVkUzA9NVRHaXpmNDBIRmwvRFRBNWF0KzkrdE5QQXFMNDhZOXZpRGk4cFVXQ2xIYz0=
USUARIO=$1
IP=$2
LOG=/var/log/paloalto
IPPALOALTO=pa-asav00.asav.org.br

cat > $LOG/$USUARIO.xml << EOF
<uid-message>
<version>1.0</version>
<type>update</type>
<payload>
<login>
<entry name="$USUARIO" ip="$IP" timeout="$TIMEOUT" />
</login>
</payload>
</uid-message>
EOF

# ENVIA O XML PARA O PALOALTO
/usr/bin/wget -q -O /dev/null --no-check-certificate --tries=3 --post-file $LOG/$USUARIO.xml "https://$IPPALOALTO/api/?type=user-id&action=set&client=wget&file-name=$LOG/$USUARIO.xml&key=$KEY" &
