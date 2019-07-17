bin/console doctrine:database:create
bin/console doctrine:schema:update --force
bin/console doctrine:fixtures:load