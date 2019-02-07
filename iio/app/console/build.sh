#!/bin/bash

# -- Get the Build Type :
TYPE=$1

# -- Run Style Parser:
bash run.sh parse-styles;

if [ "$TYPE" == "all" ]; then

  # -- Run Application Build Parser:
  bash run.sh build-app sync;

fi;
