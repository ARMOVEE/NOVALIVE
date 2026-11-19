import urllib.request
import urllib.parse
import http.cookiejar
import re

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

# 1. GET login page
login_page = opener.open('http://localhost:8000/auth/login').read().decode('utf-8')
m = re.search(r'name=["\']csrf_token["\']\s+value=["\']([a-f0-9]+)["\']', login_page)
csrf_token = m.group(1) if m else ''
print('CSRF token obtained:', csrf_token[:10] + '...')

# 2. Authenticate
login_data = urllib.parse.urlencode({
    'email': 'demo@novalive.id',
    'password': 'nova1234',
    'csrf_token': csrf_token
}).encode('utf-8')

req = urllib.request.Request('http://localhost:8000/auth/authenticate', data=login_data, method='POST')
res = opener.open(req)
print('Login response code:', res.status, res.geturl())

# 3. Access /dashboard/homepage/banners
res_banners = opener.open('http://localhost:8000/dashboard/homepage/banners')
content_banners = res_banners.read().decode('utf-8')
print('/dashboard/homepage/banners ->', res_banners.status, 'HTML length:', len(content_banners))
assert 'Banner Homepage' in content_banners, "Page title 'Banner Homepage' not found"
assert 'Tambah Slide' in content_banners, "Button 'Tambah Slide' not found"
assert 'js-soon' not in content_banners.split('<!-- 5. Homepage -->')[1].split('<!-- 6. Laporan -->')[0], "Sidebar icon 5 still has js-soon"

# 4. Access /dashboard/homepage/layout
res_layout = opener.open('http://localhost:8000/dashboard/homepage/layout')
content_layout = res_layout.read().decode('utf-8')
print('/dashboard/homepage/layout ->', res_layout.status, 'HTML length:', len(content_layout))
print('URL:', res_layout.geturl())
print('First 500 chars:', content_layout[:500])
assert 'Event Seru Untukmu' in content_layout, "Event Seru Untukmu not found in layout"
assert 'Event Populer' in content_layout, "Event Populer not found in layout"
assert 'Banner Slide' in content_layout, "Tab 'Banner Slide' not found"
assert 'Tata Letak Homepage' in content_layout, "Tab 'Tata Letak Homepage' not found"

print("ALL HTTP ENDPOINT VERIFICATIONS PASSED!")
