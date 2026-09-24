(function () {
  'use strict';
  if (window.__iosCompatReady || !window.CSS || !CSS.supports('-webkit-touch-callout', 'none')) return;
  window.__iosCompatReady = true;
  var queued = false;
  function updateViewport() {
    queued = false;
    var viewport = window.visualViewport;
    // Preserve native pinch zoom instead of relaying its dimensions into the layout.
    if (viewport && Math.abs(viewport.scale - 1) > 0.05) return;
    document.documentElement.style.setProperty('--ios-visible-height', (viewport ? viewport.height : window.innerHeight) + 'px');
    document.documentElement.style.setProperty('--ios-visible-top', (viewport ? viewport.offsetTop : 0) + 'px');
  }
  function scheduleUpdate() {
    if (queued) return;
    queued = true;
    window.requestAnimationFrame(updateViewport);
  }
  window.addEventListener('resize', scheduleUpdate);
  window.addEventListener('pageshow', scheduleUpdate);
  document.addEventListener('livewire:navigated', scheduleUpdate);
  if (window.visualViewport) {
    window.visualViewport.addEventListener('resize', scheduleUpdate);
    window.visualViewport.addEventListener('scroll', scheduleUpdate);
  }
  updateViewport();
})();
