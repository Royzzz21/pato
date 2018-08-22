#!/bin/bash

source ./_config.sh
echo "PHP_BIN=$PHP_BIN"

############## move and run
#cd $WWW_DIR

nohup $PHP_BIN ./make_tx.php &
nohup $PHP_BIN ./place_order.php &
nohup $PHP_BIN ./make_chart.php &
