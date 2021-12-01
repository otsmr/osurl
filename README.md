# osurl.de
Very simple url shorter with odmin as user management.

## setup

1. start container
    1. `docker-compose up`
2. update config.php
    1. Create new Odmin service
    2. `callback-url: https://localhost:4003/api/odmin/oauth.php`
3. goto http://localhost:4003/