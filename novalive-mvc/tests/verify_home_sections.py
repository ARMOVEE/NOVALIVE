import urllib.request

req = urllib.request.Request('http://localhost:8000/', headers={'User-Agent': 'Mozilla/5.0'})
with urllib.request.urlopen(req) as r:
    html = r.read().decode('utf-8', errors='ignore')

print('HTTP Status:', r.getcode())
print('Contains id="minggu":', 'id="minggu"' in html)
print('Contains id="kota":', 'id="kota"' in html)
print('Contains Event Populer:', 'Event Populer' in html)
print('Contains Jelajahi Event di Kotamu:', 'Jelajahi Event di Kotamu' in html)
print('Contains Event Seru Untukmu:', 'Event Seru Untukmu' in html)
print('Contains Kategori Event:', 'Kategori Event' in html)
print('Contains footer:', 'class="footer"' in html or 'footer' in html)

assert 'id="minggu"' not in html, "id='minggu' should not be in homepage"
assert 'id="kota"' not in html, "id='kota' should not be in homepage"
assert 'Event Populer' not in html, "'Event Populer' should not be in homepage"
assert 'Jelajahi Event di Kotamu' not in html, "'Jelajahi Event di Kotamu' should not be in homepage"
assert 'Event Seru Untukmu' in html, "'Event Seru Untukmu' should be in homepage"
assert 'Kategori Event' in html, "'Kategori Event' should be in homepage"

print("\n[VERIFIKASI SUKSES] Section Event Populer dan Jelajahi Event di Kotamu telah berhasil dihapus dari homepage!")
