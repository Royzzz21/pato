#!/bin/bash

export NODE_PATH="../www/node_modules"

source ./_config.sh
echo "PHP_BIN=$PHP_BIN"

node ./chat.js &
