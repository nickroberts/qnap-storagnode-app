#!/bin/bash

export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin

# Arguments:
# 1. container name

docker stop ${1} 2>&1
docker rm -f ${1} 2>&1
