@extends('admin.layout')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('page-sub','Overview of campus spaces and users')

@section('styles')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(5,1fr); gap: 12px; margin-bottom: 20px; }
    .stat-card { background: var(--glass); border: 1px solid var(--glass-border); border-radius: var(--radius-md); padding: 20px; backdrop-filter: blur(16px); }
    .stat-label { font-size: .68rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: var(--text-muted); margin-bottom: 10px; }
    .stat-value { font-size: 1.8rem; font-weight: 800; letter-spacing: -.5px; }
    .c-purple { color: var(--accent2); }
    .c-green  { color: var(--accent); }
    .c-yellow { color: var(--accent3); }
    .c-red    { color: var(--danger); }
    .c-blue   { color: #60a5fa; }
</style>
@endsection

@section('content')
<div class="stats-grid">
    <div class="stat-card"><div class="stat-label">Total Spaces</div><div class="stat-value c-purple">{{ $stats['total_spaces'] }}</div></div>
    <div class="stat-card"><div class="stat-label">Total Users</div><div class="stat-value c-green">{{ $stats['total_users'] }}</div></div>
    <div class="stat-card"><div class="stat-label">Total Capacity</div><div class="stat-value c-blue">{{ number_format($stats['total_capacity']) }}</div></div>
    <div class="stat-card"><div class="stat-label">Current Occupancy</div><div class="stat-value c-yellow">{{ $stats['current_occupancy'] }}</div></div>
    <div class="stat-card"><div class="stat-label">Avg Occupancy Rate</div><div class="stat-value c-red">{{ $stats['avg_occupancy'] }}%</div></div>
</div>

<div class="glass-card">
    <div class="glass-card-inner">
        <div class="card-title">Quick View: All Spaces <div class="card-title-line"></div></div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Space Name</th>
                    <th>Building</th>
                    <th>Occupancy</th>
                    <th>Occupancy %</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($spaces as $space)
                <tr>
                    <td style="color:var(--text);font-weight:500;">{{ $space->name }}</td>
                    <td>{{ $space->building }}</td>
                    <td>{{ $space->current_occupancy }} / {{ $space->capacity }}</td>
                    <td>{{ $space->occupancy_percent }}%</td>
                    <td><span class="status-badge status-{{ strtolower($space->status) === 'low' || strtolower($space->status) === 'moderate' ? 'active' : 'inactive' }}">{{ $space->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="empty-state">No spaces found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection