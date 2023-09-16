<h1 align="center" id="title">open-signage</h1>

<p id="description">Very easy way to create signage for digital content based on web browser built with Laravel</p>

# How it works
- Upload PDF Document with n Pages (e.g each Page is one fullwidth image)
- Background processing starts and generates images from each page
- Create and register monitor
- Assign presentation to monitor
- Display presentation on monitor
- Create schedule or group to easily assign mutliple devices one presentation

# Functions
- Create schedules and groups
- Force reload of monitor
- Live updating if presentation gets changed
- Reload page after x hours to prevent memory leaks
- Hides cursor


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

# Installation
## Docker installation
- Create stack.env and set necessary variables
- sudo docker compose up
## Updating
- sudo docker builder prune
- sudo docker compose up --force-recreate --build

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
- Feel free to contribute and help to improve it

# License
The MIT License (MIT). Please see License File for more information.

