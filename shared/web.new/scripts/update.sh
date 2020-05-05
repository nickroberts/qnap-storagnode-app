#!/bin/bash

export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin

# Arguments:
# 1. image name & tag

docker pull ${2} 2>&1
