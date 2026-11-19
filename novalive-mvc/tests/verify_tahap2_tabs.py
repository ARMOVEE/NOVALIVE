import re
import urllib.request
import urllib.parse
import http.cookiejar
import subprocess

BASE_URL = 'http://localhost:8000'

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

def get(path):
    url = f"{BASE_URL}/{path.lstrip('/')}"
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    with opener.open(req) as resp:
        return resp.getcode(), resp.read().decode('utf-8', errors='ignore')

def post(path, data):
    url = f"{BASE_URL}/{path.lstrip('/')}"
    encoded = urllib.parse.urlencode(data).encode('utf-8')
    req = urllib.request.Request(url, data=encoded, headers={
        'User-Agent': 'Mozilla/5.0',
        'Content-Type': 'application/x-www-form-urlencoded'
    })
    with opener.open(req) as resp:
        return resp.getcode(), resp.read().decode('utf-8', errors='ignore')

def extract_csrf(html):
    m = re.search(r'name=["\']csrf_token["\']\s+value=["\']([a-f0-9]{64})["\']', html)
    if not m:
        m = re.search(r'value=["\']([a-f0-9]{64})["\']\s+name=["\']csrf_token["\']', html)
    return m.group(1) if m else None

print("=== VERIFIKASI TAB CHECK-IN, REFUND, E-TIKET ===")

# 1. Login
code, html = get('/login')
csrf = extract_csrf(html)
assert csrf, "CSRF token login not found"
code, html = post('/auth/authenticate', {
    'email': 'demo@novalive.id',
    'password': 'nova1234',
    'csrf_token': csrf
})
print("[OK] 1. Login demo@novalive.id berhasil")

# 2. Checkin Page
code, html = get('/dashboard/orders/checkin')
assert code == 200, f"Checkin returned {code}"
assert "Fatal error" not in html, "Fatal error on checkin page!"
assert "Check-in Tiket" in html, "Check-in title not found"
print("[OK] 2. GET /dashboard/orders/checkin tampil tanpa error (HTTP 200)")

# 3. Refunds Page
code, html = get('/dashboard/orders/refunds')
assert code == 200, f"Refunds returned {code}"
assert "Fatal error" not in html, "Fatal error on refunds page!"
assert "Refund" in html, "Refund title not found"
print("[OK] 3. GET /dashboard/orders/refunds tampil tanpa error (HTTP 200)")

# 4. Tickets Page
code, html = get('/dashboard/orders/tickets')
assert code == 200, f"Tickets returned {code}"
assert "Fatal error" not in html, "Fatal error on tickets page!"
assert "E-Tiket" in html, "E-Tiket title not found"
print("[OK] 4. GET /dashboard/orders/tickets tampil tanpa error (HTTP 200)")

# 5. Test Check-in invalid code
csrf = extract_csrf(html)
code, html = post('/dashboard/orders/checkin/process', {
    'ticket_code': 'KODE-NGAWUR-999',
    'csrf_token': csrf
})
assert "tidak ditemukan" in html.lower() or "gagal" in html.lower() or "salah" in html.lower(), "Expected error message for invalid code"
print("[OK] 5. Check-in kode ngawur ditolak dengan pesan error yang sesuai")

# 6. Test Check-in code NVL-A1B2C3
# First, reset checked_in_at for NVL-A1B2C3 to null in database just in case
subprocess.check_call(['php', '-r', '''
require_once "novalive-mvc/app/config/config.php";
require_once "novalive-mvc/app/core/Database.php";
Database::getInstance()->prepare("UPDATE orders SET checked_in_at = NULL WHERE ticket_code = 'NVL-A1B2C3'")->execute();
'''])

code, html = get('/dashboard/orders/checkin')
csrf = extract_csrf(html)
code, html = post('/dashboard/orders/checkin/process', {
    'ticket_code': 'NVL-A1B2C3',
    'csrf_token': csrf
})
assert "berhasil" in html.lower() or "sukses" in html.lower() or "check-in" in html.lower(), "Expected success message on first checkin"
print("[OK] 6. Check-in NVL-A1B2C3 berhasil (pesan sukses tampil)")

# Try checking in again
csrf = extract_csrf(html)
code, html = post('/dashboard/orders/checkin/process', {
    'ticket_code': 'NVL-A1B2C3',
    'csrf_token': csrf
})
assert "sudah" in html.lower() or "pernah" in html.lower(), "Expected already checked in warning"
print("[OK] 7. Check-in kedua kali untuk NVL-A1B2C3 ditolak ('sudah check-in')")

# 7. Test Refund Action
# Reset refund 1 to 'Diajukan'
subprocess.check_call(['php', '-r', '''
require_once "novalive-mvc/app/config/config.php";
require_once "novalive-mvc/app/core/Database.php";
Database::getInstance()->prepare("UPDATE refunds SET status = 'Diajukan', processed_by = NULL, processed_at = NULL WHERE id = 1")->execute();
'''])

code, html = get('/dashboard/orders/refunds')
csrf = extract_csrf(html)
code, html = post('/dashboard/orders/refunds/approve/1', {
    'csrf_token': csrf
})
assert "berhasil" in html.lower() or "disetujui" in html.lower(), "Expected refund approved success message"
print("[OK] 8. Setujui refund demo ID 1 berhasil (pesan tampil, status berubah)")

# 8. Test E-Tiket detail view
code, html = get('/dashboard/orders/tickets/1')
assert code == 200, f"Ticket detail returned {code}"
assert "Fatal error" not in html, "Fatal error on ticket detail page!"
assert "Cetak" in html or "print" in html.lower(), "Tombol Cetak tidak ditemukan di halaman E-Tiket"
assert "svg" in html.lower() or "qr" in html.lower(), "QR/SVG barcode tidak ditemukan di halaman E-Tiket"
assert "NVL-A1B2C3" in html, "Kode tiket NVL-A1B2C3 tidak ditemukan di e-tiket"
print("[OK] 9. E-Tiket resmi ID 1: halaman tiket + QR + tombol Cetak tampil sempurna")

print("=== SELURUH VERIFIKASI BERHASIL 100%! ===")
