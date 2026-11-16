<?php
if (Auth('admin')->User()->dashboard_style == 'light') {
    $text = 'dark';
    $bg = 'light';
} else {
    $bg = 'dark';
    $text = 'light';
}
?>
@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">
                <div class="mt-2 mb-4 d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h1 class="title1 text-{{ $text }} mb-1">Live Chat</h1>
                        <p class="text-{{ $text }} mb-0 opacity-75">Chat with users in real time</p>
                    </div>
                    <div>
                        <span class="badge badge-info" id="lc-open-count">{{ $openCount }} open</span>
                        <span class="badge badge-danger" id="lc-unread-total" @if($unreadTotal < 1) style="display:none" @endif>{{ $unreadTotal }} unread</span>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card shadow" style="height: 70vh; overflow: hidden;">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <strong class="text-{{ $text }}">Conversations</strong>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="lc-refresh-list" title="Refresh">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                            <div class="card-body p-0" style="overflow-y: auto; height: calc(70vh - 56px);">
                                <div id="lc-conversation-list">
                                    @forelse($conversations as $c)
                                        <button type="button"
                                                class="lc-conv-item w-100 text-left border-0 border-bottom px-3 py-3 bg-transparent"
                                                data-id="{{ $c->id }}"
                                                style="cursor:pointer;">
                                            <div class="d-flex align-items-start">
                                                <img src="{{ $c->user ? profile_photo_url($c->user) : '' }}"
                                                     alt=""
                                                     width="40" height="40"
                                                     style="border-radius:50%; object-fit:cover; background:#ddd;"
                                                     onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2240%22 height=%2240%22%3E%3Crect fill=%22%23ccc%22 width=%2240%22 height=%2240%22/%3E%3C/svg%3E'">
                                                <div class="ml-2 flex-grow-1" style="min-width:0;">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <strong class="text-{{ $text }} text-truncate" style="max-width:140px;">{{ optional($c->user)->name ?: 'User' }}</strong>
                                                        @if($c->admin_unread > 0)
                                                            <span class="badge badge-danger">{{ $c->admin_unread }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="small text-muted text-truncate">{{ optional($c->user)->email }}</div>
                                                    <div class="small text-{{ $text }} opacity-75 text-truncate mt-1">
                                                        {{ $c->latestMessage ? \Illuminate\Support\Str::limit($c->latestMessage->message, 60) : 'No messages yet' }}
                                                    </div>
                                                    <div class="d-flex justify-content-between mt-1">
                                                        <span class="badge badge-{{ $c->status === 'open' ? 'success' : 'secondary' }}">{{ $c->status }}</span>
                                                        <span class="small text-muted">{{ optional($c->last_message_at)->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </button>
                                    @empty
                                        <div class="p-4 text-center text-muted" id="lc-empty-list">No conversations yet</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 mb-3">
                        <div class="card shadow" style="height: 70vh; display:flex; flex-direction:column;">
                            <div class="card-header d-flex justify-content-between align-items-center" id="lc-chat-header">
                                <div>
                                    <strong class="text-{{ $text }}" id="lc-user-name">Select a conversation</strong>
                                    <div class="small text-muted" id="lc-user-email"></div>
                                </div>
                                <div id="lc-chat-actions" style="display:none;">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="lc-toggle-status">Close</button>
                                </div>
                            </div>
                            <div class="card-body p-3" id="lc-messages"
                                 style="flex:1; overflow-y:auto; background: {{ $bg === 'dark' ? '#1a1a2e' : '#f4f6f9' }};">
                                <div class="text-center text-muted mt-5" id="lc-placeholder">
                                    Choose a conversation from the left to start chatting
                                </div>
                            </div>
                            <div class="card-footer">
                                <form id="lc-send-form" class="d-flex" style="gap:8px;">
                                    <input type="text" id="lc-message-input" class="form-control"
                                           placeholder="Type a reply..." disabled autocomplete="off" maxlength="2000">
                                    <button type="submit" class="btn btn-primary" id="lc-send-btn" disabled>
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .lc-conv-item.active {
            background: {{ $bg === 'dark' ? 'rgba(255,255,255,0.08)' : 'rgba(0,123,255,0.08)' }} !important;
        }
        .lc-bubble {
            max-width: 75%;
            padding: 10px 14px;
            border-radius: 14px;
            margin-bottom: 10px;
            word-wrap: break-word;
            white-space: pre-wrap;
        }
        .lc-bubble.mine {
            background: #007bff;
            color: #fff;
            margin-left: auto;
            border-bottom-right-radius: 4px;
        }
        .lc-bubble.theirs {
            background: {{ $bg === 'dark' ? '#2d2d44' : '#ffffff' }};
            color: {{ $bg === 'dark' ? '#eee' : '#222' }};
            border: 1px solid {{ $bg === 'dark' ? '#3a3a55' : '#e5e5e5' }};
            margin-right: auto;
            border-bottom-left-radius: 4px;
        }
        .lc-meta {
            font-size: 11px;
            opacity: 0.7;
            margin-top: 4px;
        }
    </style>

    <script>
    (function () {
        var csrf = document.querySelector('meta[name="csrf-token"]')
            ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            : '{{ csrf_token() }}';
        var activeId = null;
        var activeStatus = 'open';
        var lastMessageId = 0;
        var pollTimer = null;

        function esc(str) {
            var d = document.createElement('div');
            d.textContent = str == null ? '' : String(str);
            return d.innerHTML;
        }

        function renderBubble(m) {
            var cls = m.is_mine ? 'mine' : 'theirs';
            return '<div class="lc-bubble ' + cls + '">' +
                esc(m.message) +
                '<div class="lc-meta">' + esc(m.created_at) + '</div></div>';
        }

        function scrollMessages() {
            var box = document.getElementById('lc-messages');
            box.scrollTop = box.scrollHeight;
        }

        function setComposerEnabled(on) {
            document.getElementById('lc-message-input').disabled = !on;
            document.getElementById('lc-send-btn').disabled = !on;
            document.getElementById('lc-chat-actions').style.display = on ? '' : 'none';
        }

        function updateStatusButton() {
            var btn = document.getElementById('lc-toggle-status');
            if (activeStatus === 'open') {
                btn.textContent = 'Close chat';
                btn.className = 'btn btn-sm btn-outline-secondary';
            } else {
                btn.textContent = 'Reopen chat';
                btn.className = 'btn btn-sm btn-outline-success';
            }
        }

        function loadConversations() {
            return fetch('{{ route('admin.livechat.conversations') }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                var list = document.getElementById('lc-conversation-list');
                var html = '';
                if (!data.conversations || !data.conversations.length) {
                    html = '<div class="p-4 text-center text-muted">No conversations yet</div>';
                } else {
                    data.conversations.forEach(function (c) {
                        var active = activeId == c.id ? ' active' : '';
                        var badge = c.admin_unread > 0
                            ? '<span class="badge badge-danger">' + c.admin_unread + '</span>'
                            : '';
                        html += '<button type="button" class="lc-conv-item w-100 text-left border-0 border-bottom px-3 py-3 bg-transparent' + active + '" data-id="' + c.id + '" style="cursor:pointer;">' +
                            '<div class="d-flex align-items-start">' +
                            '<img src="' + esc(c.user.photo || '') + '" width="40" height="40" style="border-radius:50%;object-fit:cover;background:#ddd;" onerror="this.style.background=\'#ccc\'">' +
                            '<div class="ml-2 flex-grow-1" style="min-width:0;">' +
                            '<div class="d-flex justify-content-between align-items-center">' +
                            '<strong class="text-{{ $text }} text-truncate" style="max-width:140px;">' + esc(c.user.name) + '</strong>' + badge +
                            '</div>' +
                            '<div class="small text-muted text-truncate">' + esc(c.user.email || '') + '</div>' +
                            '<div class="small text-{{ $text }} opacity-75 text-truncate mt-1">' + esc(c.preview) + '</div>' +
                            '<div class="d-flex justify-content-between mt-1">' +
                            '<span class="badge badge-' + (c.status === 'open' ? 'success' : 'secondary') + '">' + esc(c.status) + '</span>' +
                            '<span class="small text-muted">' + esc(c.last_message_at || '') + '</span>' +
                            '</div></div></div></button>';
                    });
                }
                list.innerHTML = html;
                document.getElementById('lc-unread-total').textContent = (data.unread_total || 0) + ' unread';
                document.getElementById('lc-unread-total').style.display = data.unread_total > 0 ? '' : 'none';
                var open = (data.conversations || []).filter(function (c) { return c.status === 'open'; }).length;
                document.getElementById('lc-open-count').textContent = open + ' open';
                bindConvClicks();
            })
            .catch(function () {});
        }

        function bindConvClicks() {
            document.querySelectorAll('.lc-conv-item').forEach(function (el) {
                el.onclick = function () {
                    openConversation(el.getAttribute('data-id'));
                };
            });
        }

        function openConversation(id) {
            activeId = id;
            lastMessageId = 0;
            document.querySelectorAll('.lc-conv-item').forEach(function (el) {
                el.classList.toggle('active', el.getAttribute('data-id') == id);
            });
            fetch('{{ url('/admin/dashboard/livechat') }}/' + id + '/messages', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                var c = data.conversation;
                activeStatus = c.status;
                document.getElementById('lc-user-name').textContent = c.user.name || 'User';
                document.getElementById('lc-user-email').textContent = c.user.email || '';
                updateStatusButton();
                setComposerEnabled(true);

                var box = document.getElementById('lc-messages');
                var html = '';
                (data.messages || []).forEach(function (m) {
                    html += renderBubble(m);
                    if (m.id > lastMessageId) lastMessageId = m.id;
                });
                box.innerHTML = html || '<div class="text-center text-muted mt-5">No messages yet. Say hello.</div>';
                scrollMessages();
                loadConversations();
            });
        }

        function pollActive() {
            if (!activeId) return;
            fetch('{{ url('/admin/dashboard/livechat') }}/' + activeId + '/messages', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                activeStatus = data.conversation.status;
                updateStatusButton();
                var box = document.getElementById('lc-messages');
                var html = '';
                var maxId = 0;
                (data.messages || []).forEach(function (m) {
                    html += renderBubble(m);
                    if (m.id > maxId) maxId = m.id;
                });
                if (maxId > lastMessageId) {
                    box.innerHTML = html;
                    lastMessageId = maxId;
                    scrollMessages();
                }
            })
            .catch(function () {});
        }

        document.getElementById('lc-send-form').addEventListener('submit', function (e) {
            e.preventDefault();
            if (!activeId) return;
            var input = document.getElementById('lc-message-input');
            var msg = (input.value || '').trim();
            if (!msg) return;
            document.getElementById('lc-send-btn').disabled = true;

            fetch('{{ url('/admin/dashboard/livechat') }}/' + activeId + '/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ message: msg })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                input.value = '';
                if (data.message) {
                    var box = document.getElementById('lc-messages');
                    var placeholder = box.querySelector('.text-muted.mt-5');
                    if (placeholder) box.innerHTML = '';
                    box.insertAdjacentHTML('beforeend', renderBubble(data.message));
                    if (data.message.id > lastMessageId) lastMessageId = data.message.id;
                    scrollMessages();
                }
                activeStatus = 'open';
                updateStatusButton();
                loadConversations();
            })
            .catch(function () {})
            .finally(function () {
                document.getElementById('lc-send-btn').disabled = false;
                input.focus();
            });
        });

        document.getElementById('lc-toggle-status').addEventListener('click', function () {
            if (!activeId) return;
            var action = activeStatus === 'open' ? 'close' : 'reopen';
            fetch('{{ url('/admin/dashboard/livechat') }}/' + activeId + '/' + action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                activeStatus = data.status;
                updateStatusButton();
                loadConversations();
            });
        });

        document.getElementById('lc-refresh-list').addEventListener('click', loadConversations);
        bindConvClicks();

        pollTimer = setInterval(function () {
            loadConversations();
            pollActive();
        }, 5000);
    })();
    </script>
@endsection
