#!/bin/bash

# -- Get Type of Console Request :
TYPE=$2;

# -- Run the PHP Service Request :
php service/$1.php

# -- Run Follow Up Requests based on
# -- If a Type was set :
if [ "$TYPE" == "sync" ]; then

  # -- Parse the JSON Config File :
  CONFIG=$(<../../config/app.json)

  # -- Grab the IIO Path and Public Path :
  # -- NOTE: jq needs to be installed on the server :
  # ----- to install: apt-get install jq
  IIO=$(echo "$CONFIG" | jq -r '."sys-conf"."iio-path"');
  PUB=$(echo "$CONFIG" | jq -r '."sys-conf"."public-path"');

  # -- Move the Cached file path into the public
  # -- Web folder :
  cp -r $IIO/cache/. $PUB;

fi;
