(() => {
  const loadingBar = document.querySelector('[data-page-loading]');
  const errorBanner = document.querySelector('[data-page-error]');
  const errorMessage = errorBanner?.querySelector('[data-error-message]');

  const showLoading = () => {
    if (!loadingBar) return;
    loadingBar.hidden = false;
    loadingBar.setAttribute('aria-hidden', 'false');
  };

  const hideLoading = () => {
    if (!loadingBar) return;
    loadingBar.hidden = true;
    loadingBar.setAttribute('aria-hidden', 'true');
  };

  const showError = (message) => {
    if (!errorBanner || !errorMessage) return;
    errorMessage.textContent = message;
    errorBanner.hidden = false;
  };

  document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || form.dataset.noLoading === 'true') return;

    const submitter = event.submitter;
    if (submitter instanceof HTMLButtonElement || submitter instanceof HTMLInputElement) {
      submitter.setAttribute('aria-busy', 'true');
      submitter.disabled = true;
    }

    showLoading();
  });

  document.addEventListener('click', (event) => {
    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

    const link = event.target.closest('a[href]');
    if (!link || link.target === '_blank' || link.hasAttribute('download')) return;

    const url = new URL(link.href, window.location.href);
    if (url.origin !== window.location.origin || url.hash || url.href === window.location.href) return;

    showLoading();
  });

  window.addEventListener('pageshow', hideLoading);
  document.addEventListener('DOMContentLoaded', hideLoading);

  window.addEventListener('unhandledrejection', () => {
    hideLoading();
    showError('Ada bagian halaman yang gagal dimuat. Muat ulang halaman, lalu coba lagi.');
  });

  window.addEventListener('error', (event) => {
    if (!(event.error instanceof Error)) return;
    hideLoading();
    showError('Ada bagian halaman yang gagal dimuat. Muat ulang halaman, lalu coba lagi.');
  });

  document.querySelectorAll('[data-dismiss-error]').forEach((button) => {
    button.addEventListener('click', () => {
      if (errorBanner) errorBanner.hidden = true;
    });
  });
})();
