#!/bin/sh
ONLINE=0;
OFFLINE=0;

ping -c1 www.google.com > /dev/null 2> /dev/null
if [ "$?" -eq "0" ];
then
    ONLINE=60;
else
    ping -c1 www.yahoo.com > /dev/null 2> /dev/null
    if [ "$?" -eq "0" ];
	then
	ONLINE=60;
	else
	ping -c1 www.sunet.se > /dev/null 2> /dev/null
	if [ "$?" -eq "0" ];
	    then
	    ONLINE=60;
	    else
	    OFFLINE=60;
	    fi
	fi
fi

echo "Offline: $OFFLINE";
echo "Online: $ONLINE";
