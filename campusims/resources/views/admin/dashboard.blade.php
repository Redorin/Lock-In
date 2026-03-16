@extends('admin.layout')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('page-sub','Overview of campus spaces and users')
@section('styles')
<style>
.sg{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:20px;}
.sc{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:14px;padding:20px;backdrop-filter:blur(16px);box-shadow:inset 0 1px 0 rgba(255,255,255,.07);}
.sl{font-size:.68rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:10px;}
.sv{font-size:1.8rem;font-weight:800;letter-spacing:-.5px;}
.cb{color:var(--accent2);}.cp{color:var(--accent3);}.cw{color:rgba(255,255,255,.85);}.cy{color:var(--warn);}.cr{color:var(--danger);}
@media(max-width:600px){.sg{grid-template-columns:repeat(3,1fr);}.sc{padding:14px 10px;}.sv{font-size:1.3rem;}.sl{font-size:.58rem;}}
@media(max-width:380px){.sg{grid-template-columns:repeat(2,1fr);}}
</style>
@endsection
@section('content')
<div class="sg">
    <div class="sc"><div class="sl">Total Spaces</div><div class="sv cb">{{ $stats['total_spaces'] }}</div></div>
    <div class="sc"><div class="sl">Total Users</div><div class="sv cw">{{ $stats['total_users'] }}</div></div>
    <div class="sc"><div class="sl">Total Capacity</div><div class="sv cp">{{ number_format($stats['total_capacity']) }}</div></div>
    <div class="sc"><div class="sl">Occupancy</div><div class="sv cy">{{ $stats['current_occupancy'] }}</div></div>
    <div class="sc"><div class="sl">Avg Rate</div><div class="sv cr">{{ $stats['avg_occupancy'] }}%</div></div>
</div>
<div class="gc"><div class="gci">
    <div class="ct">Quick View: All Spaces <div class="ctl"></div></div>
    <div class="table-wrap">
        <table class="dt">
            <thead><tr><th>Space Name</th><th>Building</th><th>Occupancy</th><th>%</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($spaces as $s)
                <tr>
                    <td style="color:var(--white);font-weight:500;">{{ $s->name }}</td>
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
</div></div>
@endsection