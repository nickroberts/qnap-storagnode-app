#!/bin/bash

# Arguments:
# 1. container name

docker stop ${1}
docker rm -f ${1}
