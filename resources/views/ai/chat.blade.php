@extends('layouts.bootstrap')

@section('content')
<div class="container-fluid px-0">
    <style>
        .ai-chat-wrap{
            max-width:900px;
            margin:0 auto;
        }
        .ai-chat-card{
            border:1px solid #e8eef6;
            border-radius:28px;
            background:linear-gradient(180deg,#ffffff 0%,#fbfdff 100%);
            box-shadow:0 16px 32px rgba(15,23,42,.08);
            overflow:hidden;
        }
        .ai-chat-head{
            padding:18px 20px;
            border-bottom:1px solid #edf2f7;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
        }
        .ai-chat-title{
            font-size:1.2rem;
            font-weight:900;
            color:#0f172a;
        }
        .ai-chat-sub{
            color:#64748b;
            font-size:.92rem;
            margin-top:4px;
        }
        .ai-chat-body{
            min-height:55vh;
            max-height:65vh;
            overflow:auto;
            padding:18px;
            background:#f8fbff;
        }
        .ai-msg{
            display:flex;
            margin-bottom:14px;
        }
        .ai-msg.user{ justify-content:flex-end; }
        .ai-bubble{
            max-width:82%;
            padding:14px 16px;
            border-radius:20px;
            white-space:pre-wrap;
            line-height:1.6;
            font-size:.95rem;
        }
        .ai-msg.user .ai-bubble{
            background:linear-gradient(135deg,#2563eb 0%,#0ea5e9 100%);
            color:#fff;
            border-bottom-right-radius:8px;
        }
        .ai-msg.bot .ai-bubble{
            background:#fff;
            color:#0f172a;
            border:1px solid #e7edf6;
            border-bottom-left-radius:8px;
        }
        .ai-chat-foot{
            padding:16px;
            border-top:1px solid #edf2f7;
            background:#fff;
        }
        .ai-chat-foot textarea{
            min-height:90px;
            resize:none;
            border-radius:18px;
        }
    </style>

    <div class="ai-chat-wrap">
        <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
            <div>
                <div class="fw-bold fs-3">Chat AI</div>
                <div class="text-muted">
                    Bisa jawab data aplikasi yang terlihat user dan pertanyaan umum.
                    Mode gratis lokal ini tidak memakai pencarian web real-time.
                </div>
            </div>

            <a href="{{ route('prospects.index') }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="ai-chat-card">
            <div class="ai-chat-head">
                <div>
                    <div class="ai-chat-title">E-Prospek AI Assistant</div>
                    <div class="ai-chat-sub">Tanya data prospek, status, cabang, atau pertanyaan umum.</div>
                </div>

                <button type="button" class="btn btn-outline-secondary rounded-pill px-3" id="btnNewChat">
                    <i class="bi bi-arrow-clockwise me-1"></i> Chat Baru
                </button>
            </div>

            <div class="ai-chat-body" id="chatBody">
                <div class="ai-msg bot">
                    <div class="ai-bubble">
Halo, saya siap membantu.

Contoh:
- berapa total prospek saya?
- berapa follow up di cabang saya?
- tampilkan ringkasan status prospek
- buat analisa singkat data prospek saya
- apa itu deposito?
                    </div>
                </div>
            </div>

            <div class="ai-chat-foot">
                <input type="hidden" id="conversationId" value="">
                <div class="mb-2">
                    <textarea id="chatInput" class="form-control" placeholder="Tulis pertanyaan..."></textarea>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4" id="btnClearInput">
                        Kosongkan
                    </button>
                    <button type="button" class="btn btn-primary rounded-pill px-4" id="btnSendChat">
                        <i class="bi bi-send me-1"></i> Kirim
                    </button>
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

    function appendMessage(role, text) {
        const row = document.createElement('div');
        row.className = 'ai-msg ' + role;

        const bubble = document.createElement('div');
        bubble.className = 'ai-bubble';
        bubble.textContent = text;

        row.appendChild(bubble);
        chatBody.appendChild(row);
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    async function sendMessage() {
        const message = (chatInput.value || '').trim();
        if (!message) return;

        appendMessage('user', message);
        chatInput.value = '';
        btnSend.disabled = true;
        appendMessage('bot', 'Sedang memproses...');

        try {
            const res = await fetch(@json(route('ai.chat.ask')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    conversation_id: conversationId.value
                })
            });

            const data = await res.json();

            chatBody.lastElementChild.remove();

            if (!res.ok || !data.ok) {
                appendMessage('bot', 'Maaf, proses AI gagal. Coba lagi.');
                btnSend.disabled = false;
                return;
            }

            if (data.conversation_id) {
                conversationId.value = data.conversation_id;
            }

            appendMessage('bot', data.answer || 'Tidak ada jawaban.');
        } catch (e) {
            chatBody.lastElementChild.remove();
            appendMessage('bot', 'Terjadi error koneksi AI.');
        }

        btnSend.disabled = false;
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
    });
})();
</script>
@endsection
