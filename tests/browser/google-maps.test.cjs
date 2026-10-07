const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');
const source = fs.readFileSync(path.join(__dirname, '../../public/js/google-maps.js'), 'utf8');

function runtime(key = 'test-key') {
  const scripts = [];
  let geocode = async () => ({ results: [{ formatted_address: 'Semarang' }] });
  let geocoderCount = 0;
  const maps = {
    Map: class {}, marker: { AdvancedMarkerElement: class {} },
    Geocoder: class {
      constructor() { geocoderCount++; }
      geocode(request) { return geocode(request); }
    },
  };
  const window = { setTimeout: () => 1, clearTimeout() {} };
  const document = {
    currentScript: { dataset: { key, mapId: 'test-map' } },
    createElement: () => ({ remove() { this.removed = true; } }),
    head: { appendChild(script) { scripts.push(script); } },
    querySelectorAll: () => [],
  };
  const context = vm.createContext({ window, document, URLSearchParams });
  vm.runInContext(source, context);
  return {
    api: window.PipelineMaps, window, scripts,
    ready() { window.google = { maps }; window.__pipelineGoogleMapsReady(); },
    setGeocode(fn) { geocode = fn; }, countGeocoders: () => geocoderCount,
  };
}

test('maps load once and share the same promise across all maps', async () => {
  const r = runtime();
  const first = r.api.load();
  assert.equal(r.api.load(), first);
  assert.equal(r.scripts.length, 1);
  const url = new URL(r.scripts[0].src);
  assert.equal(url.hostname, 'maps.googleapis.com');
  assert.equal(url.searchParams.get('libraries'), 'marker,geocoding');
  assert.equal(url.searchParams.get('language'), 'id');
  r.ready();
  await first;
  await r.api.load();
  assert.equal(r.scripts.length, 1);
});

test('a failed SDK download can be retried', async () => {
  const r = runtime();
  const first = r.api.load();
  r.scripts[0].onerror();
  await assert.rejects(first, /gagal dimuat/);
  const retry = r.api.load();
  assert.equal(r.scripts.length, 2);
  r.ready();
  await retry;
});

test('one geocoder handles searches and treats zero results separately from API failures', async () => {
  const r = runtime();
  const load = r.api.load(); r.ready(); await load;
  const results = await r.api.geocode({ address: 'Semarang' });
  assert.equal(results[0].formatted_address, 'Semarang');
  r.setGeocode(async () => { throw { code: 'ZERO_RESULTS' }; });
  assert.equal((await r.api.geocode({ address: 'Unknown' })).length, 0);
  r.setGeocode(async () => { throw { code: 'REQUEST_DENIED' }; });
  await assert.rejects(r.api.geocode({ address: 'Semarang' }), /Geocoding API/);
  assert.equal(r.countGeocoders(), 1);
});

test('missing credentials and authentication failures are reported', async () => {
  await assert.rejects(runtime('').api.load(), /belum diatur/);
  const r = runtime();
  r.window.gm_authFailure();
  await assert.rejects(r.api.load(), /billing/);
});

test('map settings preserve per-map centers and zoom while supplying the map ID', () => {
  const r = runtime();
  const settings = r.api.options({ center: { lat: -6.9, lng: 110.4 }, zoom: 16 });
  assert.equal(settings.mapId, 'test-map');
  assert.equal(settings.center.lat, -6.9);
  assert.equal(settings.zoom, 16);
  assert.equal(settings.streetViewControl, false);
});
