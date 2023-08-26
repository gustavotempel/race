#!/bin/bash

php bin/console doctrine:migration:migrate --no-interaction --allow-no-migration

symfony server:start