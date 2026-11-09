const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');

const source = fs.readFileSync(path.join(__dirname, '../../resources/views/livewire/prospects/form.blade.php'), 'utf8');
const script = source.match(/<script>\s*([\s\S]*?)<\/script>/)[1];

function page(formPresent = true) {
  const events = {};
  const hooks = {};
  const frames = [];
  let boots = 0;
  let assets = 0;
  const document = {
    addEventListener(name, fn) { (events[name] ||= []).push(fn); },
    getElementById() { return null; },
    querySelector(selector) {
      if (selector === '.prospect-form-page') { boots++; return formPresent ? {} : null; }
      return null;
    },
    querySelectorAll() { return []; },
    head: { appendChild() { assets++; } },
  };
  const Livewire = { hook(name, fn) { hooks[name] = fn; } };
  const window = { Livewire, addEventListener() {} };
  const context = vm.createContext({ document, window, Livewire, console,
    requestAnimationFrame(fn) { frames.push(fn); return frames.length; },
  });
  vm.runInContext(script, context);
  for (const fn of events['livewire:init']) fn();
  return {
    hooks, events, frames,
    get boots() { return boots; }, get assets() { return assets; },
    flush() { frames.splice(0).forEach(fn => fn()); },
  };
}

test('notification polling does not reinitialize the prospect form', () => {
  const p = page();
  for (let i = 0; i < 10; i++) p.hooks.morphed({ component: { el: { matches: () => false } } });
  assert.equal(p.frames.length, 0);
  assert.equal(p.boots, 0);
});

test('form updates and navigation in one frame initialize controls once', () => {
  const p = page();
  p.events.DOMContentLoaded[0]();
  p.events['livewire:navigated'][0]();
  for (let i = 0; i < 5; i++) p.hooks.morphed({ component: { el: { matches: () => true } } });
  assert.equal(p.frames.length, 1);
  p.flush();
  assert.equal(p.boots, 1);
  assert.equal(p.assets, 0, 'map assets are not loaded while opening or typing in the form');
});

test('form initialization stops when navigating to another page', () => {
  const p = page(false);
  p.events['livewire:navigated'][0]();
  p.flush();
  assert.equal(p.boots, 1);
  assert.equal(p.assets, 0);
});
