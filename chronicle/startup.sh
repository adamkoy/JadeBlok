#!/bin/bash


RETRIES=10

cd /var/www/chronicle/
pwd && ls -lh

php bin/install.php --pgsql \
	--host $POSTGRES_HOST \
	--port 5432 \
	--database chronicle \
	--username $POSTGRES_USER \
	--password $POSTGRES_PASSWORD \
pwd && ls -lh

until php bin/make-tables.php
do
	if [[ $RETRIES -le 0 ]]
	then
		>&2 echo "Postgress did not respond in time - exiting"
		exit 1
	fi
	>&2 echo "Postgress not yet responding - sleeping"
	RETRIES=$((RETRIES-1))
	sleep 5
done

if [[ ! -z "$CHRONICLE_ADMIN_ID" ]]
then
	#This won't work if they already exist, but we really don't care.
	php bin/create-client.php \
		--publickey=${CHRONICLE_ADMIN_PUBLICKEY:?Missing CHRONICLE_ADMIN_PUBLICKEY} \
		--comment=${CHRONICLE_ADMIN_COMMENT:-"Container created admin"} \
		--clientid=${CHRONICLE_ADMIN_ID:?Missing CHRONICLE_ADMIN_ID} \
		--administrator
fi

apache2-foreground