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

pos_kat = html.find('id="populer"')
pos_seru = html.find('id="seru"')
print(f'Index Kategori: {pos_kat}, Index Event Seru: {pos_seru}')

assert 'id="minggu"' not in html, "id='minggu' should not be in homepage"
assert 'id="kota"' not in html, "id='kota' should not be in homepage"
assert 'Event Populer' not in html, "'Event Populer' should not be in homepage"
assert 'Jelajahi Event di Kotamu' not in html, "'Jelajahi Event di Kotamu' should not be in homepage"
assert pos_kat > 0, "Kategori Event section not found"
assert pos_seru > 0, "Event Seru Untukmu section not found"
assert pos_kat < pos_seru, f"Kategori ({pos_kat}) harus sebelum Event Seru ({pos_seru})"

print("\n[VERIFIKASI SUKSES] Kategori Event berhasil dipindahkan ke atas Event Seru Untukmu!")
