const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const source = fs.readFileSync(path.join(__dirname, '../../public/js/ios-compat.js'), 'utf8');

function browser({ supported = true, visual = true } = {}) {
  const properties = {};
  const events = {};
  const frames = [];
  const listener = prefix => (name, fn) => { events[prefix + name] = fn; };
  const viewport = { height: 700, offsetTop: 0, scale: 1, addEventListener: listener('viewport:') };
  const window = {
    CSS: { supports: () => supported }, innerHeight: 600,
    visualViewport: visual ? viewport : null,
    addEventListener: listener('window:'),
    requestAnimationFrame: fn => frames.push(fn),
  };
  const document = {
    documentElement: { style: { setProperty: (name, value) => { properties[name] = value; } } },
    addEventListener: listener('document:'),
  };
  const context = vm.createContext({ window, document, CSS: window.CSS });
  return { window, viewport, properties, events, frames, run: () => vm.runInContext(source, context), flush: () => frames.splice(0).forEach(fn => fn()) };
}

test('does not modify unsupported browsers', () => {
  const b = browser({ supported: false }); b.run();
  assert.deepEqual(b.properties, {});
  assert.deepEqual(b.events, {});
});

test('tracks the visible area when the keyboard opens and closes', () => {
  const b = browser(); b.run();
  assert.equal(b.properties['--ios-visible-height'], '700px');
  b.viewport.height = 280; b.viewport.offsetTop = 100;
  b.events['viewport:resize'](); b.events['viewport:scroll']();
  assert.equal(b.frames.length, 1);
  b.flush();
  assert.equal(b.properties['--ios-visible-height'], '280px');
  assert.equal(b.properties['--ios-visible-top'], '100px');
  b.viewport.height = 700; b.viewport.offsetTop = 0;
  b.events['viewport:resize'](); b.flush();
  assert.equal(b.properties['--ios-visible-height'], '700px');
  assert.equal(b.properties['--ios-visible-top'], '0px');
});

test('preserves pinch zoom and resumes after returning to normal scale', () => {
  const b = browser(); b.run();
  b.viewport.scale = 2; b.viewport.height = 350;
  b.events['viewport:resize'](); b.flush();
  assert.equal(b.properties['--ios-visible-height'], '700px');
  b.viewport.scale = 1; b.viewport.height = 390;
  b.events['window:resize'](); b.flush();
  assert.equal(b.properties['--ios-visible-height'], '390px');
});

test('falls back to window height when VisualViewport is unavailable', () => {
  const b = browser({ visual: false }); b.run();
  assert.equal(b.properties['--ios-visible-height'], '600px');
  assert.equal(b.properties['--ios-visible-top'], '0px');
});

test('refreshes restored pages and Livewire navigation without duplicate listeners', () => {
  const b = browser(); b.run();
  const resize = b.events['window:resize']; b.run();
  assert.equal(b.events['window:resize'], resize);
  b.viewport.height = 500;
  b.events['window:pageshow'](); b.flush();
  assert.equal(b.properties['--ios-visible-height'], '500px');
  b.viewport.height = 450;
  b.events['document:livewire:navigated'](); b.flush();
  assert.equal(b.properties['--ios-visible-height'], '450px');
});
