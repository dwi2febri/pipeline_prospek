(function () {
    if (window.prospectValidationPrintReady) return;
    window.prospectValidationPrintReady = true;

    let preview = null;
    const closePreview = () => {
        if (preview) {
            preview.close();
            preview.remove();
            preview = null;
        }
    };
    document.addEventListener('livewire:navigating', closePreview);

    window.addEventListener('print-prospect-validation', function (event) {
        const { html, filename, scope } = event.detail || {};
        if (typeof html !== 'string' || !html) return;
        closePreview();

        const dialog = document.createElement('dialog');
        preview = dialog;
        dialog.setAttribute('aria-label', 'Pratinjau surat validasi');
        dialog.setAttribute('wire:ignore', '');
        dialog.style.cssText = 'width:94vw;max-width:1450px;height:90vh;max-height:90vh;padding:0;border:1px solid #dce4ef;border-radius:16px;background:#f3f6fa;color:#1e293b;box-shadow:0 20px 80px #0f172a40;font:12px/1.5 Arial,sans-serif;';

        const toolbar = document.createElement('div');
        toolbar.style.cssText = 'display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:10px;padding:12px 16px;background:white;border-bottom:1px solid #dce4ef;';
        const caption = document.createElement('div');
        caption.textContent = 'Surat Validasi Pengajuan Closing - pilih Simpan sebagai PDF pada dialog cetak.';
        toolbar.append(caption);
        const actions = document.createElement('div');
        actions.style.cssText = 'display:flex;gap:8px;';
        const printButton = document.createElement('button');
        printButton.type = 'button';
        printButton.textContent = 'Cetak / Simpan PDF';
        printButton.disabled = true;
        printButton.style.cssText = 'border:0;border-radius:9px;padding:8px 14px;background:#2563eb;color:white;font:inherit;cursor:pointer;';
        const closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.textContent = 'Tutup';
        closeButton.style.cssText = 'border:1px solid #dce4ef;border-radius:9px;padding:8px 14px;background:white;color:#334155;font:inherit;cursor:pointer;';
        closeButton.addEventListener('click', closePreview);
        actions.append(printButton, closeButton);
        toolbar.append(actions);

        const frame = document.createElement('iframe');
        frame.title = 'Surat Validasi Pengajuan Closing';
        frame.style.cssText = 'display:block;width:100%;flex:1;min-height:0;border:0;background:white;';
        // The server-rendered letter contains escaped data. Disable scripts in the preview.
        frame.setAttribute('sandbox', 'allow-same-origin allow-modals');
        const layout = document.createElement('div');
        layout.style.cssText = 'display:flex;flex-direction:column;height:100%;';
        layout.append(toolbar, frame);
        dialog.append(layout);
        dialog.addEventListener('cancel', function (e) { e.preventDefault(); closePreview(); });

        printButton.addEventListener('click', function () {
            if (!frame.contentWindow || printButton.disabled) return;
            frame.contentWindow.focus();
            frame.contentWindow.print();
        });
        frame.addEventListener('load', async function () {
            if (!frame.contentDocument) return;
            frame.contentDocument.title = String(filename || 'surat-validasi').replace(/\.pdf$/i, '');
            if (frame.contentDocument.fonts) await frame.contentDocument.fonts.ready;
            if (preview !== dialog) return;
            printButton.disabled = false;
        }, { once: true });

        // Stay within Bootstrap's active modal focus boundary.
        const modal = document.getElementById(scope === 'kc' ? 'modalDetailKc' : 'modalDetailPegawai');
        frame.srcdoc = html;
        (modal || document.body).append(dialog);
        dialog.showModal();
    });
})();
