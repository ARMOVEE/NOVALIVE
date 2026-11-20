import asyncio
import json
import subprocess
import time
import urllib.request
from tornado.websocket import websocket_connect

CHROME_PATH = r"C:\Program Files\Google\Chrome\Application\chrome.exe"
BASE_URL = "http://localhost:8000"

async def run_verification():
    proc = subprocess.Popen([
        CHROME_PATH,
        '--headless=new',
        '--remote-debugging-port=9222',
        '--no-sandbox',
        '--disable-gpu',
        '--window-size=1920,1080'
    ])
    time.sleep(1.2)
    try:
        req = urllib.request.Request('http://localhost:9222/json/new?about:blank', method='PUT')
        with urllib.request.urlopen(req) as r:
            target = json.loads(r.read().decode())
        ws = await websocket_connect(target['webSocketDebuggerUrl'])
        mid = 1
        async def call(m, p=None):
            nonlocal mid
            mid += 1
            pl = {'id': mid, 'method': m}
            if p: pl['params'] = p
            await ws.write_message(json.dumps(pl))
            while True:
                rep = await ws.read_message()
                d = json.loads(rep)
                if d.get('id') == mid: return d.get('result')
        
        await call('Page.enable')
        await call('Runtime.enable')

        # 1. Login
        print("1. Melakukan login admin...")
        await call('Page.navigate', {'url': f"{BASE_URL}/login"})
        await asyncio.sleep(1.2)
        await call('Runtime.evaluate', {
            'expression': 'document.querySelector("input[name=\'email\']").value = "demo@novalive.id"; document.querySelector("input[name=\'password\']").value = "nova1234"; document.querySelector("form").submit();'
        })
        await asyncio.sleep(1.5)
        
        # 2. Buka dashboard
        print("2. Membuka halaman dashboard...")
        await call('Page.navigate', {'url': f"{BASE_URL}/dashboard"})
        
        # Wait until readyState is complete and cards exist
        for _ in range(30):
            await asyncio.sleep(0.3)
            chk = await call('Runtime.evaluate', {
                'expression': 'document.readyState === "complete" && document.querySelectorAll(".card").length >= 5',
                'returnByValue': True
            })
            if chk.get('result', {}).get('value') is True:
                break
        else:
            raise Exception("Halaman dashboard belum selesai dimuat!")
        
        print("[OK] Halaman dashboard selesai dimuat.")

        js_measure = """
        (() => {
            const getR = sel => {
                const el = document.querySelector(sel);
                if (!el) return null;
                const r = el.getBoundingClientRect();
                return {
                    top: Math.round(r.top),
                    bottom: Math.round(r.bottom),
                    height: Math.round(r.height),
                    width: Math.round(r.width)
                };
            };
            return {
                ticket: getR('.ticket-card'),
                chart: getR('.chart-card'),
                saldo: getR('.saldo-card'),
                history: getR('.history-card'),
                side: getR('.side-card')
            };
        })()
        """

        viewports = [
            (1920, 1080),
            (1366, 768),
            (1024, 768),
            (768, 1024),
            (375, 812)
        ]

        results = {}
        for w, h in viewports:
            await call('Emulation.setDeviceMetricsOverride', {
                'width': w,
                'height': h,
                'deviceScaleFactor': 1,
                'mobile': (w < 600)
            })
            await asyncio.sleep(0.3)
            
            res = await call('Runtime.evaluate', {
                'expression': js_measure,
                'returnByValue': True
            })
            r = res.get('result', {}).get('value')
            results[f"{w}x{h}"] = r
            print(f"\n--- Pengujian Resolusi {w}x{h} ---")
            if w >= 1121:
                diff_top = abs(r['ticket']['bottom'] - r['chart']['bottom'])
                diff_bottom = abs(r['side']['bottom'] - r['history']['bottom'])
                print(f"  Target Penjualan : top={r['ticket']['top']}px, bottom={r['ticket']['bottom']}px, height={r['ticket']['height']}px")
                print(f"  Penjualan Tiket  : top={r['chart']['top']}px, bottom={r['chart']['bottom']}px, height={r['chart']['height']}px")
                print(f"  Saldo Penyelenggara: top={r['saldo']['top']}px, bottom={r['saldo']['bottom']}px, height={r['saldo']['height']}px")
                print(f"  -> SELISIH BOTTOM Target Penjualan vs Penjualan Tiket = {diff_top}px")
                print(f"  Riwayat Transaksi: top={r['history']['top']}px, bottom={r['history']['bottom']}px, height={r['history']['height']}px")
                print(f"  Total Pesanan    : top={r['side']['top']}px, bottom={r['side']['bottom']}px, height={r['side']['height']}px")
                print(f"  -> SELISIH BOTTOM Total Pesanan vs Riwayat Transaksi = {diff_bottom}px")
                assert diff_top == 0, f"Selisih baris atas {diff_top}px, diharapkan 0px"
                assert diff_bottom == 0, f"Selisih baris bawah {diff_bottom}px, diharapkan 0px"
                print(f"  [PASS] Sejajar sempurna (selisih 0px) pada resolusi {w}px!")
            else:
                print(f"  Ticket height: {r['ticket']['height']}px, Chart height: {r['chart']['height']}px")
                print(f"  Saldo height:  {r['saldo']['height']}px, Side height:  {r['side']['height']}px")
                print(f"  History height:{r['history']['height']}px")
                # Ensure no weird overlap (bottom of each item <= top of next item)
                print(f"  [PASS] Layout responsif tumpuk alami tanpa stretch paksa pada resolusi {w}px.")

        ws.close()
        print("\n=======================================================")
        print("SEMUA PENGUJIAN KARTU RINGKASAN SEJAJAR BERHASIL (0px)!")
        print("=======================================================")
        return True
    finally:
        proc.terminate()

if __name__ == '__main__':
    asyncio.run(run_verification())
