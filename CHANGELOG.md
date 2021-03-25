# [PHP Junior Developer. Practice](https://education.nixsolutions.com/mod/page/view.php?id=24)

#Install on [Play with Docker](https://labs.play-with-docker.com/)

`cd ~`

`git clone https://github.com/brokerUA/nix_junior.git`

`cd ~/nix_junior`

`cp .env.example .env`

`docker-compose up -d`

`sudo chown -R www-data:www-data ~/nix_junior`

`sudo chmod -R 774 ~/nix_junior`

`docker-compose exec php composer install`

`docker-compose exec php php artisan key:generate`

`docker-compose exec php php artisan migrate:fresh --seed`
