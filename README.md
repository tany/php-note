README
======

## Description

MongoDB Viewer

## Installaion

```
## Source
mkdir /var/www/php-note && cd $_
git clone -b master --depth 1 git@github.com:tany/php-note.git .
git remote add tany git@github.com:tany/php-note.git

# Nginx
sudo ln -s /var/www/php-note/conf/server/nginx.conf /etc/nginx/conf.d/php-note.conf
sudo nginx -t
sudo systemctl restart nginx
```
