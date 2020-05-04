#!/bin/bash

# Arguments:
# 1. container name

docker ps -a \
	--filter name=^/$1$ \
	--no-trunc \
	--format "{{json . }}"
