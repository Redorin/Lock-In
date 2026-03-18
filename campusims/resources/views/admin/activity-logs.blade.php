@extends('admin.layout')
@section('title','Activity Logs')
@section('page-title','Activity Logs')
@section('page-sub','All admin actions and system events')

@section('styles')
<style>
.ll{display:flex;flex-direction:column;gap:10px;}

.li{
    background:var(--surface);
    border:1px solid var(--border);
    border-radius:14px;
    padding:16px 18px;
    display:flex;
    align-items:flex-start;
    gap:16px;
    transition:border-color .15s, background .25s;
    box-shadow:0 1px 4px rgba(0,0,0,.05);
}
.li:hover{ border-color:var(--accent-border); }

/* colored dots */
.ld{width:10px;height:10px;border-radius:50%;flex-shrink:0;margin-top:5px;}
.da{background:#4f9cf9;box-shadow:0 0 8px rgba(79,156,249,.5);}
.dr{background:#f87171;box-shadow:0 0 8px rgba(248,113,113,.4);}
.dde{background:#f87171;}
.du{background:#a78bfa;box-shadow:0 0 8px rgba(167,139,250,.4);}
.dd{background:#fbbf24;box-shadow:0 0 8px rgba(251,191,36,.4);}
.dm{background:var(--text-muted);}

.lb2{flex:1;min-width:0;}

.la{font-size:.83rem;font-weight:600;color:var(--text);margin-bottom:3px;}
.la strong{color:var(--accent);}

.lde{font-size:.8rem;color:var(--text-soft);line-height:1.5;}

.lt{font-size:.72rem;color:var(--text-muted);margin-top:6px;}

/* pagination */
.pg{display:flex;gap:6px;justify-content:center;margin-top:20px;flex-wrap:wrap;}
.pl{
    padding:6px 12px;border-radius:10px;
    background:var(--surface);
    border:1px solid var(--border);
    color:var(--text-soft);
    font-size:.8rem;text-decoration:none;
    transition:background .15s, border-color .15s;
}
.pl:hover{background:var(--surface2);border-color:var(--border2);}
.pl.ac{
    background:var(--accent-bg);
    color:var(--accent);
    border-color:var(--accent-border);
}
</style>
@endsection

@section('content')
@if($logs->isEmpty())
    <div class="gc"><div class="gci"><div class="empty">No activity recorded yet.</div></div></div>
@else
    <div class="ll">
        @foreach($logs as $i => $log)
        @php
            $a  = strtolower($log->action);
            $dc = 'dm';
            if(str_contains($a,'approv'))          $dc = 'da';
            elseif(str_contains($a,'reject'))      $dc = 'dr';
            elseif(str_contains($a,'delet'))       $dc = 'dde';
            elseif(str_contains($a,'updat'))       $dc = 'du';
            elseif(str_contains($a,'add') || str_contains($a,'creat')) $dc = 'dd';
        @endphp
        <div class="li">
            <div class="ld {{ $dc }}"></div>
            <div class="lb2">
                <div class="la">
                    <strong>{{ $log->user ? $log->user->name : 'System' }}</strong>
                    {{ $log->action }}
                </div>
                <div class="lde">{{ $log->description }}</div>
                <div class="lt">
                    {{ $log->created_at->format('M d, Y · g:i:s A') }}
                    &nbsp;·&nbsp;
                    {{ $log->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($logs->hasPages())
    <div class="pg">
        @if($logs->onFirstPage())
            <span class="pl" style="opacity:.4;">← Prev</span>
        @else
            <a href="{{ $logs->previousPageUrl() }}" class="pl">← Prev</a>
        @endif

        @foreach($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="pl {{ $page == $logs->currentPage() ? 'ac' : '' }}">{{ $page }}</a>
        @endforeach

        @if($logs->hasMorePages())
            <a href="{{ $logs->nextPageUrl() }}" class="pl">Next →</a>
        @else
            <span class="pl" style="opacity:.4;">Next →</span>
        @endif
    </div>
    @endif
@endif
@endsection