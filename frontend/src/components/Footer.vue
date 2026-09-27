<template>
  <footer class="site-footer">
    <div class="container footer-top">
      <div class="brand-block">
        <strong class="brand">
          IT Group
        </strong>
        <span class="address-line">C. Salvador de Madariaga, 1</span>
        <span class="address-line">28027 Madrid</span>
        <span class="address-line">Spain</span>
      </div>
      <div class="social-block">
        <span class="follow-label">Follow us on</span>
        <a href="#" class="social-icon" aria-label="Twitter / X" target="_blank" rel="noopener">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.89-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21-.36.1-.74.15-1.13.15-.28 0-.55-.03-.81-.08.55 1.71 2.14 2.95 4.03 2.98A8.6 8.6 0 0 1 2 19.54 12.13 12.13 0 0 0 8.29 21.5c7.55 0 11.68-6.25 11.68-11.68 0-.18 0-.35-.01-.53.8-.58 1.5-1.3 2.05-2.13-.74.33-1.53.55-2.35.65A4.1 4.1 0 0 0 22.46 6z"/>
          </svg>
        </a>
        <a href="#" class="social-icon" aria-label="YouTube" target="_blank" rel="noopener">
          <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
            <path d="M23.5 6.2a3.02 3.02 0 0 0-2.12-2.14C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.38.56A3.02 3.02 0 0 0 .5 6.2 31.6 31.6 0 0 0 0 12a31.6 31.6 0 0 0 .5 5.8 3.02 3.02 0 0 0 2.12 2.14C4.5 20.5 12 20.5 12 20.5s7.5 0 9.38-.56a3.02 3.02 0 0 0 2.12-2.14A31.6 31.6 0 0 0 24 12a31.6 31.6 0 0 0-.5-5.8ZM9.6 15.6V8.4l6.4 3.6-6.4 3.6Z"/>
          </svg>
        </a>
      </div>
    </div>
    <div class="footer-divider"></div>
    <div class="container footer-bottom">
      <span class="copyright">
        Copyright © {{ year }} IT Hote ls. All rights reserved.
      </span>
      <span class="credit">
        Photos by Felix Mooneeram & Serge Kutuzov 
      </span>
    </div>
  </footer>
</template>

<script setup>
const year = new Date().getFullYear()
</script>

<style scoped>
.site-footer {
  background-color: #16171a;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 32px;
}

.footer-top {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 24px;
  padding: 28px 32px 20px;
  flex-wrap: wrap;
}

.brand-block {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.brand {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 13px;
  font-weight: 700;
  color: #ffffff;
  letter-spacing: 0.01em;
  margin-bottom: 4px;
}

.address-line {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 13px;
  line-height: 1.6;
  color: rgba(255, 255, 255, 0.55);
}

.social-block {
  display: flex;
  align-items: center;
  gap: 14px;
}

.follow-label {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 12px;
  color: rgba(255, 255, 255, 0.5);
  margin-right: 4px;
}

.social-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  color: #ffffff;
  transition: color 0.15s ease;
}

.social-icon:hover {
  color: #e0a020;
}

.footer-divider {
  border-top: 1px solid rgba(255, 255, 255, 0.08);
}

.footer-bottom {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  padding: 16px 32px;
  flex-wrap: wrap;
}

.copyright,
.credit {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 12px;
  color: rgba(255, 255, 255, 0.4);
}

.credit a {
  color: rgba(255, 255, 255, 0.65);
  text-decoration: underline;
}

.credit a:hover {
  color: #e0a020;
}

@media (max-width: 640px) {
  .footer-top,
  .footer-bottom {
    padding-left: 20px;
    padding-right: 20px;
  }

  .footer-top {
    flex-direction: column;
    gap: 16px;
  }

  .footer-bottom {
    flex-direction: column;
    align-items: flex-start;
    gap: 8px;
  }
}
</style>