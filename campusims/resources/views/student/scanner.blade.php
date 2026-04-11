@extends('student.layout')
@section('title','QR Scanner')
@section('page-title','QR Scanner')
@section('page-sub','Scan a space QR code to check in')

@section('styles')
<style>
    .scanner-wrap { display:flex; flex-direction:column; align-items:center; gap:24px; max-width:480px; margin:0 auto; }

    /* currently checked in card */
    .active-card {
        width:100%; background:var(--surface2);
        border:1px solid var(--accent-border);
        border-radius:24px; padding:24px 28px;
        display:flex; align-items:center; justify-content:space-between; gap:20px; flex-wrap:wrap;
        box-shadow:var(--shadow-sm),var(--inset);
    }
    .active-label { font-size:.65rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:var(--accent2); margin-bottom:8px; display:inline-block; background:var(--accent-bg); padding:4px 12px; border-radius:99px; }
    .active-space { font-family:'Plus Jakarta Sans',sans-serif;font-size:1.25rem; font-weight:800; color:var(--text); letter-spacing:-.3px; }
    .active-time  { font-size:.82rem; color:var(--text-soft); margin-top:4px; }
    .checkout-btn {
        padding:12px 24px;
        background:var(--danger-bg); border:1px solid var(--danger-border);
        border-radius:99px; color:var(--danger);
        font-family:'Plus Jakarta Sans',sans-serif; font-size:.9rem; font-weight:700;
        cursor:pointer; transition:all var(--t) var(--ease); white-space:nowrap;
    }
    .checkout-btn:hover { background:var(--danger); color:#fff; transform:translateY(-2px); box-shadow:0 4px 20px rgba(239,68,68,.3); }

    /* scanner box */
    .scanner-box {
        width:100%; background:var(--surface);
        border:1px solid var(--border);
        border-radius:32px; overflow:hidden;
        box-shadow:var(--shadow-md),var(--inset);
    }
    .scanner-header { padding:32px 32px 0; text-align:center; }
    .scanner-title { font-family:'Plus Jakarta Sans',sans-serif;font-size:1.4rem; font-weight:800; margin-bottom:6px; color:var(--text); letter-spacing:-.4px; }
    .scanner-sub   { font-size:.85rem; color:var(--text-soft); line-height:1.5; }

    /* camera viewport */
    .camera-area {
        position:relative; margin:24px;
        border-radius:20px; overflow:hidden;
        background:#000; aspect-ratio:1;
        box-shadow:var(--shadow-sm),var(--inset);
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
    .scan-frame::before  { top:0;    left:0;  border-width:3px 0 0 3px; border-radius:6px 0 0 0;}
    .scan-frame::after   { top:0;    right:0; border-width:3px 3px 0 0; border-radius:0 6px 0 0;}
    .scan-frame span::before { bottom:0; left:0;  border-width:0 0 3px 3px; border-radius:0 0 0 6px;}
    .scan-frame span::after  { bottom:0; right:0; border-width:0 3px 3px 0; border-radius:0 0 6px 0;}
    /* scan line animation */
    .scan-line {
        position:absolute; left:10%; right:10%; height:2px;
        background:linear-gradient(90deg,transparent,var(--accent2),transparent);
        animation:scanMove 2s ease-in-out infinite;
        box-shadow:0 0 12px var(--accent-glow);
    }
    @keyframes scanMove {
        0%  { top:10%; }
        50% { top:85%; }
        100%{ top:10%; }
    }

    .scanner-status {
        text-align:center; padding:12px 24px;
        font-family:'Plus Jakarta Sans',sans-serif;font-weight:700;
        font-size:.85rem; color:var(--text-soft);
        display:flex; align-items:center; justify-content:center; gap:10px;
    }
    .status-dot { width:10px; height:10px; border-radius:50%; background:var(--accent2); animation:pulse 1.5s infinite; box-shadow:0 0 10px var(--accent-glow); }
    @keyframes pulse{0%{opacity:1}50%{opacity:.4}100%{opacity:1}}
    
    .scanner-footer { padding:0 32px 32px; }
    .scanner-hint { font-size:.8rem; color:var(--text-muted); text-align:center; line-height:1.6; }

    /* permission denied state */
    .cam-denied {
        display:none; flex-direction:column; align-items:center;
        justify-content:center; padding:40px 20px; text-align:center;
        gap:16px; aspect-ratio:1; background:var(--surface2);
    }
    .cam-denied svg { width:48px; height:48px; opacity:.8; color:var(--danger); }
    .cam-denied p { font-size:.9rem; color:var(--text-soft); }
    .cam-retry { padding:12px 24px; background:linear-gradient(135deg,var(--accent),#6366f1); color:#fff; border:none; border-radius:99px; font-family:'Plus Jakarta Sans',sans-serif; font-size:.9rem; font-weight:700; cursor:pointer; box-shadow:0 4px 20px var(--accent-glow); transition:all var(--t) var(--ease); }
    .cam-retry:hover { transform:translateY(-2px);box-shadow:0 6px 28px var(--accent-glow); }
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