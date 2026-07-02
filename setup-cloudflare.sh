#!/bin/bash
# ============================================================
# Setup Cloudflare Tunnel untuk TanamanObat.id
# ============================================================
# Dijalankan SETELAH setup-ubuntu-mysql.sh berhasil.
# Script ini akan:
#   1. Install cloudflared
#   2. Login ke Cloudflare
#   3. Membuat tunnel
#   4. Routing DNS
#   5. Update Nginx agar support domain
#   6. Update .env Laravel (APP_URL, Google OAuth callback)
#   7. Clear & rebuild cache Laravel
# ============================================================

set -e

PROJECT_NAME="tanamanobat"
PROJECT_DIR="/var/www/tanamanobat"
TUNNEL_NAME="tanamanobat-tunnel"
PHP_VERSION="8.3"

# Warna
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
print_info() { echo -e "${BLUE}[i]${NC} $1"; }
print_step() { echo -e "${GREEN}[✓]${NC} $1"; }
print_warn() { echo -e "${YELLOW}[!]${NC} $1"; }
print_error() { echo -e "${RED}[✗]${NC} $1"; }

if [ "$EUID" -ne 0 ]; then
    print_error "Script ini harus dijalankan sebagai root (sudo)!"
    exit 1
fi

print_header "Setup Cloudflare Tunnel - TanamanObat.id"

# ===================== INPUT DOMAIN =====================
read -p "$(echo -e ${YELLOW}Masukkan domain Cloudflare Anda \(contoh: tanamanobat.id\): ${NC})" DOMAIN

if [ -z "$DOMAIN" ]; then
    print_error "Domain tidak boleh kosong!"
    exit 1
fi

print_step "Domain target: ${DOMAIN}"
print_step "Domain www   : www.${DOMAIN}"

# ===================== STEP 1: INSTALL CLOUDFLARED =====================
print_header "STEP 1/7 — Install Cloudflared"
if ! command -v cloudflared &> /dev/null; then
    print_info "Mendownload cloudflared..."
    wget -q "https://github.com/cloudflare/cloudflared/releases/latest/download/cloudflared-linux-amd64.deb" -O /tmp/cloudflared.deb
    dpkg -i /tmp/cloudflared.deb
    rm /tmp/cloudflared.deb
    print_step "Cloudflared berhasil diinstal."
else
    print_step "Cloudflared sudah terinstal: $(cloudflared --version)"
fi

# ===================== STEP 2: LOGIN CLOUDFLARE =====================
print_header "STEP 2/7 — Login ke Cloudflare"
echo -e "${YELLOW}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${YELLOW}║  PERHATIAN — IKUTI LANGKAH INI:                        ║${NC}"
echo -e "${YELLOW}╠══════════════════════════════════════════════════════════╣${NC}"
echo -e "${YELLOW}║${NC} 1. Copy link yang muncul di bawah ini                   ${YELLOW}║${NC}"
echo -e "${YELLOW}║${NC} 2. Buka link tersebut di browser komputer Anda          ${YELLOW}║${NC}"
echo -e "${YELLOW}║${NC} 3. Login dengan akun Cloudflare Anda                    ${YELLOW}║${NC}"
echo -e "${YELLOW}║${NC} 4. Pilih domain: ${BOLD}${DOMAIN}${NC}                              ${YELLOW}║${NC}"
echo -e "${YELLOW}║${NC} 5. Script akan otomatis lanjut setelah Anda authorize   ${YELLOW}║${NC}"
echo -e "${YELLOW}╚══════════════════════════════════════════════════════════╝${NC}"
echo ""
cloudflared tunnel login
print_step "Login berhasil!"

# ===================== STEP 3: SETUP TUNNEL =====================
print_header "STEP 3/7 — Membuat Cloudflare Tunnel"
print_info "Menghapus tunnel lama (jika ada)..."
cloudflared tunnel delete "${TUNNEL_NAME}" 2>/dev/null || true

print_info "Membuat tunnel baru: ${TUNNEL_NAME}..."
cloudflared tunnel create "${TUNNEL_NAME}"

TUNNEL_ID=$(cloudflared tunnel list | grep "${TUNNEL_NAME}" | awk '{print $1}')
if [ -z "$TUNNEL_ID" ]; then
    print_error "Gagal mendapatkan Tunnel ID!"
    exit 1
fi
print_step "Tunnel ID: ${TUNNEL_ID}"

# ===================== STEP 4: KONFIGURASI TUNNEL =====================
print_header "STEP 4/7 — Konfigurasi Tunnel"
print_info "Membuat file konfigurasi tunnel..."
mkdir -p /root/.cloudflared
cat > /root/.cloudflared/config.yml <<TUNNEL_CONF
tunnel: ${TUNNEL_ID}
credentials-file: /root/.cloudflared/${TUNNEL_ID}.json
ingress:
  - hostname: ${DOMAIN}
    service: http://localhost:80
  - hostname: www.${DOMAIN}
    service: http://localhost:80
  - service: http_status:404
TUNNEL_CONF

print_step "Config tunnel disimpan di /root/.cloudflared/config.yml"

# ===================== STEP 5: ROUTING DNS & SERVICE =====================
print_header "STEP 5/7 — Routing DNS & Jalankan Service"
print_info "Mendaftarkan domain ke DNS Cloudflare..."
cloudflared tunnel route dns -f "${TUNNEL_NAME}" "${DOMAIN}" || true
cloudflared tunnel route dns -f "${TUNNEL_NAME}" "www.${DOMAIN}" || true
print_step "DNS routing berhasil untuk ${DOMAIN} dan www.${DOMAIN}"

print_info "Menginstall cloudflared sebagai systemd service..."
# Stop & uninstall service lama jika ada
systemctl stop cloudflared 2>/dev/null || true
cloudflared service uninstall 2>/dev/null || true

cloudflared service install
systemctl daemon-reload
systemctl enable cloudflared
systemctl start cloudflared
print_step "Service cloudflared aktif dan berjalan 24/7!"

# ===================== STEP 6: UPDATE NGINX =====================
print_header "STEP 6/7 — Update Konfigurasi Nginx untuk Domain"
print_info "Mengupdate Nginx agar menerima request dari domain..."

cat > /etc/nginx/sites-available/tanamanobat <<NGINX_CONF
server {
    listen 80;
    server_name ${DOMAIN} www.${DOMAIN} 172.16.63.10;
    root ${PROJECT_DIR}/public;
    index index.php index.html;
    client_max_body_size 64M;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Redirect www ke non-www (opsional, bisa di-comment jika tidak perlu)
    # if (\$host = www.${DOMAIN}) {
    #     return 301 https://${DOMAIN}\$request_uri;
    # }

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        fastcgi_pass unix:/var/run/php/php${PHP_VERSION}-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    # Cache Vite build assets
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

# Pastikan symlink ada
ln -sf /etc/nginx/sites-available/tanamanobat /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

# Test & restart Nginx
nginx -t
systemctl restart nginx
print_step "Nginx dikonfigurasi untuk domain: ${DOMAIN}"

# ===================== STEP 7: UPDATE LARAVEL .ENV =====================
print_header "STEP 7/7 — Update Konfigurasi Laravel (.env)"
if [ -d "${PROJECT_DIR}" ] && [ -f "${PROJECT_DIR}/.env" ]; then
    cd "${PROJECT_DIR}"

    # Update APP_URL ke https (Cloudflare otomatis handle SSL)
    sed -i "s|^APP_URL=.*|APP_URL=https://${DOMAIN}|" .env
    print_step "APP_URL → https://${DOMAIN}"

    # Update Google OAuth Redirect URL
    # Project ini menggunakan GOOGLE_REDIRECT_URL (bukan GOOGLE_REDIRECT_URI)
    if grep -q "^GOOGLE_REDIRECT_URL=" .env; then
        sed -i "s|^GOOGLE_REDIRECT_URL=.*|GOOGLE_REDIRECT_URL=https://${DOMAIN}/auth/google/callback|" .env
        print_step "GOOGLE_REDIRECT_URL → https://${DOMAIN}/auth/google/callback"
    elif grep -q "^GOOGLE_REDIRECT_URI=" .env; then
        sed -i "s|^GOOGLE_REDIRECT_URI=.*|GOOGLE_REDIRECT_URI=https://${DOMAIN}/auth/google/callback|" .env
        print_step "GOOGLE_REDIRECT_URI → https://${DOMAIN}/auth/google/callback"
    else
        echo "GOOGLE_REDIRECT_URL=https://${DOMAIN}/auth/google/callback" >> .env
        print_step "GOOGLE_REDIRECT_URL ditambahkan → https://${DOMAIN}/auth/google/callback"
    fi

    # Set Trusted Proxies agar Laravel mengenali HTTPS dari Cloudflare
    if ! grep -q "^TRUSTED_PROXIES=" .env; then
        echo "" >> .env
        echo "# Cloudflare Trusted Proxies (agar Laravel deteksi HTTPS)" >> .env
        echo "TRUSTED_PROXIES=*" >> .env
        print_step "TRUSTED_PROXIES=* ditambahkan"
    fi

    # Clear dan rebuild cache
    print_info "Membersihkan dan rebuild cache Laravel..."
    sudo -u www-data php artisan optimize:clear || true
    sudo -u www-data php artisan config:cache || true
    sudo -u www-data php artisan route:cache || true
    sudo -u www-data php artisan view:cache || true
    print_step "Cache Laravel di-rebuild!"
else
    print_error "Direktori project atau file .env tidak ditemukan di ${PROJECT_DIR}"
    print_warn "Pastikan setup-ubuntu-mysql.sh sudah dijalankan terlebih dahulu!"
fi

# ===================== SELESAI =====================
print_header "🎉 SETUP CLOUDFLARE TUNNEL SELESAI! 🎉"
echo ""
echo -e "${GREEN}╔══════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║  TanamanObat.id berhasil dikonfigurasi dengan domain!   ║${NC}"
echo -e "${GREEN}╚══════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${CYAN}Akses Website:${NC}"
echo -e "  🌐 https://${DOMAIN}"
echo -e "  🌐 https://www.${DOMAIN}"
echo -e "  🔧 http://172.16.63.10  (akses lokal/LAN)"
echo ""
echo -e "${MAGENTA}Info Cloudflare Tunnel:${NC}"
echo -e "  Tunnel Name  : ${TUNNEL_NAME}"
echo -e "  Tunnel ID    : ${TUNNEL_ID}"
echo -e "  Config File  : /root/.cloudflared/config.yml"
echo -e "  Service      : systemctl status cloudflared"
echo ""
echo -e "${YELLOW}Info Penting:${NC}"
echo -e "  Project Dir  : ${PROJECT_DIR}"
echo -e "  Nginx Config : /etc/nginx/sites-available/tanamanobat"
echo -e "  PHP-FPM      : systemctl status php${PHP_VERSION}-fpm"
echo ""
echo -e "${YELLOW}Useful Commands:${NC}"
echo -e "  sudo systemctl status cloudflared         # Cek status tunnel"
echo -e "  sudo systemctl restart cloudflared        # Restart tunnel"
echo -e "  sudo cloudflared tunnel list              # List semua tunnel"
echo -e "  sudo tail -f /var/log/cloudflared.log     # Log tunnel"
echo ""
echo -e "${YELLOW}⚠  PENTING — Pastikan di Google Cloud Console:${NC}"
echo -e "  Authorized redirect URI sudah ditambahkan:"
echo -e "  ${BOLD}https://${DOMAIN}/auth/google/callback${NC}"
echo ""
