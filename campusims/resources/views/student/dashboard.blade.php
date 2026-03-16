@extends('student.layout')
@section('title','Dashboard')
@section('page-title','Live Space Availability')
@section('page-sub','Real-time occupancy across all campus buildings')

@section('styles')
<style>
    .stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;}
    .stat{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:16px 18px;backdrop-filter:blur(16px);box-shadow:inset 0 1px 0 rgba(255,255,255,.07);}
    .stat-lbl{font-size:.65rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);margin-bottom:6px;}
    .stat-val{font-size:1.5rem;font-weight:800;letter-spacing:-.5px;}
    .cb{color:var(--accent2);}.cp{color:var(--accent3);}.cw{color:rgba(255,255,255,.85);}

    .bsect{margin-bottom:24px;}
    .bhdr{display:flex;align-items:center;gap:10px;margin-bottom:12px;}
    .btag{font-size:.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);}
    .bline{flex:1;height:1px;background:rgba(255,255,255,.07);}

    .sgrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;}
    .sc{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;overflow:hidden;backdrop-filter:blur(16px);transition:transform .2s,border-color .2s;animation:fu .5s ease both;box-shadow:inset 0 1px 0 rgba(255,255,255,.07);}
    .sc:hover{transform:translateY(-2px);border-color:rgba(79,156,249,.25);}
    @keyframes fu{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
    .sth{height:90px;background:linear-gradient(135deg,rgba(79,156,249,.08),rgba(167,139,250,.05));display:flex;align-items:center;justify-content:center;border-bottom:1px solid rgba(255,255,255,.06);position:relative;}
    .sth::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse at center,rgba(79,156,249,.06) 0%,transparent 70%);}
    .sth svg{width:28px;height:28px;opacity:.2;color:var(--accent2);}
    .sbody{padding:12px 14px;}
    .sname{font-size:.88rem;font-weight:600;margin-bottom:8px;}
    .smeta{display:flex;align-items:center;justify-content:space-between;margin-bottom:7px;}
    .scnt{font-size:.8rem;font-weight:600;color:var(--soft);}
    .badge{font-size:.6rem;font-weight:700;letter-spacing:.06em;padding:2px 8px;border-radius:99px;}
    .bl{background:rgba(79,156,249,.12);color:#7eb8ff;border:1px solid rgba(79,156,249,.2);}
    .bm{background:rgba(251,191,36,.1);color:#fbbf24;border:1px solid rgba(251,191,36,.2);}
    .bh{background:rgba(248,113,113,.1);color:#f87171;border:1px solid rgba(248,113,113,.2);}
    .bf{background:rgba(127,29,29,.3);color:#fca5a5;border:1px solid rgba(239,68,68,.3);}
    .pt{height:3px;background:rgba(255,255,255,.07);border-radius:99px;overflow:hidden;}
    .pf{height:100%;border-radius:99px;transition:width .8s cubic-bezier(.4,0,.2,1);}
    .pfl{background:linear-gradient(90deg,#4f9cf9,#7eb8ff);}
    .pfm{background:linear-gradient(90deg,#fbbf24,#f59e0b);}
    .pfh{background:linear-gradient(90deg,#f87171,#ef4444);}
    .pff{background:linear-gradient(90deg,#fca5a5,#ef4444);}

    @media(max-width:600px){
        .stats{grid-template-columns:repeat(3,1fr);gap:8px;}
        .stat{padding:12px 10px;}
        .stat-lbl{font-size:.58rem;}
        .stat-val{font-size:1.2rem;}
        .sgrid{grid-template-columns:1fr 1fr;}
        .sth{height:70px;}
    }
    @media(max-width:380px){
        .sgrid{grid-template-columns:1fr;}
    }
</style>
@endsection

@section('content')
@php $total=$spaces->count();$low=$spaces->where('status','LOW')->count();$crowded=$spaces->whereIn('status',['HIGH','FULL'])->count(); @endphp
<div class="stats">
    <div class="stat"><div class="stat-lbl">Total</div><div class="stat-val cb">{{ $total }}</div></div>
    <div class="stat"><div class="stat-lbl">Available</div><div class="stat-val cw">{{ $low }}</div></div>
    <div class="stat"><div class="stat-lbl">Crowded</div><div class="stat-val cp">{{ $crowded }}</div></div>
</div>

@forelse($grouped as $building => $bspaces)
<div class="bsect">
    <div class="bhdr"><span class="btag">{{ $building }}</span><div class="bline"></div></div>
    <div class="sgrid">
        @foreach($bspaces as $space)
        @php
            $st=strtolower($space->status);
            $bc=$st==='low'?'bl':($st==='moderate'?'bm':($st==='high'?'bh':'bf'));
            $pc=$st==='low'?'pfl':($st==='moderate'?'pfm':($st==='high'?'pfh':'pff'));
        @endphp
        <div class="sc">
            <div class="sth"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
            <div class="sbody">
                <div class="sname">{{ $space->name }}</div>
                <div class="smeta">
                    <span class="scnt">{{ $space->current_occupancy }} / {{ $space->capacity }}</span>
                    <span class="badge {{ $bc }}">{{ $space->status }}</span>
                </div>
                <div class="pt"><div class="pf {{ $pc }}" style="width:{{ $space->occupancy_percent }}%"></div></div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@empty
<div style="text-align:center;padding:60px;color:var(--muted);font-size:.9rem;">No campus spaces found.</div>
@endforelse
@endsection