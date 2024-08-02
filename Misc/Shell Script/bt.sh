#!/bin/sh
#bttv
umask 000
args="--minport 45000 --maxport 45100 --saveas_style 3 --close_with_rst 1 --display_interval 60 --torrent_dir /share/torrent/downloading --max_upload_rate 0 --max_uploads 400"
exec /usr/bin/launchmany-console $args > /share/torrent/torrent.log
