<h1 align="center" id="title">open-signage</h1>

<p id="description">Create signage for digital content based on web browser built with Laravel</p>

[![Docker](https://github.com/bluecraank/open-signage/actions/workflows/docker-publish.yml/badge.svg)](https://github.com/bluecraank/open-signage/actions/workflows/docker-publish.yml)

# Requirements
- Your monitor only needs a webbrowser with javascript
- Use a Raspberry Pi 3B or higher or any other device with browser
- Not supported: Raspberry Zero 2W, Raspberry Pi 2B
- Memory amount depends on amount of slides, recommend at least 1GB
- Recommendation: <a href="https://github.com/guysoft/FullPageOS">FullPageOS</a>

# Usage
- Export Presentation as PDF and upload document
- Background processing starts and generates images from each page
- Create and register monitor
- Assign presentation to monitor, group or schedule
- Automatically display presentation on monitor
- Create schedules or groups to easily assign presentations to multiple devices

# Functions
- LDAP-Login only
- Create, manage, delete devices
- Create, manage, delete groups
- Create, manage, delete presentations
- Create, manage, delete schedules
- Manage monitor settings (e.g Slide-duration)
- Discover url for easy mass deployment (IP recognition)
- Logging of actions in UI
- Rightmanagement

# Clientside browser functions
- Hides the cursor
- Force monitor reload on request
- Force monitor reload after x hours to prevent memory leak

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

## Install native
### Requirements
```
apt install php8.3 php8.3-imagick php8.3-mbstring php8.3-curl php8.3-ldap php8.3-bcmath
```
- php8.3
- npm
- composer
- php ldap extension
- php imagick extension
- ghostscript
- Replace imagick policy
```
sed -ri -e 's/<policy domain="resource" name="memory" value="256MiB"\/>/<policy domain="resource" name="memory" value="2GiB"\/>/g' /etc/ImageMagick-6/policy.xml

sed -ri -e 's/<policy domain="resource" name="map" value="512MiB"\/>/<policy domain="resource" name="map" value="8GiB"\/>/g' /etc/ImageMagick-6/policy.xml

sed -ri -e 's/<policy domain="resource" name="disk" value="1GiB"\/>/<policy domain="resource" name="disk" value="8GiB"\/>/g' /etc/ImageMagick-6/policy.xml

```

### Installation
- ```git clone https://github.com/bluecraank/open-signage```
- ```cd open-signage```
- ```composer install```
- ```npm install```
- ```npm run build```
- ```php artisan migrate --force```
- ```php artisan db:seed```
- ```php artisan serve``` (Not recommended)
- Use apache/nginx/caddy and configure root directory to ./public

## Words about reverse proxy
- If you use a reverse proxy to serve this app, set PROXY_URL in env file
```PROXY_URL="https://open-signage.company.com```
- Make sure your proxy expose real ip-address of client, so open-signage can recognize monitors and redirect them to correct presentation
## SSO
- Set enviroment variables
```
SSO_ENABLED=false
SSO_HTTP_HEADER_USER_KEY=HTTP_AUTH_USER
SSO_BYPASS_DOMAIN_VERIFICATION=false
```

## Deploy
- use FullPageOS <a href="https://github.com/guysoft/FullPageOS">https://github.com/guysoft/FullPageOS</a>
- or chromium parameters
```chromium --enable-logging --log-level=2 --v=0 --kiosk --touch-events=enabled --disable-pinch --noerrdialogs --simulate-outdated-no-au='Tue, 31 Dec 2099 23:59:59 GMT' --disable-session-crashed-bubble --disable-component-update --overscroll-history-navigation=0 --disable-features=Translate --autoplay-policy=no-user-gesture-required --app=https://mis.dc.local/discover```

### SSL Errors
- Add --ignore-certificate-errors to chromium flags (Not recommended)
- Add root-ca of your organization or domain

# License
The MIT License (MIT). Please see License File for more information.

