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
- Discover url for easy mass deployment

# Clientside browser functions
- Hide cursor
- Force monitor reload on request
- Force monitor reload after x hours to prevent memory leak
- Select and set loading screen
- Configure animation times and more


# Configuration
## Docker
- Edit docker-compose.yml to your needs
## Native
- Move env.example to .env
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
- Setup Database connection (Default: sqlite)

# Installation
## Docker-compose installation
- Build image with ```docker build -t open-signage/open-signage:latest```
- Edit "image" in docker-compose to your tagged image name 
- Edit "environment" in docker-compose to your need
- ```sudo docker compose up```
- ```sudo docker ps``` and grab container id for open-signage-app
- ```sudo docker exec ID php artisan migrate```
- ```sudo docker exec ID php artisan db:seed```
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
- Serve with ```php artisan serve``` (Not recommended)
- Use apache/nginx/caddy and configure root directory to ./public

## Words about reverse proxy
- If you use a reverse proxy to serve this app, set PROXY_URL in env file
```PROXY_URL="https://open-signage.company.com```
- Make sure your proxy expose real ip-address of client, so open-signage can recognize monitors and redirect them to correct presentation

## Deploy
- Example chromium
```chromium --enable-logging --log-level=2 --v=0 --kiosk --touch-events=enabled --disable-pinch --noerrdialogs --simulate-outdated-no-au='Tue, 31 Dec 2099 23:59:59 GMT' --disable-session-crashed-bubble --disable-component-update --overscroll-history-navigation=0 --disable-features=Translate --autoplay-policy=no-user-gesture-required --app=https://mis.dc.local/discover```
- or use FullPageOS <a href="https://github.com/guysoft/FullPageOS">https://github.com/guysoft/FullPageOS</a>
### SSL Errors
- Add --ignore-certificate-errors to chromium flags

# Contribution
- Feel free to contribute and help to improve it

# License
The MIT License (MIT). Please see License File for more information.

