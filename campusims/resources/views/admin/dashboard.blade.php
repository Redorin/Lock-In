@extends('admin.layout')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('page-sub','Overview of campus spaces and users')

@section('styles')
<style>
    .sg{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:22px;}
    .sc{
        background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:18px 20px;
        box-shadow:var(--shadow-sm),var(--inset);
        animation:statIn .5s var(--ease) both;
        transition:transform var(--t) var(--ease),box-shadow var(--t) var(--ease),background var(--t) var(--ease);
        position:relative;overflow:hidden;cursor:default;
    }
    .sc::after{content:'';position:absolute;inset:0;background:linear-gradient(135deg,var(--accent-bg),transparent);opacity:0;transition:opacity var(--t) var(--ease);}
    .sc:hover{transform:translateY(-3px);box-shadow:var(--shadow-md),var(--inset);}
    .sc:hover::after{opacity:1;}
    .sc:nth-child(1){animation-delay:.04s}.sc:nth-child(2){animation-delay:.08s}.sc:nth-child(3){animation-delay:.12s}.sc:nth-child(4){animation-delay:.16s}.sc:nth-child(5){animation-delay:.2s}
    @keyframes statIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
    .sc-icon{width:32px;height:32px;border-radius:9px;background:var(--accent-bg);border:1px solid var(--accent-border);display:flex;align-items:center;justify-content:center;margin-bottom:12px;position:relative;z-index:1;}
    .sc-icon svg{width:15px;height:15px;color:var(--accent2);}
    .sl{font-size:.65rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px;position:relative;z-index:1;}
    .sv{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.7rem;font-weight:800;letter-spacing:-.5px;position:relative;z-index:1;background:linear-gradient(135deg,var(--text),var(--text-soft));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
    .sv.accent{background:linear-gradient(135deg,var(--accent),var(--accent2));-webkit-background-clip:text;background-clip:text;}

    @media(max-width:768px){.sg{grid-template-columns:repeat(3,1fr);}.sc:nth-child(4),.sc:nth-child(5){display:none;}}
    @media(max-width:480px){.sg{grid-template-columns:repeat(2,1fr);}}
</style>
@endsection

@section('content')
<div class="sg">
    <div class="sc"><div class="sc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></div><div class="sl">Total Spaces</div><div class="sv accent">{{ $stats['total_spaces'] }}</div></div>
    <div class="sc"><div class="sc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div><div class="sl">Total Users</div><div class="sv">{{ $stats['total_users'] }}</div></div>
    <div class="sc"><div class="sc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div><div class="sl">Total Capacity</div><div class="sv">{{ number_format($stats['total_capacity']) }}</div></div>
    <div class="sc"><div class="sc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div><div class="sl">Occupancy</div><div class="sv">{{ $stats['current_occupancy'] }}</div></div>
    <div class="sc"><div class="sc-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div><div class="sl">Avg Rate</div><div class="sv">{{ $stats['avg_occupancy'] }}%</div></div>
</div>

<div class="gc" style="animation:pageIn .5s .2s var(--ease) both;opacity:0;animation-fill-mode:forwards;">
    <div class="gci">
        <div class="ct">All Spaces <div class="ctl"></div></div>
        <div class="table-wrap">
            <table class="dt">
                <thead><tr><th>Space</th><th>Building</th><th>Occupancy</th><th>%</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($spaces as $s)
                    <tr>
                        <td style="color:var(--text);font-weight:600;font-family:'Plus Jakarta Sans',sans-serif;">{{ $s->name }}</td>
                        <td>{{ $s->building }}</td>
                        <td>{{ $s->current_occupancy }} / {{ $s->capacity }}</td>
                        <td>{{ $s->occupancy_percent }}%</td>
                        <td><span class="sbadge {{ in_array($s->status,['LOW','MODERATE'])?'sa':'si' }}">{{ $s->status }}</span></td>
                    </tr>
                    @empty<tr><td colspan="5"><div class="empty">No spaces found.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection