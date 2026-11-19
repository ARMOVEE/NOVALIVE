</main>

<style>
/* WRAP & GRID */
.footer .wrap,
.legal-wrap {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 20px;
}

/* FOOTER */
.footer {
  background: var(--navy2, #0E3254);
  color: #DCE8F2;
  border-top: 1px solid rgba(255, 255, 255, .16);
  padding: 48px 0 20px;
}
.footer .logo {
  color: #fff;
  margin-bottom: 10px;
  text-decoration: none;
  font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif);
  font-size: 1.65rem;
  font-weight: 800;
  display: inline-block;
  letter-spacing: -.01em;
}
.footer .logo .logo-live {
  color: var(--gold, #EAD4A2);
}
.footer .logo-img {
  height: 38px;
}
.footer-grid {
  display: grid;
  grid-template-columns: 1.2fr 1fr 1fr 1fr;
  gap: 28px;
}
.footer h3 {
  font-size: 1.05rem;
  color: #fff;
  margin: 0 0 12px;
  font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif);
}
.footer nav a {
  display: block;
  text-decoration: none;
  color: #DCE8F2;
  padding: 5px 0;
  font-weight: 500;
  min-height: 2rem;
}
.footer nav a:hover {
  color: var(--gold, #EAD4A2);
  text-decoration: underline;
  text-underline-offset: 3px;
}
.social {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  margin-top: 12px;
}
.social a {
  width: 42px;
  height: 42px;
  border: 1.5px solid var(--gold-deep, #D4B475);
  background: #fff;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  color: var(--navy2, #0E3254);
  text-decoration: none;
  font-size: .8rem;
  transition: background .2s, color .2s;
}
.social a:hover {
  background: var(--gold, #EAD4A2);
  color: var(--navy2, #0E3254);
  border-color: var(--gold, #EAD4A2);
}
.footer-bottom {
  border-top: 1px solid rgba(255, 255, 255, .18);
  margin-top: 28px;
  padding-top: 14px;
  text-align: center;
  font-weight: 600;
}
@media (max-width: 1024px) {
  .footer-grid {
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 560px) {
  .footer-grid {
    grid-template-columns: 1fr;
  }
}

/* RANTAI COVER (desktop, tanpa reduced-motion): Jelajahi menutupi Event Minggu Ini, footer menutupi Jelajahi */
@media (min-width: 1025px) and (prefers-reduced-motion: no-preference) {
  .footer {
    position: relative;
    z-index: 30;
    margin-top: -120px;
    box-shadow: 0 -18px 38px rgba(14, 50, 84, .18);
  }
  body:not(:has(#kota)) .footer {
    margin-top: 40px;
    box-shadow: none;
  }
}

/* PITA LEGAL: di bawah footer, paling bawah halaman */
.legal-band {
  background: var(--navy2, #0E3254);
  color: #D7DEE8;
  padding: 44px 0 34px;
  overflow: hidden;
  position: relative;
  z-index: 30;
}
.legal-wordmark {
  font-family: var(--font-display, 'Plus Jakarta Sans', sans-serif);
  font-weight: 800;
  line-height: .92;
  letter-spacing: -.03em;
  text-align: center;
  white-space: nowrap;
  font-size: clamp(3.2rem, 13.2vw, 11.5rem);
  color: var(--sky, #509FC8);
  margin: 0 0 28px;
  user-select: none;
}
.legal-wordmark span {
  color: var(--gold, #EAD4A2);
}
.legal-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px 28px;
  flex-wrap: wrap;
}
.legal-links {
  display: flex;
  gap: 8px 28px;
  flex-wrap: wrap;
}
.legal-links a {
  color: #E6EDF5;
  text-decoration: none;
  font-weight: 600;
  font-size: .95rem;
  padding: 6px 0;
}
.legal-links a:hover {
  color: #fff;
  text-decoration: underline;
  text-underline-offset: 4px;
}
.legal-divider {
  border: 0;
  border-top: 1px solid rgba(255, 255, 255, .18);
  margin: 22px 0 18px;
}
.legal-note {
  color: #9FB3C8;
  font-size: .88rem;
  line-height: 1.75;
  margin: 0 auto;
  max-width: 1020px;
  text-align: center;
}
@media (max-width: 760px) {
  .legal-band {
    padding: 32px 0 28px;
  }
  .legal-wordmark {
    font-size: clamp(2.6rem, 13.5vw, 4.5rem);
    margin-bottom: 22px;
  }
  .legal-row {
    flex-direction: column;
    align-items: flex-start;
  }
  .legal-links {
    flex-direction: column;
    gap: 2px;
  }
}
</style>

<footer class="footer" id="bikin">
  <div class="wrap">
    <div class="footer-grid">
      <div>
        <a class="logo" href="#atas" aria-label="NOVA LIVE"><span class="logo-nova">NOVA</span> <span class="logo-live">LIVE</span></a>
        <p style="color:rgba(220,232,242,.88);line-height:1.6;margin:0 0 12px;">Platform tiket event Indonesia: konser, pameran, wahana, dan olahraga, jelas, aman, dan bisa dipercaya.</p>
        <div class="social" aria-label="Media sosial">
          <a href="#atas" aria-label="WhatsApp">WA</a><a href="#atas" aria-label="Instagram">IG</a><a href="#atas" aria-label="TikTok">TT</a><a href="#atas" aria-label="X">X</a><a href="#atas" aria-label="LinkedIn">in</a><a href="#atas" aria-label="YouTube">▶</a>
        </div>
      </div>
      <nav aria-label="Tentang Kami"><h3>Tentang Kami</h3><a href="#atas">Tentang Kami</a><a href="#atas">Our Journey</a><a href="#faq">Hubungi Kami</a><a href="#faq">Biaya</a></nav>
      <nav aria-label="Informasi"><h3>Informasi</h3><a href="#faq">Syarat &amp; Ketentuan</a><a href="#faq">Kebijakan Privasi</a><a href="#faq">FAQ</a><a href="#faq">Tiket Gelang</a><a href="#faq">Validasi Tiket</a></nav>
      <nav aria-label="Kategori Event"><h3>Kategori Event</h3><a href="#populer" data-cat-link="Musik">Musik</a><a href="#populer" data-cat-link="Pameran">Pameran</a><a href="#populer" data-cat-link="Wahana">Wahana</a><a href="#populer" data-cat-link="Olahraga">Olahraga</a><a href="#populer" data-cat-link="Semua">Semua Kategori</a></nav>
    </div>
  </div>
</footer>

<section class="legal-band" id="faq" aria-label="Informasi legal NOVA LIVE">
  <div class="legal-wrap">
    <p class="legal-wordmark" aria-hidden="true">NOVA <span>LIVE</span></p>
    <div class="legal-row">
      <nav class="legal-links" aria-label="Tautan legal">
        <a href="#faq">Kebijakan Privasi</a>
        <a href="#faq">Syarat &amp; Ketentuan</a>
        <a href="#faq">Prosedur Pengaduan</a>
      </nav>
    </div>
    <hr class="legal-divider">
    <p class="legal-note">© 2026 NOVA LIVE. Hak Cipta Dilindungi.</p>
  </div>
</section>

</body>
</html>
