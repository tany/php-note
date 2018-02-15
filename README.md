README
======

## Description

MongoDB Tool + α

## Install

~~~
# rpm -Uvh http://ftp.iij.ad.jp/pub/linux/fedora/epel/7/x86_64/Packages/e/epel-release-7-11.noarch.rpm
# rpm -Uvh http://rpms.famillecollet.com/enterprise/remi-release-7.rpm

# yum install -y --enablerepo=remi,remi-php70 \
    php70 php70-php-devel \
    php70-php-xml php70-php-yaml php70-php-mbstring \
    php70-php-pear php70-php-opcache php70-php-apcu php70-php-fpm \
    php70-php-pecl-mongodb \
    cyrus-sasl-devel

# vi /etc/opt/remi/php70/php-fpm.d/www.conf
~~~

~~~
listen = /var/run/php-fpm.sock
listen.owner = nginx
listen.group = nginx
~~~

~~~
# git clone git@github.com:tany/php-note.git /var/www/php-note
# ln -s /var/www/php-note/conf/server/nginx/note.conf /etc/nginx/conf.d/
# /opt/remi/php70/root/usr/sbin/php-fpm
# systemctl restart nginx
~~~

## PDT Extension group

http://p2-dev.pdt-extensions.org/
