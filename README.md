<h1 align="center" id="title">open-signage</h1>

<p id="description">Very easy way to create signage for digital content based on web browser built with Laravel</p>

## This project is still wip and lacks of security / permissions

# Configuration
## Configure .env
- Setup LDAP Connection
```
LDAP_LOGGING=true
LDAP_CONNECTION=default
LDAP_HOST=some.ldap.server
LDAP_USERNAME="CN=ro_mis,CN=Users,DC=ldap,DC=server"
LDAP_PASSWORD=
LDAP_PORT=636
LDAP_BASE_DN="dc=ldap,dc=server"
LDAP_TIMEOUT=15
LDAP_SSL=true
LDAP_TLS=false
LDAP_SASL=false
LDAP_ALLOWED_GROUP="CN=MIS,OU=Groups,DC=ldap,DC=server"
```
- Configure behaviour e.g refresh interval, loading background and more...
```
MONITOR_REFRESH_TIME_SECONDS=43200
MONITOR_CHECK_UPDATE_TIME_SECONDS=30
SLIDE_IN_TIME_MS=1100
SLIDE_OUT_TIME_MS=1500
INTERVAL_NEXT_SLIDE_MS=20000
LOADING_BACKGROUND_TEXT=""
LOADING_BACKGROUND_TYPE="image"
LOADING_BACKGROUND_COLOR="#000000"
LOADING_BACKGROUND_IMAGE="https://picsum.photos/1920/1080"
```
# Installation
## Install with docker-compose
- Build container with ```sudo docker compose up```
- Exposed port is 8080

## Install native
### Requirements
- php8.2
- php ldap extension
- php imagick extension
- ghostscript
### Installation
- ```git clone https://github.com/bluecraank/open-signage```
- Serve with ```php artisan serve```
- Or use apache/nginx/caddy and configure root directory to ./public

# Contribution
- Feel free to contribute
```
- Permissions / Roles are missing (spatie/permissions?)
- Monitor Authentication is missing
```

# License
The MIT License (MIT). Please see License File for more information.

