const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const source = fs.readFileSync(path.join(__dirname, '../../resources/views/livewire/prospects/form.blade.php'), 'utf8');
const script = source.match(/<script>\s*([\s\S]*?)<\/script>/)[1];

function page() {
  const events = {}, handlers = {}, frames = [], writes = [];
  const hint = { textContent: '' };
  let componentId = 'form-1', resolveSelection;
  const owner = { getAttribute: () => componentId };
  const input = () => ({ value: '', dataset: {}, sent: [], closest: () => owner,
    dispatchEvent(event) { this.sent.push(event.type); } });
  let currentInput = input();
  const button = { dataset: {}, addEventListener(name, fn) { handlers[name] = fn; } };
  const document = {
    getElementById(id) { return ({ no_hp_input: currentInput, btnPickContact: button, contactHint: hint })[id] || null; },
    addEventListener(name, fn) { (events[name] ||= []).push(fn); },
    querySelector(selector) { return selector === '.prospect-form-page' ? {} : null; },
    querySelectorAll() { return []; },
  };
  const Livewire = { find(id) { return { $set(...args) { writes.push([id, ...args]); } }; } };
  const window = { Livewire, isSecureContext: true, addEventListener() {} };
  const navigator = { contacts: {
    getProperties: async () => ['tel', 'name'],
    select: () => new Promise(resolve => { resolveSelection = resolve; }),
  } };
  vm.runInNewContext(script, { window, document, navigator, console, Event,
    requestAnimationFrame(fn) { frames.push(fn); return frames.length; },
  });
  events.DOMContentLoaded[0](); frames.splice(0).forEach(fn => fn());
  return {
    window, hint, writes,
    get input() { return currentInput; },
    replaceInput() { currentInput = input(); },
    navigateAway() { componentId = 'another-form'; },
    async open() { handlers.click({ preventDefault() {} }); await new Promise(setImmediate); },
    async select(contacts) { resolveSelection(contacts); await new Promise(setImmediate); },
  };
}

test('contact selection updates the replacement input and deferred Livewire state', async () => {
  const p = page(), old = p.input;
  await p.open(); p.replaceInput();
  await p.select([{ name: ['Dani'], tel: ['+62 812-3456-7890'] }]);
  assert.equal(p.input.value, '081234567890');
  assert.equal(old.value, '');
  assert.deepEqual(p.input.sent, ['input', 'change']);
  assert.deepEqual(p.writes, [['form-1', 'no_hp', '081234567890', false]]);
});

test('selects the first nonempty phone number and normalizes international prefixes', async () => {
  const p = page(); await p.open();
  await p.select([{ name: ['Dani'], tel: ['', '0062 813 456 789'] }]);
  assert.equal(p.input.value, '0813456789');
});

test('cancel preserves the existing phone number', async () => {
  const p = page(); p.input.value = '0811111111';
  await p.open(); await p.select([]);
  assert.equal(p.input.value, '0811111111');
  assert.equal(p.writes.length, 0);
});

test('contact selection cannot overwrite a different form after navigation', async () => {
  const p = page(); await p.open(); p.navigateAway();
  await p.select([{ tel: ['08123456'] }]);
  assert.equal(p.input.value, '');
  assert.equal(p.writes.length, 0);
});

test('Android callback synchronizes the same input and Livewire state', () => {
  const p = page(); p.window.setPickedContactFromAndroid('Dani', 'tel:+62-812-3456;ext=9');
  assert.equal(p.input.value, '08123456');
  assert.deepEqual(p.writes, [['form-1', 'no_hp', '08123456', false]]);
});
