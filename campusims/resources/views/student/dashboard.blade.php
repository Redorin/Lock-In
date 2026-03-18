@extends('student.layout')
@section('title','Dashboard')
@section('page-title','Live Space Availability')
@section('page-sub','Real-time occupancy across all campus buildings')

@section('styles')
<style>
    /* ── Stat cards ── */
    .stats { display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px; }
    .stat {
        background:var(--surface);border:1px solid var(--border);
        border-radius:16px;padding:18px 20px;
        box-shadow:var(--shadow-sm),var(--inset);
        position:relative;overflow:hidden;
        animation:statIn .5s var(--ease) both;
        transition:transform var(--t) var(--ease), box-shadow var(--t) var(--ease), background var(--t) var(--ease);
        cursor:default;
    }
    .stat::after {
        content:'';position:absolute;inset:0;
        background:linear-gradient(135deg, var(--accent-bg), transparent);
        opacity:0;transition:opacity var(--t) var(--ease);
    }
    .stat:hover { transform:translateY(-3px);box-shadow:var(--shadow-md),var(--inset); }
    .stat:hover::after { opacity:1; }
    .stat:nth-child(1){animation-delay:.05s}
    .stat:nth-child(2){animation-delay:.1s}
    .stat:nth-child(3){animation-delay:.15s}
    @keyframes statIn { from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)} }

    .stat-icon {
        width:34px;height:34px;border-radius:9px;
        background:var(--accent-bg);border:1px solid var(--accent-border);
        display:flex;align-items:center;justify-content:center;
        margin-bottom:12px;position:relative;z-index:1;
    }
    .stat-icon svg { width:16px;height:16px;color:var(--accent2); }
    .stat-lbl { font-size:.66rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px;position:relative;z-index:1; }
    .stat-val {
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:1.8rem;font-weight:800;letter-spacing:-.6px;
        position:relative;z-index:1;
        background:linear-gradient(135deg,var(--text),var(--text-soft));
        -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
    }
    .stat-val.blue { background:linear-gradient(135deg,var(--accent),var(--accent2));-webkit-background-clip:text;background-clip:text; }

    /* ── Building sections ── */
    .bsect { margin-bottom:28px; }
    .bhdr { display:flex;align-items:center;gap:12px;margin-bottom:14px; }
    .btag {
        font-family:'Plus Jakarta Sans',sans-serif;
        font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;
        color:var(--text-muted);
    }
    .bline { flex:1;height:1px;background:var(--border); }

    /* ── Space cards ── */
    .sgrid { display:grid;grid-template-columns:repeat(auto-fill,minmax(185px,1fr));gap:12px; }
    .sc {
        background:var(--surface);border:1px solid var(--border);
        border-radius:16px;overflow:hidden;
        box-shadow:var(--shadow-sm),var(--inset);
        transition:transform var(--t) var(--ease), border-color var(--t) var(--ease), box-shadow var(--t) var(--ease), background var(--t) var(--ease);
        animation:cardIn .5s var(--ease) both;
        cursor:default;
    }
    .sc:hover { transform:translateY(-4px);border-color:var(--accent-border);box-shadow:var(--shadow-md),var(--inset); }
    @keyframes cardIn { from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)} }

    .sth {
        height:88px;
        background:var(--surface2);
        display:flex;align-items:center;justify-content:center;
        border-bottom:1px solid var(--border);
        position:relative;overflow:hidden;
    }
    .sth::before {
        content:'';position:absolute;inset:0;
        background:radial-gradient(ellipse at center, var(--accent-bg) 0%, transparent 70%);
        opacity:0;transition:opacity var(--t) var(--ease);
    }
    .sc:hover .sth::before { opacity:1; }
    .sth svg { width:28px;height:28px;color:var(--accent2);opacity:.2;transition:opacity var(--t) var(--ease), transform var(--t) var(--ease-back); }
    .sc:hover .sth svg { opacity:.4;transform:scale(1.15); }

    .sbody { padding:14px; }
    .sname { font-family:'Plus Jakarta Sans',sans-serif;font-size:.88rem;font-weight:700;margin-bottom:10px;color:var(--text); }
    .smeta { display:flex;align-items:center;justify-content:space-between;margin-bottom:8px; }
    .scnt { font-size:.8rem;font-weight:600;color:var(--text-soft); }

    /* Status badges */
    .badge { font-size:.6rem;font-weight:700;letter-spacing:.05em;padding:2px 8px;border-radius:99px; }
    .bl { background:var(--accent-bg);color:var(--accent2);border:1px solid var(--accent-border); }
    .bm { background:var(--warn-bg);color:var(--warn);border:1px solid var(--warn-border); }
    .bh { background:var(--danger-bg);color:var(--danger);border:1px solid var(--danger-border); }
    .bf { background:rgba(220,38,38,.15);color:var(--danger);border:1px solid var(--danger-border); }

    /* Progress bar */
    .pt { height:3px;background:var(--border);border-radius:99px;overflow:hidden; }
    .pf { height:100%;border-radius:99px;transition:width 1s var(--ease); }
    .pfl { background:linear-gradient(90deg,var(--accent),var(--accent2)); }
    .pfm { background:linear-gradient(90deg,var(--warn),#f59e0b); }
    .pfh { background:linear-gradient(90deg,var(--danger),#f87171); }
    .pff { background:var(--danger); }

    @media(max-width:600px){
        .stats { gap:8px; }
        .stat { padding:12px 14px; }
        .stat-val { font-size:1.4rem; }
        .sgrid { grid-template-columns:1fr 1fr; }
        .sth { height:70px; }
    }
    @media(max-width:380px){ .sgrid{grid-template-columns:1fr;} }
</style>
@endsection

@section('content')
@php
    $total = $spaces->count();
    $low   = $spaces->where('status','LOW')->count();
    $crowd = $spaces->whereIn('status',['HIGH','FULL'])->count();
@endphp

<div class="stats">
    <div class="stat">
        <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></div>
        <div class="stat-lbl">Total Spaces</div>
        <div class="stat-val blue">{{ $total }}</div>
    </div>
    <div class="stat">
        <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
        <div class="stat-lbl">Available</div>
        <div class="stat-val">{{ $low }}</div>
    </div>
    <div class="stat">
        <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <div class="stat-lbl">Crowded</div>
        <div class="stat-val">{{ $crowd }}</div>
    </div>
</div>

@forelse($grouped as $building => $bspaces)
<div class="bsect">
    <div class="bhdr"><span class="btag">{{ $building }}</span><div class="bline"></div></div>
    <div class="sgrid">
        @foreach($bspaces as $i => $space)
        @php
            $st = strtolower($space->status);
            $bc = $st==='low' ? 'bl' : ($st==='moderate' ? 'bm' : ($st==='high' ? 'bh' : 'bf'));
            $pc = $st==='low' ? 'pfl' : ($st==='moderate' ? 'pfm' : ($st==='high' ? 'pfh' : 'pff'));
        @endphp
        <div class="sc" style="animation-delay:{{ min($i * .06, .5) }}s">
            <div class="sth">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
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
<div style="text-align:center;padding:60px;color:var(--text-muted);font-size:.9rem;">No campus spaces found.</div>
@endforelse
@endsection