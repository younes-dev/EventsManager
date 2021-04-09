FROM phpdockerio/php74-fpm:latest
WORKDIR "/application"
#How do I prevent root access to my docker container
#https://stackoverflow.com/q/57731428

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install  php7.4-mysql php7.4-xdebug php7.4-yaml \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Install git
RUN apt-get update \
    && apt-get -y install git \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

#install MakeFile
RUN apt-get update && apt-get install make

#https://www.freecodecamp.org/news/docker-easy-as-build-run-done-e174cc452599/

###################################################################
#https://github.com/mtxr/docker-php-nginx
#https://github.com/TrafeX/docker-php-nginx/blob/master/Dockerfile
#https://github.com/bitnami/bitnami-docker-php-fpm
###################################################################

#https://stackoverflow.com/a/54446072
#https://stackoverflow.com/a/60102194
#https://www.thegeekdiary.com/run-docker-as-a-non-root-user/
#https://www.thegeekdiary.com/run-docker-as-a-non-root-user/
#https://superuser.com/questions/906692/why-i-cant-access-to-this-directory-after-that-i-use-the-chown-command
#https://stackoverflow.com/a/24877523
#https://superuser.com/questions/701248/chown-invalid-user-www-datawww-data

#check users: sudo ls -al /var/www

# Create a user group 'GroupUser'
# Create a user 'younes' under 'GroupUser'
#RUN addgroup demo && adduser -DH -G demo younes
#RUN addgroup -S demo && adduser -S -D -h demo younes

# Chown all the files to the app user.
#RUN chown -R younes:demo /usr/application

# Switch to 'younes'
#USER younes


## Create a user group 'xyzgroup'
#RUN addgroup xyzgroup
#
## Create a user 'appuser' under 'xyzgroup'
#RUN adduser -S -D -h /usr/app/src appuser xyzgroup
#
## Chown all the files to the app user.
#RUN chown -R appuser:xyzgroup /usr/application
#
## Switch to 'appuser'
#USER appuser

######################################
# Create a user group 'xyzgroup'
#RUN addgroup xyzgroup
#
## Create a user 'appuser' under 'xyzgroup'
#RUN adduser -S -D -h /usr/app/src www-data xyzgroup
#
## Chown all the files to the app user.
#RUN chown -R www-data:xyzgroup /usr/app
#
## Switch to 'www-data'
#USER www-data

######################################
# setup folder before switching to user
#RUN mkdir /volume_data
#RUN chown postgres:postgres /volume_data
#USER postgres

#RUN useradd -ms /bin/bash  vault
#USER vault
#WORKDIR /usr/local/bin/vault

#RUN addgroup -G 1001 -S appuser && adduser -u 1001 -S appuser  -G appuser

#RUN addgroup usergroup
## Add a new user "john" with user id 8877
#RUN useradd -u 8877 younes
#RUN mkdir /usr/application
#RUN chown -R younes:usergroup /usr/application
#
## Change to non-root privilege
#USER younes
