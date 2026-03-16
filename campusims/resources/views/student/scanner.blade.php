@extends('student.layout')
@section('title','QR Scanner')
@section('page-title','QR Scanner')
@section('page-sub','Scan a space QR code to check in')

@section('styles')
<style>
    .scanner-wrap { display:flex; flex-direction:column; align-items:center; gap:20px; max-width:440px; margin:0 auto; }

    /* currently checked in card */
    .active-card {
        width:100%; background:rgba(79,156,249,.06);
        border:1px solid rgba(79,156,249,.2);
        border-radius:var(--rl); padding:20px 24px;
        display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;
    }
    .active-label { font-size:.68rem; font-weight:700; letter-spacing:.1em; text-transform:uppercase; color:var(--accent2); margin-bottom:4px; }
    .active-space { font-size:1rem; font-weight:700; color:var(--white); }
    .active-time  { font-size:.78rem; color:var(--soft); margin-top:2px; }
    .checkout-btn {
        padding:10px 20px;
        background:rgba(248,113,113,.1); border:1px solid rgba(248,113,113,.2);
        border-radius:var(--rs); color:var(--danger);
        font-family:'Outfit',sans-serif; font-size:.85rem; font-weight:600;
        cursor:pointer; transition:background .15s; white-space:nowrap;
    }
    .checkout-btn:hover { background:rgba(248,113,113,.2); }

    /* scanner box */
    .scanner-box {
        width:100%; background:rgba(255,255,255,.04);
        border:1px solid rgba(255,255,255,.08);
        border-radius:var(--rl); overflow:hidden;
        backdrop-filter:blur(16px);
        box-shadow:inset 0 1px 0 rgba(255,255,255,.07);
    }
    .scanner-header { padding:20px 24px 0; }
    .scanner-title { font-size:1rem; font-weight:700; margin-bottom:4px; }
    .scanner-sub   { font-size:.8rem; color:var(--soft); }

    /* camera viewport */
    .camera-area {
        position:relative; margin:16px;
        border-radius:14px; overflow:hidden;
        background:#000; aspect-ratio:1;
    }
    #qr-video {
        width:100%; height:100%;
        object-fit:cover; display:block;
    }
    /* scan overlay corners */
    .scan-overlay {
        position:absolute; inset:0;
        display:flex; align-items:center; justify-content:center;
        pointer-events:none;
    }
    .scan-frame {
        width:65%; height:65%; position:relative;
    }
    .scan-frame::before,.scan-frame::after,
    .scan-frame span::before,.scan-frame span::after {
        content:''; position:absolute;
        width:24px; height:24px;
        border-color:var(--accent2); border-style:solid;
    }
    .scan-frame::before  { top:0;    left:0;  border-width:3px 0 0 3px; }
    .scan-frame::after   { top:0;    right:0; border-width:3px 3px 0 0; }
    .scan-frame span::before { bottom:0; left:0;  border-width:0 0 3px 3px; }
    .scan-frame span::after  { bottom:0; right:0; border-width:0 3px 3px 0; }
    /* scan line animation */
    .scan-line {
        position:absolute; left:10%; right:10%; height:2px;
        background:linear-gradient(90deg,transparent,var(--accent2),transparent);
        animation:scanMove 2s ease-in-out infinite;
        box-shadow:0 0 8px var(--accent2);
    }
    @keyframes scanMove {
        0%  { top:10%; }
        50% { top:85%; }
        100%{ top:10%; }
    }

    .scanner-status {
        text-align:center; padding:12px 16px;
        font-size:.82rem; color:var(--soft);
        display:flex; align-items:center; justify-content:center; gap:8px;
    }
    .status-dot { width:8px; height:8px; border-radius:50%; background:var(--accent); animation:pulse 1.5s infinite; }
    @keyframes pulse{0%{opacity:1}50%{opacity:.3}100%{opacity:1}}
    .scanner-footer { padding:0 24px 20px; }
    .scanner-hint { font-size:.75rem; color:var(--muted); text-align:center; line-height:1.6; }

    /* permission denied state */
    .cam-denied {
        display:none; flex-direction:column; align-items:center;
        justify-content:center; padding:40px 20px; text-align:center;
        gap:12px; aspect-ratio:1;
    }
    .cam-denied svg { width:40px; height:40px; opacity:.3; color:var(--danger); }
    .cam-denied p { font-size:.85rem; color:var(--soft); }
    .cam-retry { padding:10px 20px; background:linear-gradient(135deg,#4f9cf9,#1a6fe8); color:#fff; border:none; border-radius:var(--rs); font-family:'Outfit',sans-serif; font-size:.85rem; font-weight:600; cursor:pointer; }
</style>
@endsection

@section('content')
<div class="scanner-wrap">

    {{-- Currently checked in --}}
    @if($activeCheckIn)
    <div class="active-card">
        <div>
            <div class="active-label">Currently Checked In</div>
            <div class="active-space">{{ $activeCheckIn->space->building }} — {{ $activeCheckIn->space->name }}</div>
            <div class="active-time">Since {{ $activeCheckIn->checked_in_at->format('g:i A') }} · Auto-checkout in 2 hrs</div>
        </div>
        <form method="POST" action="{{ route('checkin.checkout') }}">
            @csrf
            <button type="submit" class="checkout-btn">Check Out</button>
        </form>
    </div>
    @endif

    {{-- Scanner --}}
    <div class="scanner-box">
        <div class="scanner-header">
            <div class="scanner-title">Scan Space QR Code</div>
            <div class="scanner-sub">Point your camera at the QR code posted at the space entrance</div>
        </div>

        <div class="camera-area" id="cameraArea">
            <video id="qr-video" autoplay playsinline muted></video>
            <div class="scan-overlay">
                <div class="scan-frame">
                    <span></span>
                    <div class="scan-line"></div>
                </div>
            </div>
            <div class="cam-denied" id="camDenied">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
                <p>Camera access denied. Please allow camera permission to scan QR codes.</p>
                <button class="cam-retry" onclick="startCamera()">Try Again</button>
            </div>
        </div>

        <div class="scanner-status" id="scannerStatus">
            <span class="status-dot"></span>
            <span id="statusText">Initializing camera...</span>
        </div>

        <div class="scanner-footer">
            <p class="scanner-hint">QR codes change daily at midnight.<br>Ask an admin or staff to show you the current QR code.</p>
        </div>
    </div>

</div>
@endsection

@section('scripts')
{{-- jsQR library for QR decoding --}}
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
const video      = document.getElementById('qr-video');
const statusText = document.getElementById('statusText');
const camDenied  = document.getElementById('camDenied');
let   stream     = null;
let   scanning   = true;
let   canvas, ctx;

async function startCamera() {
    camDenied.style.display = 'none';
    video.style.display     = 'block';
    statusText.textContent  = 'Starting camera...';

    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 1280 } }
        });
        video.srcObject = stream;
        await video.play();
        statusText.textContent = 'Ready — point at a QR code';

        // Setup canvas for frame capture
        canvas = document.createElement('canvas');
        ctx    = canvas.getContext('2d', { willReadFrequently: true });

        scanning = true;
        requestAnimationFrame(scanFrame);
    } catch (err) {
        video.style.display    = 'none';
        camDenied.style.display = 'flex';
        statusText.textContent  = 'Camera unavailable';
    }
}

function scanFrame() {
    if (!scanning) return;

    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.width  = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code      = jsQR(imageData.data, imageData.width, imageData.height, {
            inversionAttempts: 'dontInvert'
        });

        if (code) {
            const url = code.data;
            // Only process if it's a CampuSIMS check-in URL
            if (url.includes('/checkin/scan')) {
                scanning = false;
                statusText.textContent = '✓ QR detected — checking in...';
                if (stream) stream.getTracks().forEach(t => t.stop());
                window.location.href = url;
                return;
            }
        }
    }
    requestAnimationFrame(scanFrame);
}

// Start camera on page load
startCamera();

// Stop camera when leaving page
window.addEventListener('beforeunload', () => {
    scanning = false;
    if (stream) stream.getTracks().forEach(t => t.stop());
});
</script>
@endsection