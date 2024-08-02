#!/bin/sh

echo `cat /proc/uptime | cut -d " " -f1 | cut -d "." -f1` > /www/misc.vgsoftware.com/uptime
