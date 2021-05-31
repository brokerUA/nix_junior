# [PHP Junior Developer. Practice](https://education.nixsolutions.com/mod/page/view.php?id=24)

## Install on [Play with Docker](https://labs.play-with-docker.com/)

`git clone https://github.com/brokerUA/nix_junior.git && cd nix_junior`

`cp .env.example .env`

`_UID=1000 && _GID=1000 && _USER=broker`

`/usr/sbin/adduser -u ${_UID} -D -H ${_USER} && chown -R ${_UID}:${_GID} ./`

`_UID=${_UID} _GID=${_GID} _USER=${_USER} docker-compose up -d`

`docker-compose exec php bash`

`composer install`

`php artisan key:generate`

`php artisan migrate:fresh --seed`

### Admin user:

Login: admin@app.com

Password: password

### URLs description:

8000 - Laravel demo project

8080 - phpMyAdmin

8100 - MailHog
