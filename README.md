# [PHP Junior Developer. Practice](https://education.nixsolutions.com/mod/page/view.php?id=24)

## Install on [Play with Docker](https://labs.play-with-docker.com/)

`git clone https://github.com/brokerUA/nix_junior.git && cd nix_junior`

`cp .env.example .env`

`docker-compose up -d`

`docker-compose exec php composer install`

`docker-compose exec php php artisan key:generate`

`docker-compose exec php php artisan migrate:fresh --seed`

### Admin user:

Login: admin@app.com

Password: password

### URLs description:

8000 - Laravel demo project

8080 - phpMyAdmin

8100 - MailHog
