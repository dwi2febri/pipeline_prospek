const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const source = fs.readFileSync(path.join(__dirname, '../../public/js/prospect-validation-print.js'), 'utf8');

function browser() {
  const events = {}, elements = [];
  let prints = 0;
  function element(tag) {
    const el = {
      tag, style: {}, attributes: {}, children: [], events: {},
      setAttribute(key, value) { this.attributes[key] = value; },
      addEventListener(name, fn) { this.events[name] = fn; },
      append(...children) { this.children.push(...children); },
      showModal() { this.open = true; },
      close() { this.open = false; },
      remove() { this.removed = true; },
    };
    if (tag === 'iframe') {
      el.contentWindow = { focus() {}, print() { prints++; } };
      el.contentDocument = { fonts: { ready: Promise.resolve() } };
    }
    elements.push(el);
    return el;
  }
  const modal = element('modal');
  const context = vm.createContext({
    window: { addEventListener(name, fn) { events[name] = fn; } },
    document: {
      addEventListener(name, fn) { events[name] = fn; },
      createElement: element, getElementById() { return modal; }, body: element('body'),
    },
  });
  const run = () => vm.runInContext(source, context);
  run();
  return { events, elements, modal, run, prints: () => prints,
    open: () => events['print-prospect-validation']({ detail: { html: '<html><body>Validasi</body></html>', filename: 'validasi-kc.pdf', scope: 'kc' } }),
  };
}

test('preview prints only its ready letter frame and preserves the filename', async () => {
  const b = browser(); b.open();
  const dialog = b.elements.find(el => el.tag === 'dialog');
  const frame = b.elements.find(el => el.tag === 'iframe');
  const print = b.elements.find(el => el.textContent === 'Cetak / Simpan PDF');
  assert.equal(dialog.open, true);
  assert.equal(b.modal.children[0], dialog);
  assert.equal(frame.attributes.sandbox, 'allow-same-origin allow-modals');
  print.events.click();
  assert.equal(b.prints(), 0);
  await frame.events.load();
  assert.equal(frame.contentDocument.title, 'validasi-kc');
  print.events.click();
  assert.equal(b.prints(), 1);
  b.elements.find(el => el.textContent === 'Tutup').events.click();
  assert.equal(dialog.removed, true);
});

test('opening another preview and navigating remove old letter data', () => {
  const b = browser(); b.open();
  const first = b.elements.find(el => el.tag === 'dialog');
  b.open();
  assert.equal(first.removed, true);
  const second = b.elements.filter(el => el.tag === 'dialog')[1];
  b.events['livewire:navigating']();
  assert.equal(second.removed, true);
});

test('reloading the script does not register duplicate handlers', () => {
  const b = browser();
  const original = b.events['print-prospect-validation'];
  b.run();
  assert.equal(b.events['print-prospect-validation'], original);
  b.events['print-prospect-validation']({ detail: {} });
  assert.equal(b.elements.some(el => el.tag === 'dialog'), false);
});
