@extends('admin.layout')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('page-sub','Overview of campus spaces and users')

@section('styles')
<style>
    .sg{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:22px;}
    .sc{
        background:var(--surface);border:1px solid var(--border);border-radius:24px;padding:22px 24px;
        box-shadow:var(--shadow-md),var(--inset);
        animation:statIn .5s var(--ease) both;
        transition:transform var(--t) var(--ease),box-shadow var(--t) var(--ease),background var(--t) var(--ease);
        position:relative;overflow:hidden;cursor:default;
    }
    .sc::after{content:'';position:absolute;inset:0;background:linear-gradient(135deg,var(--accent-bg),transparent);opacity:0;transition:opacity var(--t) var(--ease);}
    .sc:hover{transform:translateY(-4px);box-shadow:var(--shadow-lg),var(--inset);}
    .sc:hover::after{opacity:1;}
    .sc:nth-child(1){animation-delay:.04s}.sc:nth-child(2){animation-delay:.08s}.sc:nth-child(3){animation-delay:.12s}.sc:nth-child(4){animation-delay:.16s}.sc:nth-child(5){animation-delay:.2s}
    @keyframes statIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)}}
    .sc-icon{width:32px;height:32px;border-radius:9px;background:var(--accent-bg);border:1px solid var(--accent-border);display:flex;align-items:center;justify-content:center;margin-bottom:12px;position:relative;z-index:1;}
    .sc-icon svg{width:15px;height:15px;color:var(--accent2);}
    .sl{font-size:.65rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);margin-bottom:4px;position:relative;z-index:1;}
    .sv{font-family:'Plus Jakarta Sans',sans-serif;font-size:1.7rem;font-weight:800;letter-spacing:-.5px;position:relative;z-index:1;background:linear-gradient(135deg,var(--text),var(--text-soft));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
    .sv.accent{background:linear-gradient(135deg,var(--accent),var(--accent2));-webkit-background-clip:text;background-clip:text;}
    .qg{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px;}
    .qc{background:var(--surface);border:1px solid var(--border);border-radius:18px;padding:16px 18px;text-decoration:none;color:inherit;display:flex;align-items:center;gap:14px;transition:transform var(--t) var(--ease),border-color var(--t) var(--ease),box-shadow var(--t) var(--ease);}
    .qc:hover{transform:translateY(-2px);border-color:var(--accent-border);box-shadow:var(--shadow-md);}
    .qci{width:38px;height:38px;border-radius:12px;display:flex;align-items:center;justify-content:center;border:1px solid var(--border2);background:var(--surface2);color:var(--text-soft);flex-shrink:0;}
    .qci svg{width:18px;height:18px;}
    .qc.warn .qci{background:var(--warn-bg);border-color:var(--warn-border);color:var(--warn);}
    .qc.danger .qci{background:var(--danger-bg);border-color:var(--danger-border);color:var(--danger);}
    .qc.accent .qci{background:var(--accent-bg);border-color:var(--accent-border);color:var(--accent2);}
    .qcl{font-size:.7rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--text-muted);margin-bottom:3px;}
    .qcv{font-size:1.1rem;font-weight:800;color:var(--text);font-family:'Plus Jakarta Sans',sans-serif;}
    .dg{display:grid;grid-template-columns:minmax(0,1.05fr) minmax(0,.95fr);gap:16px;margin-bottom:18px;}
    .time-pill{display:inline-flex;align-items:center;gap:6px;padding:5px 10px;border-radius:99px;background:var(--accent-bg);border:1px solid var(--accent-border);color:var(--accent2);font-size:.72rem;font-weight:700;}

    @media(max-width:900px){.qg{grid-template-columns:repeat(2,1fr);}.dg{grid-template-columns:1fr;}}
    @media(max-width:768px){.sg{grid-template-columns:repeat(3,1fr);}.sc:nth-child(4),.sc:nth-child(5){display:none;}}
    @media(max-width:480px){.sg{grid-template-columns:repeat(2,1fr);}}
</style>
@endsection

@section('content')
<div class="sg">
    <x-stat-card label="Total Spaces" :value="$stats['total_spaces']" accent>
        <x-slot:icon><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></x-slot:icon>
    </x-stat-card>
    <x-stat-card label="Total Users" :value="$stats['total_users']">
        <x-slot:icon><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></x-slot:icon>
    </x-stat-card>
    <x-stat-card label="Total Capacity" :value="number_format($stats['total_capacity'])">
        <x-slot:icon><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></x-slot:icon>
    </x-stat-card>
    <x-stat-card label="Occupancy" :value="$stats['current_occupancy']">
        <x-slot:icon><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></x-slot:icon>
    </x-stat-card>
    <x-stat-card label="Avg Rate" :value="$stats['avg_occupancy'] . '%'">
        <x-slot:icon><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></x-slot:icon>
    </x-stat-card>
</div>

<div class="qg">
    <a class="qc danger" href="{{ route('admin.spaces', ['status' => 'full']) }}">
        <div class="qci"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg></div>
        <div><div class="qcl">Full spaces</div><div class="qcv">{{ $quickFilters['full_spaces'] }}</div></div>
    </a>
    <a class="qc warn" href="{{ route('admin.spaces', ['status' => 'high']) }}">
        <div class="qci"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
        <div><div class="qcl">High occupancy</div><div class="qcv">{{ $quickFilters['high_occupancy'] }}</div></div>
    </a>
    <a class="qc" href="{{ route('admin.users', ['status' => 'inactive']) }}">
        <div class="qci"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="18" y1="8" x2="23" y2="13"/><line x1="23" y1="8" x2="18" y2="13"/></svg></div>
        <div><div class="qcl">Inactive users</div><div class="qcv">{{ $quickFilters['inactive_users'] }}</div></div>
    </a>
    <a class="qc accent" href="{{ route('admin.verifications') }}">
        <div class="qci"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
        <div><div class="qcl">Pending verifications</div><div class="qcv">{{ $quickFilters['pending_verifications'] }}</div></div>
    </a>
</div>

<div class="dg">
    <div class="gc" style="animation:pageIn .5s .16s var(--ease) both;opacity:0;animation-fill-mode:forwards;">
        <div class="gci">
            <div class="ct">Currently Checked In <span style="font-size:.75rem;font-weight:500;color:var(--muted);">{{ $activeCheckIns->count() }} active</span><div class="ctl"></div></div>
            @if($activeCheckIns->isEmpty())
                <x-empty-state title="No active check-ins" message="When students scan into a space, they will appear here immediately.">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></x-slot:icon>
                </x-empty-state>
            @else
                <div class="table-wrap">
                    <table class="dt">
                        <thead><tr><th>Student</th><th>Space</th><th>Checked In</th><th>Duration</th></tr></thead>
                        <tbody>
                            @foreach($activeCheckIns as $checkIn)
                            <tr>
                                <td style="color:var(--text);font-weight:600;font-family:'Plus Jakarta Sans',sans-serif;">{{ $checkIn->user?->name ?? 'Unknown user' }}</td>
                                <td>{{ $checkIn->space?->building }} - {{ $checkIn->space?->name ?? 'Unknown space' }}</td>
                                <td>{{ $checkIn->checked_in_at->format('M d, g:i A') }}</td>
                                <td><span class="time-pill">{{ $checkIn->checked_in_at->diffForHumans(null, true) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
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
                    @empty<tr><td colspan="5"><x-empty-state title="No spaces found" message="Add campus spaces to start tracking occupancy."><x-slot:icon><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></x-slot:icon></x-empty-state></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
@endsection
