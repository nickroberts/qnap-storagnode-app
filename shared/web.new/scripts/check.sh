#!/bin/bash

export PATH=$PATH:/share/CACHEDEV1_DATA/.qpkg/container-station/bin

# Arguments:
# 1. container name

docker ps -a \
	--filter name=^/$1$ \
	--no-trunc \
	--format "{{json . }}" 2>&1
