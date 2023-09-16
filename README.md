<h1 align="center" id="title">open-signage</h1>

<p id="description">Very easy way to create signage for digital content based on web browser built with Laravel</p>

# This app is usable on any system, on any browser
- Your monitor only need a webbrowser
- Use Raspberry, Windows, FireTV, SmartTV or anything which has a browser with javascript

# Usage
- Export PowerPoint as PDF and upload document
- Background processing starts and generates images from each page
- Create and register monitor
- Assign presentation to monitor
- Display presentation on monitor
- Create schedule or group to easily assign mutliple devices one presentation

# Functions
- Create, manage, delete schedules
- Create, manage, delete groups
- Create, manage, delete presentations
- Create, manage, delete devices
- Assign roles to users

# Clientside browser functions
- Hide cursor
- Force monitor reload on request
- Force monitor reload after x hours to prevent memory leak
- Select and set loading screen
- Configure animation times and more


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
- ```cd open-signage```
- ```php artisan migrate --force```
- ```php artisan db:seed```
- Serve with ```php artisan serve```
- Or use apache/nginx/caddy and configure root directory to ./public

## Word about reverse proxy
- If you use a reverse proxy to serve this app, set PROXY_URL in env file
```PROXY_URL="https://open-signage.company.com```

# Contribution
- Feel free to contribute and help to improve it

# License
The MIT License (MIT). Please see License File for more information.

