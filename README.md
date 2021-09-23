# P7-API-Symfony

Develop Bilemo API REST

## Config
- PHP 8.0.8
- SYMFONY 5.3
- DOCTRINE 2.8
- COMPOSER 2.0.11
- APACHE 2.4.46
- MARIADB 10.4.13

## Install
* Step 1: Move to the installation folder, make a `git clone` followed by `https://github.com/Damien30340/P7-API-Symfony.git`
* Step 2: Configure your environment variables `.env.local` 
* Step 3: Make a `composer install` in command line
* Step 4: Make a `symfony console` or `php bin/console` followed by `doctrine:database:create`
* Step 5: Make a `symfony console` or `php bin/console` followed by `doctrine:migrations:migrate`
* Step 6: Make a `symfony console` or `php bin/console` followed by `doctrine:fixtures:load`

### Generate the SSL keys for JWT TOKEN
* `php bin/console lexik:jwt:generate-keypair`
* Save your passphrase !
#### Configuration JWT TOKEN
* Update your `config/packages/lexik_jwt_authentication.yaml`
```
lexik_jwt_authentication:
secret_key:       '%kernel.project_dir%/config/jwt/private.pem' # required for token creation
public_key:       '%kernel.project_dir%/config/jwt/public.pem'  # required for token verification
pass_phrase:      'your_secret_passphrase' # required for token creation, usage of an environment variable is recommended
token_ttl:        3600
```
For more informations on Lexik_jwt_bundle, visit : https://github.com/lexik/LexikJWTAuthenticationBundle/blob/2.x/Resources/doc/index.md
