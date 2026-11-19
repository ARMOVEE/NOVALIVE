import re
import urllib.request
import urllib.parse
import http.cookiejar

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

print("=== TAHAP 5 HTTP INTEGRATION TEST ===")

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


# 2. Check /dashboard/promotions
code, html = get('/dashboard/promotions')
assert code == 200, f"Expected 200, got {code}"
assert 'NOVAHEMAT' in html, "NOVAHEMAT not in promotions page"
assert 'TULUSJKT' in html, "TULUSJKT not in promotions page"
assert 'Segera Hadir' not in html, "'Segera Hadir' found in promotions page!"
print("[OK] 2. GET /dashboard/promotions HTTP 200 & memuat kode promo")

# 3. Check /dashboard/settings (Profil Organisasi)
code, html = get('/dashboard/settings')
assert code == 200, f"Expected 200, got {code}"
assert 'Profil Penyelenggara' in html, "'Profil Penyelenggara' not in settings"
assert 'Sanggar Gembira' in html, "'Sanggar Gembira' not in settings"
assert 'Segera Hadir' not in html, "'Segera Hadir' found in settings page!"
print("[OK] 3. GET /dashboard/settings HTTP 200 & memuat profil organisasi")

# 4. Check /dashboard/settings/account (Akun Saya)
code, html = get('/dashboard/settings/account')
assert code == 200, f"Expected 200, got {code}"
assert 'Profil Akun Saya' in html, "'Profil Akun Saya' not in account settings"
assert 'Ganti Kata Sandi' in html, "'Ganti Kata Sandi' not in account settings"
assert 'demo@novalive.id' in html, "'demo@novalive.id' not in account settings"
print("[OK] 4. GET /dashboard/settings/account HTTP 200 & memuat akun saya")

# 5. Check /dashboard/settings/users (Kelola Pengguna)
code, html = get('/dashboard/settings/users')
assert code == 200, f"Expected 200, got {code}"
assert 'Kelola Pengguna &amp; Hak Akses Staf' in html or 'Kelola Pengguna' in html, "Kelola pengguna title not found"
assert 'manager@novalive.id' in html, "manager@novalive.id not found in user list"
assert 'staff@novalive.id' in html, "staff@novalive.id not found in user list"
print("[OK] 5. GET /dashboard/settings/users HTTP 200 & memuat kelola pengguna")

# 6. Test Store Promo Code
csrf = extract_csrf(html)
code, html = post('/dashboard/promotions/store', {
    'code': 'TESTDISKON2026',
    'type': 'persen',
    'value': '25',
    'event_id': '',
    'quota': '100',
    'starts_at': '2026-01-01',
    'ends_at': '2026-12-31',
    'csrf_token': csrf
})
print("[OK] 6. POST /dashboard/promotions/store berhasil")

code, html = get('/dashboard/promotions')
assert 'TESTDISKON2026' in html, "TESTDISKON2026 was not listed after creation"
print("[OK] 7. Kode promo TESTDISKON2026 tampil pada daftar")

# 7. Find new promo id and delete it
m = re.search(r'/dashboard/promotions/delete/(\d+)', html)
assert m, "Delete promo action URL not found"
new_promo_id = m.group(1)
csrf = extract_csrf(html)

code, html = post(f'/dashboard/promotions/delete/{new_promo_id}', {
    'csrf_token': csrf
})
print(f"[OK] 8. POST /dashboard/promotions/delete/{new_promo_id} berhasil")

code, html = get('/dashboard/promotions')
assert 'TESTDISKON2026' not in html, "TESTDISKON2026 still present after deletion"
print("[OK] 9. Kode promo TESTDISKON2026 berhasil dihapus dari daftar")

print("=== SELURUH INTEGRASI HTTP TAHAP 5 BERHASIL SEMPURNA! ===")
