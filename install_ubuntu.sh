#!/usr/bin/env bash

if [ $(uname -s) == "Linux" ]; then

    if ! [ -x "$(which docker)" ]; then
        sudo apt-get update
        sudo apt-get -y install docker.io python-pip
        sudo pip install --upgrade pip
        sudo pip install docker-compose
    fi

    docker-compose up
fi