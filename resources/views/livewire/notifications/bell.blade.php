<div class="notification-bell-root position-relative" wire:poll.5s>
    <button class="notification-bell-trigger iconbtn position-relative" type="button" wire:click="toggle" title="Notifikasi">
        <i class="bi bi-bell"></i>
        @if($unreadCount > 0)
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    @if($open)
        @if($mobileOverlay)
            <dialog
                class="notification-panel notification-panel-overlay bg-white border rounded-4 shadow"
                x-init="if (!$el.open) $el.showModal()"
                x-on:click="if ($event.target === $el) $wire.set('open', false)"
                x-on:cancel.prevent="$wire.set('open', false)"
            >
        @else
            <div
                class="notification-panel position-absolute end-0 mt-2 bg-white border rounded-4 shadow"
                style="width:360px; max-width:90vw; z-index:6000; overflow:hidden;"
                wire:click.outside="$set('open', false)"
            >
        @endif
            <div class="notification-panel-head p-3 border-bottom d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-bold">Notifikasi</div>
                    <div class="text-muted small">Update status prospek Anda</div>
                </div>

                @if($unreadCount > 0)
                    <button class="btn btn-sm btn-light rounded-pill" wire:click="markAllAsRead">
                        Tandai dibaca
                    </button>
                @endif

                @if($mobileOverlay)
                    <button type="button" class="btn-close ms-auto" aria-label="Tutup notifikasi"
                            wire:click="$set('open', false)"></button>
                @endif
            </div>

            <div class="notification-panel-list" style="max-height:420px; overflow:auto;">
                @forelse($notifications as $n)
                    <div class="notification-panel-item p-3 border-bottom {{ $n->read_at ? '' : 'bg-light' }}">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div class="flex-grow-1">
                                <div class="fw-semibold">{{ $n->title }}</div>
                                <div class="small text-muted mt-1">{{ $n->message }}</div>
                                <div class="small text-muted mt-2">
                                    {{ $n->created_at?->format('d/m/Y H:i') }}
                                </div>
                            </div>

                            @if(!$n->read_at)
                                <button class="btn btn-sm btn-outline-primary rounded-pill"
                                        wire:click="markAsRead({{ $n->id }})">
                                    Baca
                                </button>
                            @endif
                        </div>

                        @if($n->prospect_id)
                            <div class="mt-2">
                                <a href="{{ route('prospects.index') }}"
                                   wire:click="markAsRead({{ $n->id }})"
                                   class="btn btn-sm btn-outline-secondary rounded-pill">
                                    Lihat
                                </a>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-4 text-center text-muted">
                        Belum ada notifikasi.
                    </div>
                @endforelse
            </div>
        @if($mobileOverlay)
            </dialog>
        @else
            </div>
        @endif
    @endif
</div>
