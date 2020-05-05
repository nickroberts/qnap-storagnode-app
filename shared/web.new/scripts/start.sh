#!/bin/bash

export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin

# Arguments:
# 1. container name
# 2. image name & tag
# 3. hostname and port
# 4. identity path
# 5. wallet address
# 6. storage allocation
# 7. email address
# 8. storage directory

PORT=`echo "$3" | cut -f3 -d:`
STORAGE="$((${6} + 1000000000))"

docker stop ${1}
docker rm -f ${1}

docker run -d \
  --restart no \
  -p ${PORT}:28967 \
  -p 14002:14002 \
  -e WALLET=${5} \
  -e EMAIL=${7} \
  -e ADDRESS=${3} \
  -e STORAGE=${STORAGE} \
  -v ${4}:/app/identity \
  -v ${8}:/app/config \
  --name ${1} \
  ${2} 2>&1
