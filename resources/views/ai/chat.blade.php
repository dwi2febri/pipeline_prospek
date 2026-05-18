<div class="container-fluid px-0">
    <style>
        .ai-page{
            min-height:calc(100vh - 90px);
            padding:18px 18px 24px;
        }

        .ai-chat-shell{
            width:100%;
            max-width:none;
            margin:0 auto;
            padding-left:clamp(0px, 1.2vw, 14px);
            padding-right:clamp(0px, 1.2vw, 14px);
            box-sizing:border-box;
        }

        .ai-topbar{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:14px;
            margin-bottom:14px;
        }

        .ai-topbar-left{
            min-width:0;
        }

        .ai-topbar-right{
            display:flex;
            align-items:center;
            gap:10px;
            flex-shrink:0;
        }

        .ai-page-title{
            font-size:clamp(1.45rem, 1.8vw, 1.95rem);
            font-weight:900;
            color:#0f172a;
            line-height:1.08;
            margin-bottom:4px;
            letter-spacing:-.02em;
        }

        .ai-page-subtitle{
            color:#64748b;
            font-size:.95rem;
            line-height:1.55;
            max-width:760px;
        }

        .ai-back-btn{
            border-radius:999px;
            padding:10px 18px;
            white-space:nowrap;
            min-height:44px;
        }

        .ai-chat-card{
            width:100%;
            max-width:100%;
            border:1px solid #d8e5d8;
            border-radius:28px;
            background:linear-gradient(180deg,#efeae2 0%,#ece5dd 100%);
            box-shadow:0 18px 40px rgba(15,23,42,.08);
            overflow:hidden;
        }

        .ai-chat-head{
            padding:16px 18px;
            border-bottom:1px solid #d8e5d8;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:14px;
            background:linear-gradient(180deg,#075e54 0%,#0b7a6e 100%);
        }

        .ai-chat-head-left{
            min-width:0;
        }

        .ai-chat-title{
            font-size:clamp(1.18rem, 1.3vw, 1.45rem);
            font-weight:900;
            color:#ffffff;
            line-height:1.2;
            margin-bottom:2px;
            letter-spacing:-.02em;
        }

        .ai-chat-sub{
            color:rgba(255,255,255,.88);
            font-size:.84rem;
        }

        .ai-new-btn{
            border-radius:999px;
            padding:10px 16px;
            white-space:nowrap;
            color:#075e54;
            background:#ffffff;
            border:1px solid rgba(255,255,255,.8);
            min-height:42px;
        }

        .ai-new-btn:hover{
            color:#075e54;
            background:#f8fafc;
        }

        .ai-chat-body{
            height:clamp(360px, 52vh, 620px);
            overflow:auto;
            padding:18px;
            background:#e5ddd5;
            background-image:
                radial-gradient(rgba(255,255,255,.15) 1px, transparent 1px);
            background-size:18px 18px;
        }

        .ai-msg{
            display:flex;
            margin-bottom:14px;
        }

        .ai-msg.user{
            justify-content:flex-end;
        }

        .ai-msg.bot{
            justify-content:flex-start;
        }

        .ai-bubble{
            max-width:min(76%, 760px);
            padding:13px 15px;
            border-radius:16px;
            white-space:pre-wrap;
            line-height:1.65;
            font-size:.95rem;
            word-break:break-word;
            box-shadow:0 4px 10px rgba(15,23,42,.05);
        }

        .ai-msg.user .ai-bubble{
            background:#d9fdd3;
            color:#111827;
            border-bottom-right-radius:6px;
        }

        .ai-msg.bot .ai-bubble{
            background:#ffffff;
            color:#111827;
            border:1px solid rgba(15,23,42,.05);
            border-bottom-left-radius:6px;
        }

        .ai-chat-foot{
            padding:14px;
            border-top:1px solid #d8e5d8;
            background:#f0f2f5;
        }

        .ai-input-wrap{
            border:1px solid #d1d5db;
            border-radius:18px;
            background:#ffffff;
            padding:10px 12px;
        }

        .ai-chat-foot textarea{
            width:100%;
            min-height:92px;
            max-height:210px;
            resize:vertical;
            border:0;
            outline:none;
            box-shadow:none;
            font-size:.94rem;
            line-height:1.55;
            background:transparent;
            color:#111827;
        }

        .ai-chat-foot textarea::placeholder{
            color:#9ca3af;
        }

        .ai-foot-actions{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            margin-top:10px;
            flex-wrap:wrap;
        }

        .ai-foot-hint{
            color:#64748b;
            font-size:.84rem;
        }

        .ai-btn-group{
            display:flex;
            align-items:center;
            gap:10px;
            margin-left:auto;
        }

        .ai-btn-light,
        .ai-btn-send{
            border-radius:999px;
            padding:10px 18px;
            min-width:128px;
            font-weight:700;
            font-size:.92rem;
        }

        .ai-btn-send{
            background:#0b7a6e;
            border-color:#0b7a6e;
        }

        .ai-btn-send:hover{
            background:#09695f;
            border-color:#09695f;
        }

        .ai-typing{
            display:inline-flex;
            align-items:center;
            gap:8px;
        }

        .ai-dot{
            width:8px;
            height:8px;
            border-radius:50%;
            background:#94a3b8;
            animation:aiBlink 1.2s infinite ease-in-out;
        }

        .ai-dot:nth-child(2){ animation-delay:.15s; }
        .ai-dot:nth-child(3){ animation-delay:.3s; }

        @keyframes aiBlink{
            0%,80%,100%{opacity:.25;transform:scale(.9);}
            40%{opacity:1;transform:scale(1);}
        }

        @media (min-width: 1400px){
            .ai-page{
                padding-left:22px;
                padding-right:22px;
            }

            .ai-chat-shell{
                padding-left:0;
                padding-right:0;
            }
        }

        @media (max-width: 991.98px){
            .ai-chat-shell{
                max-width:100%;
                padding-left:6px;
                padding-right:6px;
            }

            .ai-bubble{
                max-width:86%;
            }
        }

        @media (max-width: 767.98px){
            .ai-page{
                padding:12px 6px calc(86px + env(safe-area-inset-bottom, 0px));
                min-height:auto;
            }

            .ai-chat-shell{
                padding-left:0;
                padding-right:0;
            }

            .ai-topbar{
                flex-direction:column;
                align-items:stretch;
                gap:10px;
                margin-bottom:10px;
            }

            .ai-topbar-right{
                width:100%;
                justify-content:flex-start;
            }

            .ai-back-btn{
                width:100%;
                justify-content:center;
            }

            .ai-chat-card{
                border-radius:22px;
                overflow:hidden;
            }

            .ai-chat-head{
                padding:14px 14px;
                flex-direction:column;
                align-items:stretch;
                gap:10px;
            }

            .ai-chat-title{
                font-size:1.05rem;
            }

            .ai-chat-sub{
                font-size:.8rem;
            }

            .ai-new-btn{
                width:100%;
                justify-content:center;
                min-height:40px;
            }

            .ai-chat-body{
                height:42vh;
                min-height:260px;
                padding:12px;
            }

            .ai-bubble{
                max-width:92%;
                font-size:.91rem;
                padding:11px 13px;
                line-height:1.55;
            }

            .ai-chat-foot{
                padding:12px;
                position:relative;
                z-index:2;
            }

            .ai-input-wrap{
                border-radius:18px;
                padding:9px 11px;
            }

            .ai-chat-foot textarea{
                min-height:82px;
                font-size:.92rem;
                resize:none;
            }

            .ai-foot-actions{
                display:flex;
                flex-direction:column;
                align-items:stretch;
                gap:10px;
                margin-top:10px;
            }

            .ai-foot-hint{
                order:2;
                font-size:.76rem;
                line-height:1.35;
                text-align:left;
                color:#64748b;
            }

            .ai-btn-group{
                order:1;
                width:100%;
                margin-left:0;
                display:grid;
                grid-template-columns:1fr 1fr;
                gap:10px;
            }

            .ai-btn-group .btn,
            .ai-btn-light,
            .ai-btn-send{
                width:100%;
                min-width:0;
                min-height:44px;
                padding:10px 12px;
                font-size:.9rem;
                border-radius:999px;
            }

            /*
              FIX UTAMA:
              CSS global layout sebelumnya menangkap button[class*="ai"]
              lalu menjadikan tombol Chat Baru/Kirim/Kosongkan sebagai floating.
              Bagian ini memaksa semua tombol di halaman AI tetap normal.
            */
            .ai-page .ai-back-btn,
            .ai-page .ai-new-btn,
            .ai-page .ai-btn-light,
            .ai-page .ai-btn-send,
            .ai-page button[class*="ai"],
            .ai-page a[class*="ai"],
            .ai-page .btn[class*="ai"]{
                position:static !important;
                left:auto !important;
                right:auto !important;
                top:auto !important;
                bottom:auto !important;
                transform:none !important;
                z-index:auto !important;
                float:none !important;
            }

            .ai-page .ai-btn-send{
                background:#0b7a6e !important;
                border-color:#0b7a6e !important;
                color:#fff !important;
            }

            .ai-page .ai-btn-light{
                background:#fff !important;
                border-color:#e5e7eb !important;
                color:#111827 !important;
            }

            .ai-page .ai-new-btn{
                background:#fff !important;
                color:#075e54 !important;
                border-color:rgba(255,255,255,.8) !important;
            }
        }
    </style>

    <div class="ai-page">
        <div class="ai-chat-shell">
            <div class="ai-topbar">
                <div class="ai-topbar-right">

                </div>
            </div>

            <div class="ai-chat-card">
                <div class="ai-chat-head">
                    <div class="ai-chat-head-left">
                        <div class="ai-chat-title">E-Prospek AI Assistant <a href="{{ route('prospects.index') }}" class="btn btn-light ai-back-btn">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a></div>
                        <div class="ai-chat-sub"><i class="bi bi-stars"></i> Gemini</div>
                    </div>

                    <button type="button" class="btn ai-new-btn" id="btnNewChat">
                        <i class="bi bi-arrow-clockwise me-1"></i> Chat Baru
                    </button>
                </div>

                <div class="ai-chat-body" id="chatBody">
                    <div class="ai-msg bot">
                        <div class="ai-bubble">Halo, saya siap membantu.

Contoh:
berapa total prospek saya?
berapa follow up di cabang saya?
tampilkan ringkasan status prospek
buat analisa singkat data prospek saya
apa itu deposito?</div>
                    </div>
                </div>

                <div class="ai-chat-foot">
                    <input type="hidden" id="conversationId" value="">
                    <input type="hidden" id="csrfToken" value="{{ csrf_token() }}">

                    <div class="ai-input-wrap">
                        <textarea id="chatInput" placeholder="Tulis pertanyaan..."></textarea>
                    </div>

                    <div class="ai-foot-actions">
                        <div class="ai-foot-hint">
                            Enter untuk kirim • Shift + Enter untuk baris baru
                        </div>

                        <div class="ai-btn-group">
                            <button type="button" class="btn btn-light ai-btn-light" id="btnClearInput">
                                Kosongkan
                            </button>
                            <button type="button" class="btn btn-primary ai-btn-send" id="btnSendChat">
                                <i class="bi bi-send me-1"></i> Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const chatBody = document.getElementById('chatBody');
    const chatInput = document.getElementById('chatInput');
    const btnSend = document.getElementById('btnSendChat');
    const btnClear = document.getElementById('btnClearInput');
    const btnNewChat = document.getElementById('btnNewChat');
    const conversationId = document.getElementById('conversationId');
    const csrfToken = document.getElementById('csrfToken').value;

    function appendMessage(role, text, isTyping = false) {
        const row = document.createElement('div');
        row.className = 'ai-msg ' + role;

        const bubble = document.createElement('div');
        bubble.className = 'ai-bubble';

        if (isTyping) {
            bubble.innerHTML = '<span class="ai-typing"><span class="ai-dot"></span><span class="ai-dot"></span><span class="ai-dot"></span></span> Sedang memproses...';
        } else {
            bubble.textContent = text;
        }

        row.appendChild(bubble);
        chatBody.appendChild(row);
        chatBody.scrollTop = chatBody.scrollHeight;

        return row;
    }

    function setSendingState(isSending) {
        btnSend.disabled = isSending;
        btnClear.disabled = isSending;
        btnNewChat.disabled = isSending;
        chatInput.disabled = isSending;

        btnSend.innerHTML = isSending
            ? '<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...'
            : '<i class="bi bi-send me-1"></i> Kirim';
    }

    async function sendMessage() {
        const message = (chatInput.value || '').trim();
        if (!message) {
            chatInput.focus();
            return;
        }

        appendMessage('user', message);
        chatInput.value = '';

        setSendingState(true);
        const typingRow = appendMessage('bot', '', true);

        try {
            const res = await fetch(@json(route('ai.chat.ask')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    conversation_id: conversationId.value
                })
            });

            let data = {};
            try {
                data = await res.json();
            } catch (e) {
                data = {};
            }

            if (typingRow && typingRow.parentNode) {
                typingRow.parentNode.removeChild(typingRow);
            }

            if (!res.ok) {
                appendMessage('bot', data.message || 'Maaf, proses AI gagal.');
                return;
            }

            if (!data.ok) {
                appendMessage('bot', data.message || 'Maaf, AI belum bisa menjawab sekarang.');
                return;
            }

            if (data.conversation_id) {
                conversationId.value = data.conversation_id;
            }

            appendMessage('bot', data.answer || 'Tidak ada jawaban.');
        } catch (e) {
            if (typingRow && typingRow.parentNode) {
                typingRow.parentNode.removeChild(typingRow);
            }
            appendMessage('bot', 'Terjadi error koneksi AI.');
        } finally {
            setSendingState(false);
            chatInput.focus();
        }
    }

    btnSend.addEventListener('click', sendMessage);

    chatInput.addEventListener('keydown', function(e){
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    btnClear.addEventListener('click', function(){
        chatInput.value = '';
        chatInput.focus();
    });

    btnNewChat.addEventListener('click', function(){
        conversationId.value = '';
        chatBody.innerHTML = '';
        appendMessage('bot', 'Chat baru dimulai. Silakan tanya lagi.');
        chatInput.focus();
    });
})();
</script>
