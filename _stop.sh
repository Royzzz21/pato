#!/bin/sh


############## kill all node
echo "killing nodes"
killall node
pkill node


############## kill all php and node
# mac
echo "killing php and nodes"
ps -ef | awk '/php/ && !/awk/ {print $2}' | xargs kill -9
ps -ef | awk '/node/ && !/awk/ {print $2}' | xargs kill -9

##############
echo 'stopping servers..'