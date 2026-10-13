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
      el.contentDocument = {
        fonts: { ready: Promise.resolve() }, head: element('head'),
        getElementById(id) { return this.head.children.find(child => child.id === id); },
      };
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
    open: (settings = {}) => events['print-prospect-validation']({ detail: { html: '<html><body>Validasi</body></html>', filename: 'validasi-kc.pdf', scope: 'kc', ...settings } }),
  };
}

test('preview prints only its ready letter frame and preserves the filename', async () => {
  const b = browser(); b.open();
  const dialog = b.elements.find(el => el.tag === 'dialog');
  const frame = b.elements.find(el => el.tag === 'iframe');
  const print = b.elements.find(el => el.textContent === 'Cetak / Simpan PDF');
  assert.equal(dialog.open, true);
  assert.match(dialog.style.cssText, /position:fixed;inset:0;margin:auto/);
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

test('preview switches paper dimensions without changing letter data', async () => {
  const b = browser(); b.open({ paper: 'F4', orientation: 'portrait' });
  const frame = b.elements.find(el => el.tag === 'iframe');
  const paper = b.elements.find(el => el.attributes['aria-label'] === 'Kertas');
  const orientation = b.elements.find(el => el.attributes['aria-label'] === 'Orientasi');
  await frame.events.load();
  const style = frame.contentDocument.head.children[0];
  assert.match(style.textContent, /size: 210mm 330mm/);
  for (const [p, o, expected] of [['A4', 'portrait', '210mm 297mm'], ['A4', 'landscape', '297mm 210mm'], ['F4', 'landscape', '330mm 210mm']]) {
    paper.value = p; orientation.value = o;
    paper.events.change(); orientation.events.change();
    assert.ok(style.textContent.includes(`size: ${expected}`));
  }
  assert.equal(frame.contentDocument.head.children.length, 1);
  assert.equal(frame.srcdoc, '<html><body>Validasi</body></html>');
});
