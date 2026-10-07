(function () {
  if (window.PipelineMaps) return;
  const config = document.currentScript.dataset;
  let loading = null;
  let geocoder = null;
  let authFailed = false;

  function showError(element, error) {
    if (!element || !element.isConnected) return;
    let message = element.parentElement.querySelector('.google-map-error');
    if (!message) {
      message = document.createElement('div');
      message.className = 'google-map-error alert alert-warning small my-2';
      message.setAttribute('role', 'alert');
      element.insertAdjacentElement('afterend', message);
    }
    message.textContent = error.message || 'Google Maps gagal dimuat. Silakan coba lagi.';
  }

  function load() {
    if (authFailed) return Promise.reject(new Error('Google Maps menolak API key. Periksa aktivasi API, billing, dan izin domain di Google Cloud.'));
    if (loading) return loading;
    if (!config.key) return Promise.reject(new Error('API key Google Maps belum diatur.'));
    loading = new Promise((resolve, reject) => {
      if (window.google?.maps?.Map && window.google.maps.marker?.AdvancedMarkerElement) {
        resolve(window.google.maps);
        return;
      }
      const script = document.createElement('script');
      const timeout = window.setTimeout(() => fail(), 20000);
      function fail() {
        window.clearTimeout(timeout);
        script.remove();
        loading = null;
        reject(new Error('Google Maps gagal dimuat. Periksa koneksi lalu coba lagi.'));
      }
      window.__pipelineGoogleMapsReady = () => {
        window.clearTimeout(timeout);
        resolve(window.google.maps);
      };
      script.onerror = fail;
      const params = new URLSearchParams({ key: config.key, callback: '__pipelineGoogleMapsReady', loading: 'async', libraries: 'marker,geocoding', v: 'weekly', language: 'id', region: 'ID' });
      script.src = 'https://maps.googleapis.com/maps/api/js?' + params;
      script.async = true;
      document.head.appendChild(script);
    });
    return loading;
  }

  window.gm_authFailure = function () {
    authFailed = true;
    document.querySelectorAll('[data-google-map]').forEach(element => showError(element,
      new Error('Google Maps menolak API key. Periksa aktivasi API, billing, dan izin domain di Google Cloud.')));
  };

  window.PipelineMaps = {
    load,
    showError,
    options(extra) {
      return Object.assign({ center: { lat: -7.150975, lng: 110.140259 }, zoom: 8, mapId: config.mapId || 'DEMO_MAP_ID', streetViewControl: false, mapTypeControl: false, fullscreenControl: true, gestureHandling: 'cooperative' }, extra);
    },
    async geocode(request) {
      const maps = await load();
      if (!geocoder) geocoder = new maps.Geocoder();
      try {
        const response = await geocoder.geocode(request);
        return response.results || [];
      } catch (error) {
        const status = String(error.code || error.message);
        if (status.includes('ZERO_RESULTS')) return [];
        if (status.includes('REQUEST_DENIED')) {
          throw new Error('Pencarian alamat ditolak Google. Periksa billing, aktivasi Geocoding API, dan izin API key di Google Cloud.');
        }
        throw new Error('Pencarian alamat Google Maps gagal. Periksa Geocoding API dan izin API key.');
      }
    }
  };
})();
