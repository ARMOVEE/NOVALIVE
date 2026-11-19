import urllib.request
import urllib.parse
import http.cookiejar
import re

cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))

# 1. GET login page to get CSRF token
resp = opener.open('http://localhost:8000/login')
html = resp.read().decode('utf-8')
token_match = re.search(r'name=["\']csrf_token["\']\s+value=["\']([^"\']+)["\']', html)
token = token_match.group(1) if token_match else ''
print('CSRF token obtained:', token[:16] + '...')

# 2. POST login
data = urllib.parse.urlencode({'csrf_token': token, 'email': 'demo@novalive.id', 'password': 'nova1234'}).encode('utf-8')
resp = opener.open('http://localhost:8000/auth/authenticate', data=data)
print('Logged in successfully. URL:', resp.geturl())

# 3. Test Finance CSV export
resp = opener.open('http://localhost:8000/dashboard/finance?export=csv')
csv_data = resp.read().decode('utf-8')
lines = csv_data.splitlines()
print('\n--- Finance CSV Export ---')
print('Status:', resp.status, '| Total Lines:', len(lines))
print('Header:', lines[0] if lines else 'EMPTY')
if len(lines) > 1:
    print('Row 1:', lines[1])

# 4. Test Analytics CSV export
resp = opener.open('http://localhost:8000/dashboard/reports/analytics?export=csv')
csv_data = resp.read().decode('utf-8')
lines = csv_data.splitlines()
print('\n--- Analytics CSV Export ---')
print('Status:', resp.status, '| Total Lines:', len(lines))
print('Header:', lines[0] if lines else 'EMPTY')
if len(lines) > 1:
    print('Row 1:', lines[1])

# 5. Check all Dashboard pages as logged-in user
for page in [
    '/dashboard',
    '/dashboard/finance',
    '/dashboard/reports',
    '/dashboard/reports/analytics',
    '/dashboard/reports/logs',
    '/dashboard/reports/notifications'
]:
    res = opener.open('http://localhost:8000' + page)
    print(f'HTTP {res.status} {page}')
