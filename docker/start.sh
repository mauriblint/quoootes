docker stop $(docker ps -a -q)
docker-compose up quoootes_nginx quoootes_php7 quoootes_mariadb
