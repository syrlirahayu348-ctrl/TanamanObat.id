#!/bin/bash
# ============================================================
# TanamanObat.id - Ubuntu Server Setup Script (Local MySQL)
# ============================================================
# Laravel 10 + Nginx + PHP 8.3-FPM + Node.js 20
# Database: MySQL (Local Server)
# Target: Ubuntu Server on VirtualBox
# ============================================================

set -e

# ===================== KONFIGURASI =====================
PROJECT_NAME="tanamanobat"
PROJECT_DIR="/var/www/tanamanobat"
UPLOAD_DIR="$(pwd)"
PHP_VERSION="8.3"
NODE_VERSION="20"
SERVER_IP="172.16.63.10"

# MySQL Settings
DB_NAME="tanamanobat"
DB_USER="tanamanobat_user"
# Generate random password for DB
DB_PASS=$(openssl rand -base64 12)

# ===================== WARNA =====================
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
MAGENTA='\033[0;35m'
NC='\033[0m'
BOLD='\033[1m'

print_header() {
    echo ""
    echo -e "${CYAN}╔══════════════════════════════════════════════════════════╗${NC}"
    echo -e "${CYAN}║${NC} ${BOLD}$1${NC}"
    echo -e "${CYAN}╚══════════════════════════════════════════════════════════╝${NC}"
    echo ""
}
print_step() { echo -e "${GREEN}[✓]${NC} $1"; }
print_info() { echo -e "${BLUE}[i]${NC} $1"; }
print_warn() { echo -e "${YELLOW}[!]${NC} $1"; }
print_error() { echo -e "${RED}[✗]${NC} $1"; }

# ===================== CEK ROOT =====================
if [ "$EUID" -ne 0 ]; then
    print_error "Script ini harus dijalankan sebagai root (sudo)!"
    exit 1
fi

print_header "TanamanObat.id - Setup Ubuntu Server (MySQL)"
print_info "Server IP: ${SERVER_IP}"
print_info "Project Directory: ${PROJECT_DIR}"

# ===================== STEP 1: UPDATE SYSTEM =====================
print_header "STEP 1/9 — Update System"
apt update -y && apt upgrade -y
apt install -y software-properties-common curl wget gnupg2 unzip git ca-certificates apt-transport-https

# ===================== STEP 2: INSTALL MYSQL =====================
print_header "STEP 2/9 — Install MySQL Server"
apt install -y mysql-server
systemctl start mysql
systemctl enable mysql

print_info "Membuat Database dan User MySQL..."
mysql -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
mysql -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "ALTER USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mysql -e "GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"
print_step "Database: ${DB_NAME}"
print_step "Username: ${DB_USER}"
print_step "Password: ${DB_PASS}"

# ===================== STEP 3: INSTALL PHP =====================
print_header "STEP 3/9 — Install PHP ${PHP_VERSION} + Extensions"
add-apt-repository -y ppa:ondrej/php
apt update -y
apt install -y \
    php${PHP_VERSION} \
    php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-cli \
    php${PHP_VERSION}-common \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-readline \
    php${PHP_VERSION}-tokenizer \
    php${PHP_VERSION}-dom \
    php${PHP_VERSION}-fileinfo

sed -i "s/^user = .*/user = www-data/" /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf
sed -i "s/upload_max_filesize = .*/upload_max_filesize = 64M/" /etc/php/${PHP_VERSION}/fpm/php.ini
sed -i "s/post_max_size = .*/post_max_size = 64M/" /etc/php/${PHP_VERSION}/fpm/php.ini
systemctl restart php${PHP_VERSION}-fpm

# ===================== STEP 4: INSTALL NGINX =====================
print_header "STEP 4/9 — Install Nginx"
apt install -y nginx

# ===================== STEP 5: INSTALL NODE.JS =====================
print_header "STEP 5/9 — Install Node.js ${NODE_VERSION}"
curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash -
apt install -y nodejs

# ===================== STEP 6: INSTALL COMPOSER =====================
print_header "STEP 6/9 — Install Composer"
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# ===================== STEP 7: DEPLOY PROJECT =====================
print_header "STEP 7/9 — Deploy Project Files"
mkdir -p "${PROJECT_DIR}"

if [ -f "${UPLOAD_DIR}/artisan" ]; then
    print_info "Menyalin file project dari ${UPLOAD_DIR}..."
    rsync -av --exclude='node_modules' --exclude='vendor' --exclude='.git' "${UPLOAD_DIR}/" "${PROJECT_DIR}/"
else
    print_error "File artisan tidak ditemukan di ${UPLOAD_DIR}!"
    print_info "Pastikan file project sudah di-upload ke server terlebih dahulu."
    exit 1
fi

cd "${PROJECT_DIR}"

# Buat .env dari .env.example jika belum ada
if [ ! -f ".env" ]; then
    cp .env.example .env
fi

# Update .env untuk production
print_info "Mengkonfigurasi .env untuk production..."
sed -i "s|APP_NAME=.*|APP_NAME=TanamanObat.id|" .env
sed -i "s|APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|" .env
sed -i "s|APP_URL=.*|APP_URL=http://${SERVER_IP}|" .env

# Database MySQL
sed -i "s|DB_CONNECTION=.*|DB_CONNECTION=mysql|" .env
sed -i "s|^# DB_HOST=.*|DB_HOST=127.0.0.1|" .env
sed -i "s|^DB_HOST=.*|DB_HOST=127.0.0.1|" .env
sed -i "s|^# DB_PORT=.*|DB_PORT=3306|" .env
sed -i "s|^DB_PORT=.*|DB_PORT=3306|" .env
sed -i "s|^# DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|" .env
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_NAME}|" .env
sed -i "s|^# DB_USERNAME=.*|DB_USERNAME=${DB_USER}|" .env
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USER}|" .env
# Handle password
sed -i '/^# DB_PASSWORD=/d' .env
sed -i '/^DB_PASSWORD=/d' .env
# Tambahkan password setelah username
sed -i "/^DB_USERNAME=.*/a DB_PASSWORD=\"${DB_PASS}\"" .env

# Google OAuth
if ! grep -q "^GOOGLE_CLIENT_ID=" .env; then
    echo "" >> .env
    echo "# Google OAuth" >> .env
    echo "GOOGLE_CLIENT_ID=450102867619-61lo2cm87nsg28ts4gn5jf48b178b1s4.apps.googleusercontent.com" >> .env
    echo "GOOGLE_CLIENT_SECRET=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" >> .env
    echo "GOOGLE_REDIRECT_URL=http://${SERVER_IP}/auth/google/callback" >> .env
else
    sed -i "s|GOOGLE_REDIRECT_URL=.*|GOOGLE_REDIRECT_URL=http://${SERVER_IP}/auth/google/callback|" .env
fi

# reCAPTCHA
if ! grep -q "^RECAPTCHA_SITE_KEY=" .env; then
    echo "" >> .env
    echo "# Google reCAPTCHA v2" >> .env
    echo "RECAPTCHA_SITE_KEY=6LdISzMtAAAAAA0vJahkeMDi914Q2CO9A0SjelJn" >> .env
    echo "RECAPTCHA_SECRET_KEY=6LdISzMtAAAAAAjb5n_CmCVMk2NokWiIU84Dr6pL" >> .env
fi

# Mail Config
sed -i "s|MAIL_MAILER=.*|MAIL_MAILER=smtp|" .env
sed -i "s|MAIL_HOST=.*|MAIL_HOST=smtp.gmail.com|" .env
sed -i "s|MAIL_PORT=.*|MAIL_PORT=465|" .env
sed -i "s|MAIL_USERNAME=.*|MAIL_USERNAME=akusayangfirefly@gmail.com|" .env
sed -i "s|MAIL_PASSWORD=.*|MAIL_PASSWORD=ntelmkxfclpeedqg|" .env
if ! grep -q "^MAIL_ENCRYPTION=" .env; then
    sed -i "/^MAIL_PASSWORD=.*/a MAIL_ENCRYPTION=ssl" .env
else
    sed -i "s|MAIL_ENCRYPTION=.*|MAIL_ENCRYPTION=ssl|" .env
fi
sed -i "s|MAIL_FROM_ADDRESS=.*|MAIL_FROM_ADDRESS=\"akusayangfirefly@gmail.com\"|" .env
sed -i "s|MAIL_FROM_NAME=.*|MAIL_FROM_NAME=\"TanamanObat.id\"|" .env

# Fix ownership dan permissions
chown -R www-data:www-data "${PROJECT_DIR}"
chmod -R 775 "${PROJECT_DIR}/storage" "${PROJECT_DIR}/bootstrap/cache"

# Install PHP dependencies
print_info "Menjalankan composer install..."
cd "${PROJECT_DIR}"
sudo -u www-data composer install --no-dev --optimize-autoloader 2>&1 || {
    print_warn "composer install gagal sebagai www-data, mencoba sebagai root..."
    composer install --no-dev --optimize-autoloader
    chown -R www-data:www-data "${PROJECT_DIR}/vendor"
}

# Install Node dependencies dan build assets (Vite)
print_info "Menjalankan npm install & build..."
npm ci 2>/dev/null || npm install 2>/dev/null || {
    print_warn "npm install gagal, mencoba ulang..."
    npm install 2>/dev/null || true
}
npm run build || {
    print_warn "npm run build gagal, mencoba ulang..."
    npm run build || true
}
chown -R www-data:www-data "${PROJECT_DIR}/node_modules" 2>/dev/null || true
chown -R www-data:www-data "${PROJECT_DIR}/public/build" 2>/dev/null || true

# Laravel artisan commands
print_info "Menjalankan Laravel artisan commands..."
sudo -u www-data php artisan key:generate --force || true
sudo -u www-data php artisan optimize:clear || true
sudo -u www-data php artisan storage:link 2>/dev/null || true
sudo -u www-data php artisan migrate --force || true
sudo -u www-data php artisan db:seed --force || true
sudo -u www-data php artisan config:cache || true
sudo -u www-data php artisan route:cache || true
sudo -u www-data php artisan view:cache || true

# ===================== STEP 8: KONFIGURASI NGINX =====================
print_header "STEP 8/9 — Konfigurasi Nginx"
cat > /etc/nginx/sites-available/tanamanobat <<NGINX_CONF
server {
    listen 80;
    server_name ${SERVER_IP};
    root ${PROJECT_DIR}/public;
    index index.php index.html;
    client_max_body_size 64M;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        fastcgi_pass unix:/var/run/php/php${PHP_VERSION}-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    # Cache static assets (Vite build)
    location /build/ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff2?)$ {
        expires 30d;
        add_header Cache-Control "public";
    }

    # Deny access to hidden files
    location ~ /\. {
        deny all;
    }
}
NGINX_CONF

ln -sf /etc/nginx/sites-available/tanamanobat /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test nginx config
nginx -t
systemctl restart nginx

# ===================== STEP 9: FIREWALL & FINAL =====================
print_header "STEP 9/9 — Setup Firewall"

# Firewall
print_info "Mengkonfigurasi Firewall (UFW)..."
apt install -y ufw
ufw --force reset
ufw default deny incoming
ufw default allow outgoing
ufw allow 22/tcp    # SSH
ufw allow 80/tcp    # HTTP
ufw allow 443/tcp   # HTTPS
ufw --force enable

# ===================== SELESAI =====================
print_header "🎉 SETUP SELESAI! 🎉"
echo ""
echo -e "${GREEN}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║  TanamanObat.id berhasil di-deploy!                    ║${NC}"
echo -e "${GREEN}╚══════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${CYAN}Akses Website:${NC}"
echo -e "  http://${SERVER_IP}"
echo ""
echo -e "${MAGENTA}Info MySQL:${NC}"
echo -e "  Database : ${DB_NAME}"
echo -e "  Username : ${DB_USER}"
echo -e "  Password : ${DB_PASS}"
echo ""
echo -e "${YELLOW}Info Penting:${NC}"
echo -e "  Project Dir  : ${PROJECT_DIR}"
echo -e "  Nginx Config : /etc/nginx/sites-available/tanamanobat"
echo -e "  PHP-FPM      : systemctl status php${PHP_VERSION}-fpm"
echo -e "  Nginx        : systemctl status nginx"
echo ""
echo -e "${YELLOW}Useful Commands:${NC}"
echo -e "  sudo php artisan migrate:fresh --seed   # Reset DB + seed data"
echo -e "  sudo php artisan optimize:clear          # Clear all caches"
echo -e "  sudo systemctl restart nginx             # Restart Nginx"
echo -e "  sudo systemctl restart php${PHP_VERSION}-fpm     # Restart PHP-FPM"
echo ""
echo -e "${RED}⚠  SIMPAN PASSWORD DATABASE DI ATAS!${NC}"
echo ""
