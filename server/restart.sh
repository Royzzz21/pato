#!/bin/bash

source ./_config.sh
echo "PHP_BIN=$PHP_BIN"




./stop.sh

############## sleep 5
echo 'starting servers..'
sleep 5


############## move and run
cd $WWW_DIR

./start_node.sh
./start_php.sh
