#!/bin/bash


############## kill all node
killall node
pkill node


############## kill all php and node
# mac
ps -ef | awk '/php / && !/awk/ {print $2}' | xargs kill -9
ps -ef | awk '/node/ && !/awk/ {print $2}' | xargs kill -9

############## sleep 5
echo 'stopping servers..'