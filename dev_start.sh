#!/usr/bin/env bash
mkdir logs

chmod -R 777 logs/.

cp env_files/env_development ./env

docker-compose down

composer install

docker-compose up -d

>&2 echo "App is ready"