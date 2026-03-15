@extends('admin.layout')
@section('title','Activity Logs')
@section('page-title','Activity Logs')
@section('page-sub','All admin actions and system events')

@section('styles')
<style>
    .log-list { display: flex; flex-direction: column; gap: 10px; }
    .log-item {
        background: var(--glass); border: 1px solid var(--glass-border);
        border-radius: var(--radius-md); padding: 16px 18px;
        display: flex; align-items: flex-start; gap: 16px;
        transition: border-color .15s;
        animation: fadeUp .3s ease both;
    }
    .log-item:hover { border-color: rgba(124,111,247,.2); }
    @keyframes fadeUp { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

    .log-dot {
        width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
        margin-top: 5px;
    }
    .dot-approve { background: var(--accent); box-shadow: 0 0 8px var(--accent); }
    .dot-reject  { background: var(--danger); box-shadow: 0 0 8px var(--danger); }
    .dot-delete  { background: var(--danger); }
    .dot-update  { background: var(--accent2); box-shadow: 0 0 8px var(--accent2); }
    .dot-add     { background: var(--accent3); box-shadow: 0 0 8px var(--accent3); }
    .dot-toggle  { background: #60a5fa; }
    .dot-default { background: var(--text-muted); }

    .log-body { flex: 1; min-width: 0; }
    .log-action {
        font-size: .83rem; font-weight: 600; color: var(--text);
        margin-bottom: 3px;
    }
    .log-action strong { color: var(--accent2); }
    .log-description { font-size: .8rem; color: var(--text-soft); line-height: 1.5; }
    .log-time { font-size: .72rem; color: var(--text-muted); margin-top: 6px; }

    .pagination { display:flex; gap:6px; justify-content:center; margin-top:20px; flex-wrap:wrap; }
    .pagination .page-link {
        padding: 6px 12px; border-radius: var(--radius-sm);
        background: var(--glass); border: 1px solid var(--glass-border);
        color: var(--text-soft); font-size: .8rem; text-decoration: none;
        transition: background .15s;
    }
    .pagination .page-link:hover { background: var(--glass-hover); }
    .pagination .page-link.active { background: rgba(124,111,247,.2); color: var(--accent2); border-color: rgba(124,111,247,.3); }
</style>
@endsection

@section('content')

@if($logs->isEmpty())
    <div class="glass-card">
        <div class="glass-card-inner">
            <div class="empty-state">No activity recorded yet.</div>
        </div>
    </div>
@else
    <div class="log-list">
        @foreach($logs as $i => $log)
        @php
            $action = strtolower($log->action);
            $dot = 'dot-default';
            if (str_contains($action, 'approv'))  $dot = 'dot-approve';
            elseif (str_contains($action, 'reject')) $dot = 'dot-reject';
            elseif (str_contains($action, 'delet'))  $dot = 'dot-delete';
            elseif (str_contains($action, 'updat'))  $dot = 'dot-update';
            elseif (str_contains($action, 'add'))    $dot = 'dot-add';
            elseif (str_contains($action, 'activ') || str_contains($action, 'deact')) $dot = 'dot-toggle';
        @endphp
        <div class="log-item" style="animation-delay:{{ min($i,10) * 0.03 }}s">
            <div class="log-dot {{ $dot }}"></div>
            <div class="log-body">
                <div class="log-action">
                    <strong>{{ $log->user ? $log->user->name : 'System' }}</strong>
                    {{ $log->action }}
                </div>
                <div class="log-description">{{ $log->description }}</div>
                <div class="log-time">{{ $log->created_at->format('M d, Y · g:i:s A') }} &nbsp;·&nbsp; {{ $log->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($logs->hasPages())
    <div class="pagination">
        @if($logs->onFirstPage())
            <span class="page-link" style="opacity:.4;">← Prev</span>
        @else
            <a href="{{ $logs->previousPageUrl() }}" class="page-link">← Prev</a>
        @endif

        @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="page-link {{ $page == $logs->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach

        @if($logs->hasMorePages())
            <a href="{{ $logs->nextPageUrl() }}" class="page-link">Next →</a>
        @else
            <span class="page-link" style="opacity:.4;">Next →</span>
        @endif
    </div>
    @endif
@endif

@endsection