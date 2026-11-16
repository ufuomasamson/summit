@auth
@php
    $lcSiteName = isset($settings) && $settings ? ($settings->site_name ?? 'Support') : 'Support';
@endphp
<style>
    #summit-livechat-root {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 99999;
        font-family: Inter, system-ui, sans-serif;
    }
    #summit-lc-toggle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: none;
        background: var(--color-primary, #c10e0e);
        color: #fff;
        box-shadow: 0 8px 24px rgba(0,0,0,.22);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: transform .2s ease, background .2s ease;
    }
    #summit-lc-toggle:hover { transform: scale(1.05); }
    #summit-lc-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        min-width: 20px;
        height: 20px;
        padding: 0 5px;
        border-radius: 999px;
        background: #111;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        display: none;
        align-items: center;
        justify-content: center;
        border: 2px solid #fff;
    }
    #summit-lc-panel {
        position: absolute;
        right: 0;
        bottom: 70px;
        width: min(380px, calc(100vw - 32px));
        height: min(520px, calc(100vh - 120px));
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 16px 48px rgba(0,0,0,.2);
        display: none;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #E0E5E5;
    }
    #summit-lc-panel.open { display: flex; }
    #summit-lc-header {
        background: var(--color-primary, #c10e0e);
        color: #fff;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    #summit-lc-header h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
    }
    #summit-lc-header p {
        margin: 2px 0 0;
        font-size: 12px;
        opacity: .9;
    }
    #summit-lc-close {
        background: transparent;
        border: none;
        color: #fff;
        cursor: pointer;
        padding: 4px;
        line-height: 1;
    }
    #summit-lc-messages {
        flex: 1;
        overflow-y: auto;
        padding: 16px;
        background: #F7F8F8;
    }
    .summit-lc-bubble {
        max-width: 82%;
        padding: 10px 12px;
        border-radius: 14px;
        margin-bottom: 10px;
        font-size: 13.5px;
        line-height: 1.45;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    .summit-lc-bubble.mine {
        background: var(--color-primary, #c10e0e);
        color: #fff;
        margin-left: auto;
        border-bottom-right-radius: 4px;
    }
    .summit-lc-bubble.theirs {
        background: #fff;
        color: #151515;
        border: 1px solid #E0E5E5;
        margin-right: auto;
        border-bottom-left-radius: 4px;
    }
    .summit-lc-meta {
        font-size: 10px;
        opacity: .7;
        margin-top: 4px;
    }
    #summit-lc-footer {
        display: flex;
        gap: 8px;
        padding: 12px;
        border-top: 1px solid #E0E5E5;
        background: #fff;
    }
    #summit-lc-input {
        flex: 1;
        border: 1px solid #E0E5E5;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 14px;
        outline: none;
        color: #151515;
        background: #fff;
    }
    #summit-lc-input:focus { border-color: var(--color-primary, #c10e0e); }
    #summit-lc-send {
        border: none;
        border-radius: 10px;
        background: var(--color-primary, #c10e0e);
        color: #fff;
        width: 44px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #summit-lc-send:disabled { opacity: .6; cursor: not-allowed; }
    .summit-lc-empty {
        text-align: center;
        color: #7A7A7A;
        font-size: 13px;
        margin-top: 40px;
        padding: 0 20px;
    }
</style>

<div id="summit-livechat-root" aria-live="polite">
    <div id="summit-lc-panel" role="dialog" aria-label="Live chat">
        <div id="summit-lc-header">
            <div>
                <h4>{{ $lcSiteName }} Support</h4>
                <p>We typically reply within minutes</p>
            </div>
            <button type="button" id="summit-lc-close" aria-label="Close chat">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="summit-lc-messages">
            <div class="summit-lc-empty">Loading conversation…</div>
        </div>
        <form id="summit-lc-footer">
            <input type="text" id="summit-lc-input" placeholder="Write a message…" maxlength="2000" autocomplete="off">
            <button type="submit" id="summit-lc-send" aria-label="Send">
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
    <button type="button" id="summit-lc-toggle" aria-label="Open live chat" title="Live Chat">
        <i class="fas fa-comments" style="font-size:22px;"></i>
        <span id="summit-lc-badge">0</span>
    </button>
</div>

<script>
(function () {
    if (window.__summitLiveChatInit) return;
    window.__summitLiveChatInit = true;

    var urls = {
        bootstrap: @json(route('user.livechat.bootstrap')),
        poll: @json(route('user.livechat.poll')),
        send: @json(route('user.livechat.send')),
        unread: @json(route('user.livechat.unread'))
    };

    var csrf = (document.querySelector('meta[name="csrf-token"]') || document.querySelector('meta[name="csrf_token"]') || {}).content
        || (document.getElementById('csrf_token') || {}).content
        || '';

    var panel = document.getElementById('summit-lc-panel');
    var toggle = document.getElementById('summit-lc-toggle');
    var closeBtn = document.getElementById('summit-lc-close');
    var messagesEl = document.getElementById('summit-lc-messages');
    var form = document.getElementById('summit-lc-footer');
    var input = document.getElementById('summit-lc-input');
    var sendBtn = document.getElementById('summit-lc-send');
    var badge = document.getElementById('summit-lc-badge');

    var open = false;
    var bootstrapped = false;
    var lastId = 0;
    var pollTimer = null;

    function esc(str) {
        var d = document.createElement('div');
        d.textContent = str == null ? '' : String(str);
        return d.innerHTML;
    }

    function renderBubble(m) {
        return '<div class="summit-lc-bubble ' + (m.is_mine ? 'mine' : 'theirs') + '">' +
            esc(m.message) +
            '<div class="summit-lc-meta">' + esc(m.created_at) + '</div></div>';
    }

    function setBadge(count) {
        count = parseInt(count, 10) || 0;
        if (count > 0 && !open) {
            badge.style.display = 'flex';
            badge.textContent = count > 99 ? '99+' : String(count);
        } else {
            badge.style.display = 'none';
        }
    }

    function scrollBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function paintMessages(list, replace) {
        if (replace) {
            if (!list || !list.length) {
                messagesEl.innerHTML = '<div class="summit-lc-empty">Send a message to start chatting with support.</div>';
                return;
            }
            messagesEl.innerHTML = list.map(renderBubble).join('');
        } else if (list && list.length) {
            var empty = messagesEl.querySelector('.summit-lc-empty');
            if (empty) messagesEl.innerHTML = '';
            messagesEl.insertAdjacentHTML('beforeend', list.map(renderBubble).join(''));
        }
        scrollBottom();
    }

    function bootstrap() {
        return fetch(urls.bootstrap, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            lastId = 0;
            (data.messages || []).forEach(function (m) {
                if (m.id > lastId) lastId = m.id;
            });
            paintMessages(data.messages || [], true);
            bootstrapped = true;
            setBadge(0);
        })
        .catch(function () {
            messagesEl.innerHTML = '<div class="summit-lc-empty">Unable to load chat. Please try again.</div>';
        });
    }

    function poll() {
        var q = urls.poll + '?after_id=' + lastId + '&mark_read=' + (open ? '1' : '0');
        fetch(q, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            var incoming = data.messages || [];
            if (open && bootstrapped && incoming.length) {
                // when after_id is used, only append new ones
                if (lastId > 0) {
                    paintMessages(incoming, false);
                }
                incoming.forEach(function (m) {
                    if (m.id > lastId) lastId = m.id;
                });
            }
            if (!open) setBadge(data.unread || 0);
            else setBadge(0);
        })
        .catch(function () {});
    }

    function refreshUnread() {
        if (open) return;
        fetch(urls.unread, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (data) { setBadge(data.count || 0); })
        .catch(function () {});
    }

    function openChat() {
        open = true;
        panel.classList.add('open');
        setBadge(0);
        if (!bootstrapped) {
            bootstrap().then(function () { input.focus(); });
        } else {
            // reopen: refresh full thread and mark read
            lastId = 0;
            bootstrap().then(function () { input.focus(); });
        }
    }

    function closeChat() {
        open = false;
        panel.classList.remove('open');
        refreshUnread();
    }

    toggle.addEventListener('click', function () {
        if (open) closeChat();
        else openChat();
    });
    closeBtn.addEventListener('click', closeChat);

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var msg = (input.value || '').trim();
        if (!msg) return;
        sendBtn.disabled = true;

        fetch(urls.send, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ message: msg })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            input.value = '';
            if (data.message) {
                paintMessages([data.message], false);
                if (data.message.id > lastId) lastId = data.message.id;
            }
            bootstrapped = true;
        })
        .catch(function () {})
        .finally(function () {
            sendBtn.disabled = false;
            input.focus();
        });
    });

    // Global opener for support page / other CTAs
    window.openSummitLiveChat = openChat;

    refreshUnread();
    pollTimer = setInterval(function () {
        if (open && bootstrapped) poll();
        else refreshUnread();
    }, 4000);
})();
</script>
@endauth
