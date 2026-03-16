@extends('admin.layout')
@section('title','Activity Logs')
@section('page-title','Activity Logs')
@section('page-sub','All admin actions and system events')
@section('styles')
<style>
.ll{display:flex;flex-direction:column;gap:10px;}
.li{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);border-radius:var(--rm);padding:16px 18px;display:flex;align-items:flex-start;gap:16px;transition:border-color .15s;box-shadow:inset 0 1px 0 rgba(255,255,255,.05);}
.li:hover{border-color:rgba(79,156,249,.2);}
.ld{width:10px;height:10px;border-radius:50%;flex-shrink:0;margin-top:5px;}
.da{background:var(--accent2);box-shadow:0 0 8px rgba(79,156,249,.5);}
.dr{background:var(--danger);box-shadow:0 0 8px rgba(248,113,113,.4);}
.dde{background:var(--danger);}
.du{background:var(--accent3);box-shadow:0 0 8px rgba(167,139,250,.4);}
.dd{background:var(--warn);box-shadow:0 0 8px rgba(251,191,36,.4);}
.dm{background:rgba(255,255,255,.3);}
.lb2{flex:1;min-width:0;}
.la{font-size:.83rem;font-weight:600;color:var(--white);margin-bottom:3px;}
.la strong{color:var(--accent2);}
.lde{font-size:.8rem;color:var(--soft);line-height:1.5;}
.lt{font-size:.72rem;color:var(--muted);margin-top:6px;}
.pg{display:flex;gap:6px;justify-content:center;margin-top:20px;flex-wrap:wrap;}
.pl{padding:6px 12px;border-radius:var(--rs);background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);color:var(--soft);font-size:.8rem;text-decoration:none;transition:background .15s;}
.pl:hover{background:rgba(255,255,255,.08);}
.pl.ac{background:rgba(79,156,249,.15);color:var(--accent2);border-color:rgba(79,156,249,.25);}
</style>
@endsection
@section('content')
@if($logs->isEmpty())
<div class="gc"><div class="gci"><div class="empty">No activity recorded yet.</div></div></div>
@else
<div class="ll">
    @foreach($logs as $i => $log)
    @php
        $a=strtolower($log->action);
        $dc='dm';
        if(str_contains($a,'approv'))$dc='da';
        elseif(str_contains($a,'reject'))$dc='dr';
        elseif(str_contains($a,'delet'))$dc='dde';
        elseif(str_contains($a,'updat'))$dc='du';
        elseif(str_contains($a,'add')||str_contains($a,'creat'))$dc='dd';
    @endphp
    <div class="li">
        <div class="ld {{ $dc }}"></div>
        <div class="lb2">
            <div class="la"><strong>{{ $log->user ? $log->user->name : 'System' }}</strong> {{ $log->action }}</div>
            <div class="lde">{{ $log->description }}</div>
            <div class="lt">{{ $log->created_at->format('M d, Y · g:i:s A') }} · {{ $log->created_at->diffForHumans() }}</div>
        </div>
    </div>
    @endforeach
</div>
@if($logs->hasPages())
<div class="pg">
    @if($logs->onFirstPage())<span class="pl" style="opacity:.4;">← Prev</span>@else<a href="{{ $logs->previousPageUrl() }}" class="pl">← Prev</a>@endif
    @foreach($logs->getUrlRange(1,$logs->lastPage()) as $page => $url)
        <a href="{{ $url }}" class="pl {{ $page==$logs->currentPage()?'ac':'' }}">{{ $page }}</a>
    @endforeach
    @if($logs->hasMorePages())<a href="{{ $logs->nextPageUrl() }}" class="pl">Next →</a>@else<span class="pl" style="opacity:.4;">Next →</span>@endif
</div>
@endif
@endif
@endsection