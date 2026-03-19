@extends('student.layout')
@section('title','Dashboard')
@section('page-title','Live Space Availability')
@section('page-sub','Real-time occupancy across all campus buildings')

@section('styles')
<style>
    /* ── Search bar ── */
    .search-wrap {
        position: relative;
        margin-bottom: 20px;
        animation: statIn .4s var(--ease) both;
    }
    .search-wrap svg {
        position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
        width: 18px; height: 18px; color: var(--text-muted); pointer-events: none;
        transition: color var(--t) var(--ease);
    }
    .search-input {
        width: 100%; padding: 13px 16px 13px 46px;
        font-family: 'Inter', sans-serif; font-size: .9rem;
        background: var(--surface); border: 1.5px solid var(--border2);
        border-radius: 14px; color: var(--text); outline: none;
        box-shadow: var(--shadow-sm);
        transition: border-color var(--t) var(--ease), box-shadow var(--t) var(--ease), background var(--t) var(--ease);
    }
    .search-input::placeholder { color: var(--text-muted); }
    .search-input:focus {
        border-color: var(--accent); background: var(--surface);
        box-shadow: 0 0 0 3px var(--accent-bg), var(--shadow-sm);
    }
    .search-input:focus + .search-wrap svg,
    .search-wrap:focus-within svg { color: var(--accent2); }
    .search-clear {
        position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
        width: 26px; height: 26px; border-radius: 50%;
        background: var(--surface2); border: 1px solid var(--border);
        display: none; align-items: center; justify-content: center;
        cursor: pointer; color: var(--text-muted);
        transition: all var(--t) var(--ease);
    }
    .search-clear.show { display: flex; }
    .search-clear:hover { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-border); }
    .search-clear svg { width: 13px; height: 13px; }

    /* ── Stats ── */
    .stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; margin-bottom: 20px; }
    .stat {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 16px; padding: 18px 20px;
        box-shadow: var(--shadow-sm), var(--inset);
        animation: statIn .5s var(--ease) both;
        transition: transform var(--t) var(--ease), box-shadow var(--t) var(--ease), background var(--t) var(--ease);
        position: relative; overflow: hidden; cursor: default;
    }
    .stat::after { content:''; position:absolute; inset:0; background:linear-gradient(135deg,var(--accent-bg),transparent); opacity:0; transition:opacity var(--t) var(--ease); }
    .stat:hover { transform: translateY(-3px); box-shadow: var(--shadow-md), var(--inset); }
    .stat:hover::after { opacity: 1; }
    .stat:nth-child(1){animation-delay:.05s}.stat:nth-child(2){animation-delay:.1s}.stat:nth-child(3){animation-delay:.15s}
    @keyframes statIn { from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)} }
    .stat-icon { width:34px;height:34px;border-radius:9px;background:var(--accent-bg);border:1px solid var(--accent-border);display:flex;align-items:center;justify-content:center;margin-bottom:12px;position:relative;z-index:1; }
    .stat-icon svg { width:16px;height:16px;color:var(--accent2); }
    .stat-lbl { font-size:.66rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px;position:relative;z-index:1; }
    .stat-val { font-family:'Plus Jakarta Sans',sans-serif;font-size:1.8rem;font-weight:800;letter-spacing:-.6px;position:relative;z-index:1;background:linear-gradient(135deg,var(--text),var(--text-soft));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text; }
    .stat-val.blue { background:linear-gradient(135deg,var(--accent),var(--accent2));-webkit-background-clip:text;background-clip:text; }

    /* ── No results ── */
    .no-results {
        text-align: center; padding: 56px 20px;
        animation: statIn .3s var(--ease) both;
        display: none;
    }
    .no-results.show { display: block; }
    .no-results-icon { width: 56px; height: 56px; border-radius: 16px; background: var(--surface2); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
    .no-results-icon svg { width: 26px; height: 26px; color: var(--text-muted); opacity: .5; }
    .no-results h3 { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: 6px; }
    .no-results p { font-size: .83rem; color: var(--text-muted); }

    /* ── Building sections ── */
    .bsect { margin-bottom: 28px; }
    .bhdr { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
    .btag { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .68rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--text-muted); }
    .bline { flex: 1; height: 1px; background: var(--border); }
    .bcount { font-size: .7rem; color: var(--text-muted); background: var(--surface2); border: 1px solid var(--border); padding: 2px 8px; border-radius: 99px; }

    /* ── Space cards ── */
    .sgrid { display: grid; grid-template-columns: repeat(auto-fill, minmax(185px,1fr)); gap: 12px; }
    .sc {
        background: var(--surface); border: 1px solid var(--border);
        border-radius: 16px; overflow: hidden;
        box-shadow: var(--shadow-sm), var(--inset);
        transition: transform var(--t) var(--ease), border-color var(--t) var(--ease), box-shadow var(--t) var(--ease), background var(--t) var(--ease);
        animation: cardIn .5s var(--ease) both;
        cursor: pointer;
    }
    .sc:hover { transform: translateY(-4px); border-color: var(--accent-border); box-shadow: var(--shadow-md), var(--inset); }
    .sc:active { transform: translateY(-1px) scale(.98); }
    @keyframes cardIn { from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)} }

    .sth {
        height: 88px; background: var(--surface2);
        display: flex; align-items: center; justify-content: center;
        border-bottom: 1px solid var(--border);
        position: relative; overflow: hidden;
        transition: background var(--t) var(--ease);
    }
    .sth::before { content:''; position:absolute; inset:0; background:radial-gradient(ellipse at center,var(--accent-bg) 0%,transparent 70%); opacity:0; transition:opacity var(--t) var(--ease); }
    .sc:hover .sth::before { opacity: 1; }
    .sth svg { width:28px;height:28px;color:var(--accent2);opacity:.2;transition:opacity var(--t) var(--ease),transform var(--t) var(--ease-back); }
    .sc:hover .sth svg { opacity:.45; transform:scale(1.15); }

    /* Tap ripple hint */
    .sth-tap {
        position: absolute; bottom: 6px; right: 8px;
        font-size: .58rem; font-weight: 600; color: var(--accent2);
        opacity: 0; transition: opacity var(--t) var(--ease);
        display: flex; align-items: center; gap: 4px;
        background: var(--accent-bg); border: 1px solid var(--accent-border);
        padding: 2px 7px; border-radius: 99px;
    }
    .sth-tap svg { width: 9px; height: 9px; }
    .sc:hover .sth-tap { opacity: 1; }

    .sbody { padding: 14px; }
    .sname { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .88rem; font-weight: 700; margin-bottom: 10px; color: var(--text); }
    .smeta { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
    .scnt { font-size: .8rem; font-weight: 600; color: var(--text-soft); }
    .badge { font-size: .6rem; font-weight: 700; letter-spacing: .05em; padding: 2px 8px; border-radius: 99px; }
    .bl { background: var(--accent-bg); color: var(--accent2); border: 1px solid var(--accent-border); }
    .bm { background: var(--warn-bg); color: var(--warn); border: 1px solid var(--warn-border); }
    .bh { background: var(--danger-bg); color: var(--danger); border: 1px solid var(--danger-border); }
    .bf { background: rgba(220,38,38,.15); color: var(--danger); border: 1px solid var(--danger-border); }
    .pt { height: 3px; background: var(--border); border-radius: 99px; overflow: hidden; }
    .pf { height: 100%; border-radius: 99px; transition: width 1s var(--ease); }
    .pfl { background: linear-gradient(90deg, var(--accent), var(--accent2)); }
    .pfm { background: linear-gradient(90deg, var(--warn), #f59e0b); }
    .pfh { background: linear-gradient(90deg, var(--danger), #f87171); }
    .pff { background: var(--danger); }

    /* ════════════════════════
       SPACE DETAIL MODAL
    ════════════════════════ */
    .space-modal-overlay {
        display: none; position: fixed; inset: 0; z-index: 600;
        background: var(--overlay);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        align-items: flex-end;
        justify-content: center;
        padding: 0;
        animation: overlayIn .25s ease both;
    }
    .space-modal-overlay.open { display: flex; }
    @keyframes overlayIn { from{opacity:0} to{opacity:1} }

    .space-modal {
        background: var(--surface);
        border: 1px solid var(--border2);
        border-radius: 28px 28px 0 0;
        width: 100%; max-width: 560px;
        max-height: 90vh;
        overflow-y: auto;
        animation: sheetUp .35s var(--ease) both;
        box-shadow: 0 -8px 48px rgba(0,0,0,.2);
        position: relative;
    }
    @keyframes sheetUp { from{transform:translateY(100%);opacity:0} to{transform:translateY(0);opacity:1} }

    /* On larger screens, show as centered modal */
    @media(min-width:600px) {
        .space-modal-overlay { align-items: center; padding: 20px; }
        .space-modal { border-radius: 24px; max-height: 85vh; animation: modalIn .3s var(--ease) both; }
        @keyframes modalIn { from{opacity:0;transform:translateY(20px) scale(.96)} to{opacity:1;transform:translateY(0) scale(1)} }
    }

    /* Drag handle (mobile) */
    .modal-handle {
        width: 40px; height: 4px; border-radius: 99px;
        background: var(--border2); margin: 12px auto 0;
    }
    @media(min-width:600px) { .modal-handle { display: none; } }

    /* Modal header image area */
    .sm-hero {
        height: 160px; position: relative;
        background: var(--surface2);
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
    }
    .sm-hero::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(ellipse at center, var(--accent-bg) 0%, transparent 70%);
    }
    .sm-hero svg { width: 52px; height: 52px; color: var(--accent2); opacity: .15; }
    .sm-hero-badge {
        position: absolute; top: 14px; right: 14px;
        padding: 5px 12px; border-radius: 99px;
        font-size: .68rem; font-weight: 700; letter-spacing: .05em;
    }
    .sm-close {
        position: absolute; top: 14px; left: 14px;
        width: 34px; height: 34px; border-radius: 50%;
        background: var(--surface); border: 1px solid var(--border2);
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: var(--text-soft);
        transition: all var(--t) var(--ease);
        box-shadow: var(--shadow-sm);
    }
    .sm-close:hover { background: var(--danger-bg); color: var(--danger); border-color: var(--danger-border); }
    .sm-close svg { width: 15px; height: 15px; }

    /* Modal body */
    .sm-body { padding: 22px 24px 28px; }

    .sm-building { font-size: .7rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--accent2); margin-bottom: 4px; }
    .sm-name { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.5rem; font-weight: 800; letter-spacing: -.4px; color: var(--text); margin-bottom: 16px; }

    /* Occupancy meter */
    .sm-occ-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
    .sm-occ-label { font-size: .8rem; color: var(--text-soft); font-weight: 500; }
    .sm-occ-num { font-family: 'Plus Jakarta Sans', sans-serif; font-size: .9rem; font-weight: 700; color: var(--text); }
    .sm-bar { height: 8px; background: var(--surface2); border-radius: 99px; overflow: hidden; margin-bottom: 18px; border: 1px solid var(--border); }
    .sm-bar-fill { height: 100%; border-radius: 99px; transition: width 1s var(--ease); }

    /* Stats grid */
    .sm-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; margin-bottom: 22px; }
    .sm-stat {
        background: var(--surface2); border: 1px solid var(--border);
        border-radius: 12px; padding: 14px 12px; text-align: center;
        transition: all var(--t) var(--ease);
    }
    .sm-stat:hover { border-color: var(--accent-border); background: var(--accent-bg); }
    .sm-stat-val { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.2rem; font-weight: 800; color: var(--text); }
    .sm-stat-lbl { font-size: .65rem; font-weight: 600; letter-spacing: .06em; text-transform: uppercase; color: var(--text-muted); margin-top: 3px; }

    /* Divider */
    .sm-div { height: 1px; background: var(--border); margin: 18px 0; }

    /* Info row */
    .sm-info-row { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
    .sm-info-row svg { width: 16px; height: 16px; color: var(--text-muted); flex-shrink: 0; }
    .sm-info-text { font-size: .84rem; color: var(--text-soft); }
    .sm-info-text strong { color: var(--text); font-weight: 600; }

    /* Full notice */
    .sm-full-notice {
        background: var(--danger-bg); border: 1px solid var(--danger-border);
        border-radius: 12px; padding: 14px 16px;
        display: flex; align-items: center; gap: 12px;
        margin-bottom: 16px;
        font-size: .83rem; color: var(--danger);
    }
    .sm-full-notice svg { width: 18px; height: 18px; flex-shrink: 0; }

    /* CTA button */
    .sm-cta {
        display: flex; align-items: center; justify-content: center; gap: 10px;
        width: 100%; padding: 15px;
        background: linear-gradient(135deg, var(--accent), #6366f1);
        color: #fff;
        font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 1rem;
        border: none; border-radius: 14px; cursor: pointer;
        box-shadow: 0 4px 20px var(--accent-glow);
        text-decoration: none;
        transition: all var(--t) var(--ease);
        position: relative; overflow: hidden;
    }
    .sm-cta::before { content:''; position:absolute; inset:0; background:linear-gradient(180deg,rgba(255,255,255,.12) 0%,transparent 100%); pointer-events:none; }
    .sm-cta:hover { transform: translateY(-2px); box-shadow: 0 6px 28px var(--accent-glow); }
    .sm-cta:active { transform: translateY(0) scale(.98); }
    .sm-cta svg { width: 20px; height: 20px; }
    .sm-cta.disabled {
        background: var(--surface2); color: var(--text-muted);
        border: 1px solid var(--border); cursor: not-allowed;
        box-shadow: none;
    }
    .sm-cta.disabled:hover { transform: none; }

    /* Already checked in notice */
    .sm-checkin-notice {
        background: var(--accent-bg); border: 1px solid var(--accent-border);
        border-radius: 12px; padding: 12px 16px;
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 14px; font-size: .82rem; color: var(--accent2);
    }
    .sm-checkin-notice svg { width: 16px; height: 16px; flex-shrink: 0; }

    @media(max-width:600px){
        .stats{gap:8px;}.stat{padding:12px 14px;}.stat-val{font-size:1.4rem;}
        .sgrid{grid-template-columns:1fr 1fr;}.sth{height:70px;}
        .sm-hero{height:130px;}
        .sm-body{padding:18px 18px 24px;}
        .sm-name{font-size:1.3rem;}
        .sm-stats{grid-template-columns:repeat(3,1fr);}
    }
    @media(max-width:380px){ .sgrid{grid-template-columns:1fr;} }
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

{{-- Stat cards --}}
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

{{-- Search bar --}}
<div class="search-wrap" style="position:relative;">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="position:absolute;left:16px;top:50%;transform:translateY(-50%);width:18px;height:18px;color:var(--text-muted);pointer-events:none;z-index:1;">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
    </svg>
    <input type="text" id="spaceSearch" class="search-input"
           placeholder="Search spaces or buildings…"
           autocomplete="off"
           oninput="handleSearch(this.value)">
    <button class="search-clear" id="searchClear" onclick="clearSearch()" title="Clear search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
</div>

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
        <div class="bline"></div>
        <span class="bcount">{{ count($bspaces) }} space{{ count($bspaces) !== 1 ? 's' : '' }}</span>
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
            <div class="sth">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                <div class="sth-tap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 8 16 12 12 16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    View
                </div>
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
<div style="text-align:center;padding:60px;color:var(--text-muted);">No campus spaces found.</div>
@endforelse
</div>

{{-- ════ SPACE DETAIL MODAL ════ --}}
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
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                <span class="sm-info-text">Building: <strong id="smInfoBuilding"></strong></span>
            </div>
            <div class="sm-info-row">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <span class="sm-info-text">QR codes refresh daily at <strong>midnight</strong></span>
            </div>
            <div class="sm-info-row">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
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