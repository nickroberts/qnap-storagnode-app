#!/bin/bash

export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin

# Arguments:
# 1. container name

docker exec `docker ps -l -q -f name=^/$1$` sh -c "/app/storagenode version" 2>&1
