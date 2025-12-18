<div id="marketing-strip" class="fixed top-0 left-0 w-full bg-gradient-to-r from-primary-600 via-primary-500 to-primary-600 text-white z-[9999]">
  <div class="max-w-screen-xl mx-auto px-4 py-2 flex items-center justify-between gap-3 text-sm">
    <div class="flex items-center gap-3">
      <span class="hidden md:inline font-semibold">{{ t('Stay connected with Askilah Group') }}</span>
      <span class="hidden font-semibold">{{ t('Connect with us') }}</span>
      <span class="opacity-80 hidden md:inline">{{ t('Watch our latest updates and follow our journey') }}</span>
    </div>
    <div class="flex items-center gap-2">
      <a href="https://www.youtube.com/channel/UCmHHT15TMvSYD4iELO7xOJw" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-full transition">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.5 6.2s-.2-1.6-.8-2.3c-.8-.8-1.8-.8-2.2-.9C17.9 2.7 12 2.7 12 2.7h0s-5.9 0-8.5.3c-.4 0-1.4.1-2.2.9-.6.7-.8 2.3-.8 2.3S0 8.1 0 10.1v1.7c0 2 .2 3.9.2 3.9s.2 1.6.8 2.3c.8.8 1.9.8 2.4.9 1.8.2 7.6.3 7.6.3s5.9 0 8.5-.3c.4 0 1.4-.1 2.2-.9.6-.7.8-2.3.8-2.3s.2-1.9.2-3.9v-1.7c0-2-.2-3.9-.2-3.9ZM9.6 14.6V7.9l6.4 3.4-6.4 3.3Z"/></svg>
        <span>{{ t('YouTube') }}</span>
      </a>
      <a href="https://www.instagram.com/askilagroup?igsh=ZDh1czJ1Mm9rbjkw&utm_source=qr" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-full transition">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2C4.2 2 2 4.2 2 7v10c0 2.8 2.2 5 5 5h10c2.8 0 5-2.2 5-5V7c0-2.8-2.2-5-5-5H7Zm10 2c1.7 0 3 1.3 3 3v10c0 1.7-1.3 3-3 3H7c-1.7 0-3-1.3-3-3V7c0-1.7 1.3-3 3-3h10Zm-5 3.5A5.5 5.5 0 1 0 17.5 13 5.5 5.5 0 0 0 12 7.5Zm0 2A3.5 3.5 0 1 1 8.5 13 3.5 3.5 0 0 1 12 9.5Zm5.8-2.6a1 1 0 1 0 1.4 1.4 1 1 0 0 0-1.4-1.4Z"/></svg>
        <span>{{ t('Instagram') }}</span>
      </a>
      <!-- <a href="https://www.tiktok.com/@alaskiila?_t=ZS-904r7B2T9aU&_r=1" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-full transition">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.7 2c.5 2.2 2.2 3.9 4.4 4.4v3a8.4 8.4 0 0 1-4.3-1.2v6.7a6.9 6.9 0 1 1-6.9-6.9c.4 0 .8 0 1.2.1v3.1a3.9 3.9 0 0 0-1.2-.2 3.9 3.9 0 1 0 3.9 3.9V2h2.9Z"/></svg>
        <span>{{ t('TikTok') }}</span>
      </a> -->
      <a href="https://www.facebook.com/Asklagroup?locale=ar_AR" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-full transition">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13 22v-8h3l1-4h-4V7.5c0-1.2.3-2 2-2h2V2.1C16.6 2 15.2 2 14 2c-3 0-5 1.8-5 5.1V10H6v4h3v8h4Z"/></svg>
        <span>{{ t('Facebook') }}</span>
      </a>
    </div>
  </div>
</div>
<script>
  (function(){
    try {
      var key = 'marketing_strip_dismissed_v1';
      var el = document.getElementById('marketing-strip');
      var btn = document.getElementById('strip-close');
      if (!el || !btn) return;
      if (localStorage.getItem(key) === '1') {
        el.style.display = 'none';
        return;
      }
      btn.addEventListener('click', function(){
        el.style.display = 'none';
        localStorage.setItem(key, '1');
      });
    } catch(e) {}
  })();
</script>
