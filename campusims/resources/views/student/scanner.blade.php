@extends('student.layout')
@section('title','QR Scanner')
@section('page-title','QR Scanner')
@section('page-sub','Scan a space QR code to check in')

@section('styles')
@vite('resources/css/scanner.css')
@endsection

@section('content')
<div class="scanner-wrap" data-scanner>

    {{-- Scanner --}}
    <div class="scanner-box">
        <div class="scanner-header">
            <div class="scanner-title">Scan Space QR Code</div>
            <div class="scanner-sub">Point your camera at the QR code posted at the space entrance</div>
        </div>

        <div class="camera-area">
            <video class="qr-video" data-qr-video playsinline autoplay muted></video>
            <canvas class="qr-canvas" data-qr-canvas></canvas>

            <div class="scan-overlay">
                <div class="scan-frame"><span></span><div class="scan-line"></div></div>
            </div>

            <div class="cam-denied" data-cam-denied>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
                <p data-cam-denied-msg>Camera access denied. Please allow camera permission to scan QR codes.</p>
                <button class="cam-retry" data-retry-camera>Try Again</button>
            </div>
        </div>

        <div class="scanner-status">
            <span class="status-dot"></span>
            <span data-status>Loading scanner...</span>
        </div>

        <div class="scanner-footer">
            <p class="scanner-hint">QR codes refresh every 15 minutes.<br>Ask an admin or staff to show you the current QR code.</p>
            <div class="trouble-box">
                <div class="trouble-title">Having trouble?</div>
                <div class="trouble-copy">Restart the camera or upload a clear screenshot/photo of the QR code.</div>
                <div class="fallback-actions">
                    <button type="button" class="retry-inline" data-retry-inline>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/>
                            <path d="M3 21v-5h5"/>
                            <path d="M3 12a9 9 0 0 1 15.74-6.26L21 8"/>
                            <path d="M16 8h5V3"/>
                        </svg>
                        Retry camera
                    </button>
                    <label class="upload-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <path d="M21 15l-5-5L5 21"/>
                        </svg>
                        Upload QR image
                        <input class="qr-file-input" type="file" accept="image/*" data-qr-file>
                    </label>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
@vite('resources/js/scanner.js')
@endsection
