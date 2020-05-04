#!/bin/bash

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

docker run -d \
  --restart no \
  -p ${PORT}:28967 \
  -p 14002:14002 \
  -e WALLET=\"${5}\" \
  -e EMAIL=\"${7}\" \
  -e ADDRESS=\"${3}\" \
  -e STORAGE=\"${6}\" \
  -v ${4}:/app/identity \
  -v ${8}:/app/config \
  --name ${1} \
  ${2}
