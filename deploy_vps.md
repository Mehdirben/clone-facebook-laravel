# 🚀 Complete Laravel Facebook Clone Deployment Guide for DigitalOcean


**Your Droplet IP:** `207.154.215.219`  
**Database Password:** `1245780`


---


## Step 1: Connect to Your DigitalOcean Droplet


```bash
ssh root@207.154.215.219
```


---


## Step 2: System Setup and Software Installation


### Update System and Install Essential Packages
```bash
apt update && apt upgrade -y
apt install -y curl wget unzip software-properties-common apt-transport-https ca-certificates gnupg lsb-release git
```


### Install PHP 8.3 and Extensions
```bash
apt install -y php8.3 php8.3-fpm php8.3-cli php8.3-common php8.3-mysql php8.3-zip php8.3-gd php8.3-mbstring php8.3-curl php8.3-xml php8.3-bcmath php8.3-pdo php8.3-pgsql php8.3-intl php8.3-redis
```


### Install Composer
```bash
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
export COMPOSER_ALLOW_SUPERUSER=1
```


### Install Node.js 20.x
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
```


### Install and Configure PostgreSQL
```bash
apt install -y postgresql postgresql-contrib
systemctl start postgresql
systemctl enable postgresql
```


### Create Database and User
```bash
sudo -u postgres psql << 'EOF'
CREATE USER laravel_user WITH PASSWORD '1245780';
CREATE DATABASE laravel_db OWNER laravel_user;
GRANT ALL PRIVILEGES ON DATABASE laravel_db TO laravel_user;
ALTER USER laravel_user CREATEDB;
\q
EOF
```


### Install Nginx, Redis, and Supervisor
```bash
apt install -y nginx redis-server supervisor
systemctl start nginx redis-server supervisor
systemctl enable nginx redis-server supervisor
rm -f /etc/nginx/sites-enabled/default
```


---


## Step 3: Create Application Directory


```bash
mkdir -p /var/www/html/facebook-clone
cd /var/www/html/facebook-clone
```


---


## Step 4: Upload Your Laravel Application


**On your LOCAL Windows machine, open PowerShell and run:**


```powershell
# Navigate to your Laravel project directory
cd "C:\Users\mehdi\Documents\Projects\clone-facebook-laravel"


# Upload all files to your server
scp -r * root@207.154.215.219:/var/www/html/facebook-clone/
```


**Back on your server, fix file structure if needed:**


```bash
cd /var/www/html/facebook-clone


# If files are in a subdirectory, move them up
if [ -d "clone-facebook-laravel" ]; then
    mv clone-facebook-laravel/* .
    mv clone-facebook-laravel/.* . 2>/dev/null || true
    rmdir clone-facebook-laravel
fi


# Verify files are in place
ls -la | grep -E "(composer.json|artisan|app)"
```


---


## Step 5: Configure Environment and Install Dependencies


### Create Environment File
```bash
cd /var/www/html/facebook-clone
cat > .env << 'EOF'
APP_NAME="Facebook Clone"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=UTC
APP_URL=http://207.154.215.219


APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US


APP_MAINTENANCE_DRIVER=file


DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=1245780


CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis


REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379


MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"


VITE_APP_NAME="${APP_NAME}"
EOF
```


### Install Dependencies
```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev --no-interaction


# Generate application key
php artisan key:generate --force


# Install Node dependencies and build assets
npm install
npm run build
```


### Set Proper Permissions
```bash
chown -R www-data:www-data /var/www/html/facebook-clone
find /var/www/html/facebook-clone -type f -exec chmod 644 {} \;
find /var/www/html/facebook-clone -type d -exec chmod 755 {} \;
chmod -R 775 /var/www/html/facebook-clone/storage
chmod -R 775 /var/www/html/facebook-clone/bootstrap/cache
chmod 755 /var/www/html/facebook-clone/public
```


### Run Database Migrations
```bash
php artisan migrate --force
```


### Optimize Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```


---


## Step 6: Configure Nginx


### Create Nginx Site Configuration
```bash
cat > /etc/nginx/sites-available/facebook-clone << 'EOF'
server {
    listen 80;
    listen [::]:80;
    server_name 207.154.215.219 _;
    root /var/www/html/facebook-clone/public;


    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";


    index index.php;
    charset utf-8;


    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }


    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }


    error_page 404 /index.php;


    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }


    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF
```


### Enable Site and Restart Services
```bash
ln -s /etc/nginx/sites-available/facebook-clone /etc/nginx/sites-enabled/
nginx -t
systemctl restart nginx php8.3-fpm
```


---


## Step 7: Configure Queue Worker with Supervisor


```bash
cat > /etc/supervisor/conf.d/laravel-worker.conf << 'EOF'
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/facebook-clone/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/html/facebook-clone/storage/logs/worker.log
stopwaitsecs=3600
EOF


supervisorctl reread
supervisorctl update
supervisorctl start laravel-worker:*
```


---


## Step 8: Configure Firewall


```bash
ufw allow OpenSSH
ufw allow 'Nginx Full'
ufw --force enable
```


---


## Step 9: Optimize PHP Configuration


```bash
cat >> /etc/php/8.3/fpm/php.ini << 'EOF'


; Optimization settings
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
opcache.save_comments=1
opcache.fast_shutdown=1


; Upload settings
upload_max_filesize = 64M
post_max_size = 64M
max_execution_time = 300
EOF


systemctl restart php8.3-fpm
```


---


## Step 10: Create Deployment Script for Future Updates


```bash
cat > /root/deploy.sh << 'EOF'
#!/bin/bash
echo "🔄 Starting deployment..."


cd /var/www/html/facebook-clone


echo "📥 Pulling latest changes..."
git pull origin main 2>/dev/null || echo "No git repository configured"


echo "📦 Installing PHP dependencies..."
export COMPOSER_ALLOW_SUPERUSER=1
composer install --optimize-autoloader --no-dev --no-interaction


echo "🎨 Installing Node dependencies and building assets..."
npm install
npm run build


echo "🗄️ Running database migrations..."
php artisan migrate --force


echo "🧹 Clearing and caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache


echo "👥 Setting permissions..."
chown -R www-data:www-data /var/www/html/facebook-clone
find /var/www/html/facebook-clone -type f -exec chmod 644 {} \;
find /var/www/html/facebook-clone -type d -exec chmod 755 {} \;
chmod -R 775 /var/www/html/facebook-clone/storage
chmod -R 775 /var/www/html/facebook-clone/bootstrap/cache
chmod 755 /var/www/html/facebook-clone/public


echo "🔄 Restarting services..."
supervisorctl restart laravel-worker:*
systemctl reload php8.3-fpm


echo "✅ Deployment complete!"
EOF


chmod +x /root/deploy.sh
```


---


## Step 11: Set Up Log Rotation


```bash
cat > /etc/logrotate.d/laravel << 'EOF'
/var/www/html/facebook-clone/storage/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    notifempty
    create 644 www-data www-data
    postrotate
        /usr/bin/supervisorctl restart laravel-worker:*
    endscript
}
EOF
```


---


## Step 12: Final Verification


### Test All Services
```bash
echo "=== SERVICES STATUS ==="
systemctl is-active nginx php8.3-fpm postgresql redis-server supervisor


echo -e "\n=== LISTENING PORTS ==="
ss -tulpn | grep -E ":80|:5432|:6379"


echo -e "\n=== LARAVEL STATUS ==="
cd /var/www/html/facebook-clone
php artisan --version


echo -e "\n=== PERMISSIONS CHECK ==="
ls -la public/index.php
ls -ld storage/


echo -e "\n=== LOCAL HTTP TEST ==="
curl -I http://localhost


echo -e "\n=== DATABASE CONNECTION ==="
php artisan migrate:status | head -5


echo "=== APPLICATION URL ==="
echo "🎉 Your Facebook Clone is live at: http://207.154.215.219"
```


---


## 🎊 Your Application is Now Live!


**Visit:** `http://207.154.215.219`


## 🛠️ Maintenance Commands


### View Application Logs
```bash
tail -f /var/www/html/facebook-clone/storage/logs/laravel.log
```


### Restart All Services
```bash
systemctl restart nginx php8.3-fpm postgresql redis-server
supervisorctl restart laravel-worker:*
```


### Deploy Updates
```bash
/root/deploy.sh
```


### Upload New Code (from your local machine)
```powershell
# Run this from your Laravel project directory on Windows
scp -r * root@207.154.215.219:/var/www/html/facebook-clone/
```


### Backup Database
```bash
pg_dump -U laravel_user -h localhost laravel_db > backup_$(date +%Y%m%d_%H%M%S).sql
```


---


## ✅ What's Working Now


- ✅ **Laravel 12** with **PHP 8.3**
- ✅ **PostgreSQL** database with password `1245780`
- ✅ **Redis** for caching and sessions
- ✅ **Nginx** web server
- ✅ **Queue workers** with Supervisor
- ✅ **Optimized performance**
- ✅ **Proper file permissions**
- ✅ **Firewall configured**
- ✅ **Log rotation**
- ✅ **Deployment script**


---


## 🔒 Optional: Add HTTPS with SSL Certificate


### For Production with Domain Name
```bash
# Install Certbot
apt install -y certbot python3-certbot-nginx


# Get SSL certificate (replace with your domain)
certbot --nginx -d yourdomain.com -d www.yourdomain.com
```


### For Testing with Self-Signed Certificate
```bash
# Create self-signed SSL certificate
mkdir -p /etc/ssl/private
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/ssl/private/nginx-selfsigned.key \
    -out /etc/ssl/certs/nginx-selfsigned.crt \
    -subj "/C=US/ST=State/L=City/O=Organization/CN=207.154.215.219"


# Update Nginx configuration for HTTPS
cat > /etc/nginx/sites-available/facebook-clone << 'EOF'
server {
    listen 80;
    server_name 207.154.215.219 _;
    return 301 https://$server_name$request_uri;
}


server {
    listen 443 ssl;
    listen [::]:443 ssl;
    server_name 207.154.215.219 _;
    root /var/www/html/facebook-clone/public;


    ssl_certificate /etc/ssl/certs/nginx-selfsigned.crt;
    ssl_certificate_key /etc/ssl/private/nginx-selfsigned.key;


    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_prefer_server_ciphers off;


    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";


    index index.php;
    charset utf-8;


    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }


    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }


    error_page 404 /index.php;


    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }


    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF


# Enable HTTPS and restart
ufw allow 443/tcp
nginx -t && systemctl reload nginx


# Update Laravel APP_URL for HTTPS
sed -i 's|APP_URL=.*|APP_URL=https://207.154.215.219|' /var/www/html/facebook-clone/.env
cd /var/www/html/facebook-clone && php artisan config:cache
```


---


## 🚨 Troubleshooting


### If application shows errors:
```bash
# Check Laravel logs
tail -f /var/www/html/facebook-clone/storage/logs/laravel.log


# Check Nginx error logs
tail -f /var/log/nginx/error.log


# Check PHP-FPM logs
tail -f /var/log/php8.3-fpm.log
```


### If database connection fails:
```bash
# Test database connection
sudo -u postgres psql -c "\l" | grep laravel_db


# Reset database password if needed
sudo -u postgres psql -c "ALTER USER laravel_user WITH PASSWORD '1245780';"
```


### If permissions are wrong:
```bash
# Fix all permissions
cd /var/www/html/facebook-clone
chown -R www-data:www-data .
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;
chmod -R 775 storage bootstrap/cache
```


---


**Your Facebook clone is now running at:** `http://207.154.215.219` 🚀


Test features like user registration, posts, comments, likes, friend requests, and messaging!

