#!/bin/sh

pid=$(ps axc|grep launchmany|awk '{print $1}')
kill -hup $pid
if [ $? -eq 0 ]
then
  # wait a bit to give it time to clean up
  # (it seems that this one, unlike launchmany-console, exits straight away,
  # so there's no point in waiting for long)
  sleep 2s
  # make sure it's dead
  kill -9 $pid
fi

>| /share/torrent/torrent.log

exec /home/viktor/dev/shell/torrent/bt.sh &
