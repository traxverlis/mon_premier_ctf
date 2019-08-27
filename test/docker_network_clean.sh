#!/bin/bash

# Kill all running containers
docker kill $(docker ps -aq)

# Delete all stopped containers
docker rm $(docker ps -aq)

# Delete all docker networks
docker network rm $(docker network ls | awk '{print $(2)}' | grep Net_)
docker network prune -f

echo If passwords are changed dont forget to :
echo docker rmi webserver_webserver_mysql

# restart from scratch
./go_first_install_webserver_run