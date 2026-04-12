@extends('student.layout')
@section('title','Dashboard')
@section('page-title','Live Space Availability')
@section('page-sub','Real-time occupancy across all campus buildings')

@section('styles')
<style>
    /* ── Search bar (Nexus Top style) ── */
    .search-wrap {
        position: relative;
        margin-bottom: 32px;
        animation: statIn .4s var(--ease) both;
        max-width: 480px;
    }
    .search-wrap svg {
        position: absolute; left: 20px; top: 50%; transform: translateY(-50%);
        width: 18px; height: 18px; color: var(--text-muted); pointer-events: none;
        transition: color var(--t) var(--ease);
    }
    .search-input {
        width: 100%; padding: 16px 20px 16px 52px;
        font-family: 'Inter', sans-serif; font-size: .95rem; font-weight: 500;
        background: var(--surface2); border: 1px solid var(--border);
        border-radius: 99px; /* absolute pill shape */
        color: var(--text); outline: none;
        box-shadow: inset 0 2px 8px rgba(0,0,0,.15);
        transition: border-color var(--t) var(--ease), box-shadow var(--t) var(--ease), background var(--t) var(--ease);
    }
    .search-input::placeholder { color: var(--text-muted); }
    .search-input:focus {
        border-color: var(--accent); background: var(--surface);
        box-shadow: 0 0 0 4px var(--accent-bg), inset 0 2px 8px rgba(0,0,0,.1);
    }
    .search-input:focus + .search-wrap svg,
    .search-wrap:focus-within svg { color: var(--accent2); }
    .search-clear {
        position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
        width: 34px; height: 34px; border-radius: 50%;
        background: var(--surface); border: 1px solid var(--border);
        display: none; align-items: center; justify-content: center;
        cursor: pointer; color: var(--text-muted);
        transition: all var(--t) var(--ease);
        box-shadow: var(--shadow-sm);
    }
    .search-clear.show { display: flex; }
    .search-clear:hover { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-border); }
    .search-clear svg { width: 15px; height: 15px; }

    /* ── Nexus Dashboard Cards Concept ── */
    .dashboard-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 24px; margin-bottom: 32px; align-items: flex-start; }
    
    .stats { display: grid; grid-template-columns: 1fr; gap: 16px; align-self: flex-start; position: sticky; top: 24px; z-index: 10; }
    .stat {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 24px; padding: 22px;
        box-shadow: var(--shadow-md), var(--inset);
        animation: statIn .5s var(--ease) both;
        transition: transform var(--t) var(--ease), box-shadow var(--t) var(--ease), background var(--t) var(--ease);
        position: relative; overflow: hidden; cursor: default;
        min-height: 140px;
        display: flex; flex-direction: column; justify-content: space-between;
    }
    .stat::before { content:''; position:absolute; top:-50px; right:-50px; width:150px; height:150px; border-radius:50%; background: radial-gradient(circle, var(--accent-bg) 0%, transparent 70%); pointer-events: none; }
    .stat:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg), var(--inset); border-color: var(--accent-border); }
    
    .stat-main { background: linear-gradient(145deg, var(--accent), #6366f1); border:none; position:relative; }
    .stat-main::before { background: radial-gradient(circle, rgba(255,255,255,.2) 0%, transparent 70%); }
    .stat-main .stat-lbl { color: rgba(255,255,255,.8); }
    .stat-main .stat-val { background: none; -webkit-text-fill-color: #fff; color: #fff; text-shadow: 0 2px 10px rgba(0,0,0,.2); }
    .stat-main .stat-icon { background: rgba(0,0,0,.2); border: 1px solid rgba(255,255,255,.2); }
    .stat-main .stat-icon svg { color: #fff; }

    @keyframes statIn { from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)} }
    
    .stat-top-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
    .stat-icon { width:38px; height:38px; border-radius:12px; background:var(--surface2); border:1px solid var(--border); display:flex; align-items:center; justify-content:center; position:relative; z-index:1; flex-shrink:0; }
    .stat-icon svg { width:16px; height:16px; color:var(--accent2); }
    .stat-btn { width:28px;height:28px;border-radius:50%;background:var(--surface3);display:flex;align-items:center;justify-content:center;color:var(--text-soft);flex-shrink:0; }
    .stat-btn svg { width:14px; height:14px; }
    
    .stat-lbl { font-size:.78rem;font-weight:600;letter-spacing:.02em;color:var(--text-soft);margin-bottom:2px;position:relative;z-index:1; }
    .stat-val { font-family:'Plus Jakarta Sans',sans-serif;font-size:2.4rem;font-weight:800;letter-spacing:-1px;position:relative;z-index:1;background:linear-gradient(135deg,var(--text),var(--text-soft));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text; line-height: 1.1; }

    /* ── Building sections ── */
    .bsect { background: var(--surface); border: 1px solid var(--border); border-radius: 28px; padding: 24px; margin-bottom: 24px; box-shadow: var(--shadow-md), var(--inset); }
    .bhdr { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
    .btag { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.1rem; font-weight: 800; letter-spacing: -.3px; color: var(--text); }
    .bcount { font-size: .75rem; font-weight: 600; color: var(--accent2); background: var(--accent-bg); padding: 4px 12px; border-radius: 99px; }

    /* ── Space cards (Glass list style) ── */
    .sgrid { display: flex; flex-direction: column; gap: 12px; }
    .sc {
        background: var(--surface2); border: 1px solid var(--border);
        border-radius: 20px; overflow: hidden;
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px;
        transition: transform var(--t) var(--ease), border-color var(--t) var(--ease), box-shadow var(--t) var(--ease), background var(--t) var(--ease);
        animation: cardIn .5s var(--ease) both;
        cursor: pointer;
    }
    .sc:hover { transform: translateX(4px); border-color: var(--accent-border); box-shadow: var(--shadow-sm), var(--inset); background: var(--surface3); }
    .sc:active { transform: translateX(0) scale(.99); }
    @keyframes cardIn { from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)} }
    
    .sc-left { display: flex; align-items: center; gap: 14px; flex: 1; }
    .sth {
        width: 40px; height: 40px; border-radius: 11px;
        background: var(--surface); border: 1px solid var(--border);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; box-shadow: var(--shadow-sm);
    }
    .sth svg { width: 18px; height: 18px; color: var(--accent2); }
    
    .sbody { flex: 1; }
    .sname { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .95rem; font-weight: 700; color: var(--text); margin-bottom: 2px; }
    .scnt { font-size: .8rem; font-weight: 500; color: var(--text-soft); }
    
    .sc-right { display: flex; align-items: center; gap: 24px; min-width: 140px; justify-content: flex-end; }
    
    .badge { font-size: .7rem; font-weight: 700; letter-spacing: .02em; padding: 4px 10px; border-radius: 99px; }
    .bl { background: var(--accent-bg); color: var(--accent2); border: 1px solid var(--accent-border); }
    .bm { background: var(--warn-bg); color: var(--warn); border: 1px solid var(--warn-border); }
    .bh { background: var(--danger-bg); color: var(--danger); border: 1px solid var(--danger-border); }
    .bf { background: rgba(220,38,38,.15); color: var(--danger); border: 1px solid var(--danger-border); }
    
    .pt { width: 60px; height: 6px; background: var(--border); border-radius: 99px; overflow: hidden; flex-shrink: 0; }
    .pf { height: 100%; border-radius: 99px; transition: width 1s var(--ease); }
    .pfl { background: linear-gradient(90deg, var(--accent), var(--accent2)); box-shadow: 0 0 10px var(--accent-glow); }
    .pfm { background: linear-gradient(90deg, var(--warn), #f59e0b); box-shadow: 0 0 10px rgba(251,191,36,.4); }
    .pfh { background: linear-gradient(90deg, var(--danger), #f87171); box-shadow: 0 0 10px rgba(248,113,113,.4); }
    .pff { background: var(--danger); }

    /* No results */
    .no-results {
        text-align: center; padding: 56px 20px;
        animation: statIn .3s var(--ease) both;
        display: none; background: var(--surface); border: 1px solid var(--border); border-radius: 28px;
    }
    .no-results.show { display: block; }
    .no-results-icon { width: 56px; height: 56px; border-radius: 16px; background: var(--surface2); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
    .no-results-icon svg { width: 26px; height: 26px; color: var(--text-muted); opacity: .5; }
    .no-results h3 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.1rem; font-weight: 700; color: var(--text); margin-bottom: 6px; }
    .no-results p { font-size: .85rem; color: var(--text-soft); }

    /* ═══════════ SPACE DETAIL MODAL ═══════════ */
    .space-modal-overlay {
        display: none; position: fixed; inset: 0; z-index: 600;
        background: var(--overlay); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
        align-items: flex-end; justify-content: center; padding: 0;
    }
    .space-modal-overlay.open { display: flex; }

    .space-modal {
        background: var(--surface); border: 1px solid var(--border2); border-radius: 28px 28px 0 0;
        width: 100%; max-width: 560px; max-height: 90vh; overflow-y: auto;
        animation: sheetUp .35s var(--ease) both; box-shadow: 0 -8px 48px rgba(0,0,0,.25);
        position: relative;
    }
    @keyframes sheetUp { from{transform:translateY(100%);opacity:0} to{transform:translateY(0);opacity:1} }

    @media(min-width:600px) {
        .space-modal-overlay { align-items: center; padding: 20px; }
        .space-modal { border-radius: 24px; max-height: 85vh; animation: modalIn .28s var(--ease) both; }
        @keyframes modalIn { from{opacity:0;transform:translateY(20px) scale(.96)} to{opacity:1;transform:translateY(0) scale(1)} }
    }

    .modal-handle { width: 40px; height: 4px; border-radius: 99px; background: var(--border2); margin: 12px auto 0; }
    @media(min-width:600px) { .modal-handle { display: none; } }

    .sm-hero {
        height: 160px; position: relative; background: var(--surface2); overflow: hidden;
        display: flex; align-items: center; justify-content: center;
    }
    .sm-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at center, var(--accent-bg) 0%, transparent 70%);
    }
    .sm-hero > svg { width: 48px; height: 48px; color: var(--accent2); opacity: .12; position: relative; z-index: 1; }
    .sm-hero-badge {
        position: absolute; top: 14px; right: 14px; padding: 4px 12px; border-radius: 99px;
        font-size: .68rem; font-weight: 700; letter-spacing: .05em; z-index: 2;
    }
    .sm-close {
        position: absolute; top: 14px; left: 14px; width: 32px; height: 32px; border-radius: 50%;
        background: var(--surface); border: 1px solid var(--border2); z-index: 2;
        display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--text-soft);
        transition: all var(--t) var(--ease); box-shadow: var(--shadow-sm);
    }
    .sm-close:hover { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-border); }
    .sm-close svg { width: 14px; height: 14px; }

    .sm-body { padding: 20px 22px 26px; }
    .sm-building { font-size: .68rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--accent2); margin-bottom: 3px; }
    .sm-name { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.4rem; font-weight: 800; letter-spacing: -.4px; color: var(--text); margin-bottom: 14px; }

    .sm-occ-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 7px; }
    .sm-occ-label { font-size: .78rem; color: var(--text-soft); font-weight: 500; }
    .sm-occ-num { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .85rem; font-weight: 700; color: var(--text); }
    .sm-bar { height: 7px; background: var(--surface2); border-radius: 99px; overflow: hidden; margin-bottom: 16px; border: 1px solid var(--border); }
    .sm-bar-fill { height: 100%; border-radius: 99px; transition: width 1s var(--ease); }

    .sm-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-bottom: 18px; }
    .sm-stat { background: var(--surface2); border: 1px solid var(--border); border-radius: 12px; padding: 12px; text-align: center; transition: all var(--t) var(--ease); }
    .sm-stat:hover { border-color: var(--accent-border); background: var(--accent-bg); }
    .sm-stat-val { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.15rem; font-weight: 800; color: var(--text); }
    .sm-stat-lbl { font-size: .62rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--text-muted); margin-top: 2px; }

    .sm-div { height: 1px; background: var(--border); margin: 16px 0; }

    .sm-full-notice {
        background: var(--danger-bg); border: 1px solid var(--danger-border); border-radius: 12px; padding: 12px 14px;
        display: flex; align-items: center; gap: 10px; margin-bottom: 14px; font-size: .82rem; color: var(--danger);
    }
    .sm-full-notice svg { width: 16px; height: 16px; flex-shrink: 0; }

    .sm-checkin-notice {
        background: var(--accent-bg); border: 1px solid var(--accent-border); border-radius: 12px; padding: 11px 14px;
        display: flex; align-items: center; gap: 10px; margin-bottom: 12px; font-size: .82rem; color: var(--accent2);
    }
    .sm-checkin-notice svg { width: 14px; height: 14px; flex-shrink: 0; }

    .sm-cta {
        display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 14px;
        background: linear-gradient(135deg, var(--accent), #6366f1); color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: .95rem;
        border: none; border-radius: 99px; cursor: pointer; box-shadow: 0 4px 20px var(--accent-glow);
        text-decoration: none; transition: all var(--t) var(--ease); position: relative; overflow: hidden;
    }
    .sm-cta::before { content:''; position:absolute; inset:0; background:linear-gradient(180deg,rgba(255,255,255,.12) 0%,transparent 100%); pointer-events:none; }
    .sm-cta:hover { transform: translateY(-2px); box-shadow: 0 6px 28px var(--accent-glow); }
    .sm-cta:active { transform: translateY(0) scale(.98); }
    .sm-cta svg { width: 18px; height: 18px; }
    .sm-cta.disabled { background: var(--surface2); color: var(--text-muted); border: 1px solid var(--border); cursor: not-allowed; box-shadow: none; }
    .sm-cta.disabled:hover { transform: none; }

    /* Info rows in modal */
    .sm-info-row {
        display: flex; align-items: center; gap: 12px; margin-bottom: 10px;
        background: var(--surface2); padding: 10px 14px; border-radius: 14px;
        border: 1px solid var(--border);
        transition: transform var(--t) var(--ease), border-color var(--t) var(--ease);
    }
    .sm-info-row:hover { transform: translateX(3px); border-color: var(--accent-border); }
    .sm-info-icon {
        width: 30px; height: 30px; border-radius: 9px;
        background: var(--surface); display: flex; align-items: center; justify-content: center;
        border: 1px solid var(--border); flex-shrink: 0;
    }
    .sm-info-icon svg { width: 14px; height: 14px; color: var(--accent2); display: block; }
    .sm-info-text { font-size: .84rem; color: var(--text-soft); line-height: 1.4; }
    .sm-info-text strong { color: var(--text); font-weight: 700; }

    @media(max-width:900px){
        .dashboard-grid { grid-template-columns: 1fr; }
        .stats { grid-template-columns: repeat(3, 1fr); position:static; }
        .search-wrap { max-width:100%; }
    }
    @media(max-width:640px){
        .stats { grid-template-columns: 1fr; gap:10px; }
        .stat { padding:18px; min-height:100px; }
        .stat-val { font-size:2rem; }
        .sc { flex-direction: column; align-items: flex-start; gap: 12px; }
        .sc-right { width: 100%; justify-content: space-between; min-width:unset; }
        .pt { width: 80px; }
        .search-input { border-radius: 16px; font-size:.875rem; padding:13px 16px 13px 44px; }
        .bsect { padding:18px 16px; border-radius:20px; }
    }
    @media(max-width:400px){
        .stats { grid-template-columns: repeat(3, 1fr); gap:8px; }
        .stat { padding:14px 12px; min-height:80px; border-radius:18px; }
        .stat-val { font-size:1.6rem; }
        .stat-lbl { font-size:.7rem; }
    }
</style>
@endsection

@section('content')
@php
    $total = $spaces->count();
    $low   = $spaces->where('status','LOW')->count();
    $crowd = $spaces->whereIn('status',['HIGH','FULL'])->count();

    // Check if student is currently checked in somewhere
    $activeCheckIn = auth()->user()->checkIns()->whereNull('checked_out_at')->with('space')->first() ?? null;
@endphp

{{-- Search bar at top --}}
<div class="search-wrap">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
    </svg>
    <input type="text" id="spaceSearch" class="search-input"
           placeholder="Search any building or space..."
           autocomplete="off"
           oninput="handleSearch(this.value)">
    <button class="search-clear" id="searchClear" onclick="clearSearch()" title="Clear search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
</div>

<div class="dashboard-grid">
    {{-- Top Left Layout: Huge Stat Cards --}}
    <div class="stats">
        {{-- Hero Stat --}}
        <div class="stat stat-main">
            <div class="stat-top-row">
                <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></div>
                <div class="stat-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg></div>
            </div>
            <div>
                <div class="stat-lbl">Total Spaces</div>
                <div class="stat-val">{{ $total }}</div>
            </div>
        </div>
        
        <div class="stat">
            <div class="stat-top-row">
                <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg></div>
                <div class="stat-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg></div>
            </div>
            <div>
                <div class="stat-lbl">Available Now</div>
                <div class="stat-val" style="color:var(--success);">{{ $low }}</div>
            </div>
        </div>
        
        <div class="stat">
            <div class="stat-top-row">
                <div class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
                <div class="stat-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg></div>
            </div>
            <div>
                <div class="stat-lbl">Crowded Spaces</div>
                <div class="stat-val" style="color:var(--danger);">{{ $crowd }}</div>
            </div>
        </div>
    </div>

    {{-- Top Right Area: Spaces list --}}
    <div class="spaces-area">
        {{-- No results --}}
        <div class="no-results" id="noResults">
            <div class="no-results-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>
            <h3>No spaces found</h3>
            <p>Try a different name or building.</p>
        </div>

        {{-- Space list --}}
        <div id="spacesList">
        @forelse($grouped as $building => $bspaces)
        <div class="bsect" data-building="{{ strtolower($building) }}">
            <div class="bhdr">
                <span class="btag">{{ $building }}</span>
                <span class="bcount">{{ count($bspaces) }}</span>
            </div>
            <div class="sgrid">
                @foreach($bspaces as $i => $space)
                @php
                    $st = strtolower($space->status);
                    $bc = $st==='low' ? 'bl' : ($st==='moderate' ? 'bm' : ($st==='high' ? 'bh' : 'bf'));
                    $pc = $st==='low' ? 'pfl' : ($st==='moderate' ? 'pfm' : ($st==='high' ? 'pfh' : 'pff'));
                @endphp
                <div class="sc"
                     style="animation-delay:{{ min($i * .06, .5) }}s"
                     data-name="{{ strtolower($space->name) }}"
                     data-building="{{ strtolower($space->building) }}"
                     onclick="openSpace({
                         id: {{ $space->id }},
                         name: '{{ addslashes($space->name) }}',
                         building: '{{ addslashes($space->building) }}',
                         occupancy: {{ $space->current_occupancy }},
                         capacity: {{ $space->capacity }},
                         percent: {{ $space->occupancy_percent }},
                         status: '{{ $space->status }}',
                         bc: '{{ $bc }}',
                         pc: '{{ $pc }}'
                     })">
                    <div class="sc-left">
                        <div class="sth">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                        </div>
                        <div class="sbody">
                            <div class="sname">{{ $space->name }}</div>
                            <div class="scnt">{{ $space->current_occupancy }} / {{ $space->capacity }}</div>
                        </div>
                    </div>
                    <div class="sc-right">
                        <div class="pt"><div class="pf {{ $pc }}" style="width:{{ $space->occupancy_percent }}%"></div></div>
                        <span class="badge {{ $bc }}">{{ $space->status }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:60px;color:var(--text-muted);">No campus spaces found.</div>
        @endforelse
        </div>
    </div>
</div>

@endsection

{{-- ════ SPACE DETAIL MODAL ════ --}}
@section('modals')
<div class="space-modal-overlay" id="spaceModal" onclick="handleOverlayClick(event)">
    <div class="space-modal" id="spaceModalInner">
        <div class="modal-handle"></div>

        {{-- Hero --}}
        <div class="sm-hero" id="smHero">
            <button class="sm-close" onclick="closeSpace()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span class="sm-hero-badge badge" id="smBadge"></span>
        </div>

        {{-- Body --}}
        <div class="sm-body">
            <div class="sm-building" id="smBuilding"></div>
            <div class="sm-name" id="smName"></div>

            {{-- Occupancy meter --}}
            <div class="sm-occ-row">
                <span class="sm-occ-label">Current Occupancy</span>
                <span class="sm-occ-num" id="smOccNum"></span>
            </div>
            <div class="sm-bar"><div class="sm-bar-fill" id="smBarFill"></div></div>

            {{-- Stats --}}
            <div class="sm-stats">
                <div class="sm-stat">
                    <div class="sm-stat-val" id="smCurrent"></div>
                    <div class="sm-stat-lbl">Present</div>
                </div>
                <div class="sm-stat">
                    <div class="sm-stat-val" id="smCapacity"></div>
                    <div class="sm-stat-lbl">Capacity</div>
                </div>
                <div class="sm-stat">
                    <div class="sm-stat-val" id="smAvail"></div>
                    <div class="sm-stat-lbl">Available</div>
                </div>
            </div>

            <div class="sm-div"></div>

            {{-- Info rows --}}
            <div class="sm-info-row">
                <div class="sm-info-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></div>
                <span class="sm-info-text">Building: <strong id="smInfoBuilding"></strong></span>
            </div>
            <div class="sm-info-row">
                <div class="sm-info-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                <span class="sm-info-text">QR codes refresh daily at <strong>midnight</strong></span>
            </div>
            <div class="sm-info-row">
                <div class="sm-info-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                <span class="sm-info-text">Auto check-out after <strong>2 hours</strong></span>
            </div>

            <div class="sm-div"></div>

            {{-- Full notice (hidden by default) --}}
            <div class="sm-full-notice" id="smFullNotice" style="display:none;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                This space is currently <strong>full</strong>. Please try another space.
            </div>

            {{-- Already checked in here notice --}}
            <div class="sm-checkin-notice" id="smCheckinNotice" style="display:none;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                <span id="smCheckinText"></span>
            </div>

            {{-- CTA --}}
            <a href="{{ route('student.scanner') }}" class="sm-cta" id="smCta">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="5" height="5" rx="1"/>
                    <rect x="16" y="3" width="5" height="5" rx="1"/>
                    <rect x="3" y="16" width="5" height="5" rx="1"/>
                    <line x1="16" y1="16" x2="21" y2="21"/>
                    <line x1="16" y1="16" x2="21" y2="16"/>
                    <line x1="16" y1="16" x2="16" y2="21"/>
                </svg>
                Scan QR to Check In
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// ── Space data from PHP ───────────────────────────────────────────────────────
const activeCheckIn = @json($activeCheckIn ? ['space_id' => $activeCheckIn->campus_space_id, 'space_name' => $activeCheckIn->space->name, 'checked_in_at' => $activeCheckIn->checked_in_at->format('g:i A')] : null);

// ── Search ────────────────────────────────────────────────────────────────────
function handleSearch(val) {
    const q = val.trim().toLowerCase();
    const sections = document.querySelectorAll('.bsect');
    const clearBtn = document.getElementById('searchClear');
    clearBtn.classList.toggle('show', q.length > 0);

    let anyVisible = false;
    sections.forEach(section => {
        const cards = section.querySelectorAll('.sc');
        let sectionHasMatch = false;
        cards.forEach(card => {
            const name = card.dataset.name || '';
            const building = card.dataset.building || '';
            const match = !q || name.includes(q) || building.includes(q);
            card.style.display = match ? '' : 'none';
            if (match) sectionHasMatch = true;
        });
        section.style.display = sectionHasMatch ? '' : 'none';
        if (sectionHasMatch) anyVisible = true;
    });

    document.getElementById('noResults').classList.toggle('show', !anyVisible && q.length > 0);
}

function clearSearch() {
    const inp = document.getElementById('spaceSearch');
    inp.value = '';
    handleSearch('');
    inp.focus();
}

// ── Space modal ───────────────────────────────────────────────────────────────
let currentSpaceId = null;

function openSpace(space) {
    currentSpaceId = space.id;

    // Populate hero
    const hero = document.getElementById('smHero');
    const barColors = { pfl:'linear-gradient(90deg,var(--accent),var(--accent2))', pfm:'linear-gradient(90deg,var(--warn),#f59e0b)', pfh:'linear-gradient(90deg,var(--danger),#f87171)', pff:'var(--danger)' };
    hero.style.background = '';

    // Badge
    const badge = document.getElementById('smBadge');
    badge.textContent = space.status;
    badge.className = 'sm-hero-badge badge ' + space.bc;

    // Text
    document.getElementById('smBuilding').textContent = space.building;
    document.getElementById('smName').textContent = space.name;
    document.getElementById('smOccNum').textContent = `${space.occupancy} / ${space.capacity} (${space.percent}%)`;
    document.getElementById('smCurrent').textContent = space.occupancy;
    document.getElementById('smCapacity').textContent = space.capacity;
    document.getElementById('smAvail').textContent = Math.max(0, space.capacity - space.occupancy);
    document.getElementById('smInfoBuilding').textContent = space.building;

    // Bar
    const fill = document.getElementById('smBarFill');
    fill.style.width = '0%';
    fill.style.background = barColors[space.pc] || 'var(--accent)';
    setTimeout(() => { fill.style.width = space.percent + '%'; }, 50);

    // Full notice
    const isFull = space.status === 'FULL';
    document.getElementById('smFullNotice').style.display = isFull ? 'flex' : 'none';

    // Check-in status
    const checkinNotice = document.getElementById('smCheckinNotice');
    const cta = document.getElementById('smCta');

    if (activeCheckIn && activeCheckIn.space_id === space.id) {
        checkinNotice.style.display = 'flex';
        document.getElementById('smCheckinText').textContent = `You're already checked in here since ${activeCheckIn.checked_in_at}.`;
        cta.textContent = 'Already Checked In ✓';
        cta.className = 'sm-cta disabled';
        cta.removeAttribute('href');
    } else if (isFull) {
        checkinNotice.style.display = 'none';
        cta.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg> Space is Full`;
        cta.className = 'sm-cta disabled';
        cta.removeAttribute('href');
    } else {
        checkinNotice.style.display = 'none';
        cta.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="5" height="5" rx="1"/><rect x="16" y="3" width="5" height="5" rx="1"/><rect x="3" y="16" width="5" height="5" rx="1"/><line x1="16" y1="16" x2="21" y2="21"/><line x1="16" y1="16" x2="21" y2="16"/><line x1="16" y1="16" x2="16" y2="21"/></svg> Scan QR to Check In`;
        cta.className = 'sm-cta';
        cta.href = '{{ route("student.scanner") }}';
    }

    // Open
    document.getElementById('spaceModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeSpace() {
    document.getElementById('spaceModal').classList.remove('open');
    document.body.style.overflow = '';
    currentSpaceId = null;
}

function handleOverlayClick(e) {
    if (e.target === document.getElementById('spaceModal')) closeSpace();
}

// Close on Escape
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSpace(); });

// Touch swipe down to close (mobile sheet)
let touchStartY = 0;
document.getElementById('spaceModalInner').addEventListener('touchstart', e => {
    touchStartY = e.touches[0].clientY;
}, { passive: true });
document.getElementById('spaceModalInner').addEventListener('touchend', e => {
    const delta = e.changedTouches[0].clientY - touchStartY;
    if (delta > 80) closeSpace();
}, { passive: true });
</script>
@endsection