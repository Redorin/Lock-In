@extends('student.layout')
@section('title','QR Scanner')
@section('page-title','QR Scanner')
@section('page-sub','Scan a space QR code to check in')

@section('styles')
<style>
    .scanner-wrap { display:flex; flex-direction:column; align-items:center; gap:24px; max-width:480px; margin:0 auto; }

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

    .scanner-box {
        width:100%; background:var(--surface);
        border:1px solid var(--border);
        border-radius:32px; overflow:hidden;
        box-shadow:var(--shadow-md),var(--inset);
    }
    .scanner-header { padding:32px 32px 0; text-align:center; }
    .scanner-title { font-family:'Plus Jakarta Sans',sans-serif;font-size:1.4rem; font-weight:800; margin-bottom:6px; color:var(--text); letter-spacing:-.4px; }
    .scanner-sub   { font-size:.85rem; color:var(--text-soft); line-height:1.5; }

    /* 
     * Camera viewport — uses a wrapper + absolute video so the video
     * always fills regardless of stream aspect ratio or orientation.
     */
    .camera-area {
        position:relative;
        margin:24px;
        border-radius:20px;
        overflow:hidden;
        background:#000;
        /* Explicit square via padding-top — works on all mobile browsers */
        width: calc(100% - 48px);
        height: 0;
        padding-bottom: calc(100% - 48px);
        box-shadow:var(--shadow-sm),var(--inset);
    }

    #qr-video {
        position:absolute;
        top:0; left:0;
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
        border-radius:20px;
    }

    #qr-canvas {
        display:none; /* only used for BarcodeDetector fallback decode, never shown */
    }

    .scan-overlay {
        position:absolute; inset:0;
        display:flex; align-items:center; justify-content:center;
        pointer-events:none; z-index:10;
    }
    .scan-frame { width:65%; height:65%; position:relative; }
    .scan-frame::before,.scan-frame::after,
    .scan-frame span::before,.scan-frame span::after {
        content:''; position:absolute; width:24px; height:24px;
        border-color:var(--accent2); border-style:solid;
    }
    .scan-frame::before  { top:0;    left:0;  border-width:3px 0 0 3px; border-radius:6px 0 0 0; }
    .scan-frame::after   { top:0;    right:0; border-width:3px 3px 0 0; border-radius:0 6px 0 0; }
    .scan-frame span::before { bottom:0; left:0; border-width:0 0 3px 3px; border-radius:0 0 0 6px; }
    .scan-frame span::after  { bottom:0; right:0;border-width:0 3px 3px 0; border-radius:0 0 6px 0; }
    .scan-line {
        position:absolute; left:10%; right:10%; height:2px;
        background:linear-gradient(90deg,transparent,var(--accent2),transparent);
        animation:scanMove 2s ease-in-out infinite;
        box-shadow:0 0 12px var(--accent-glow);
    }
    @keyframes scanMove { 0%{top:10%} 50%{top:85%} 100%{top:10%} }

    .cam-denied {
        display:none; flex-direction:column; align-items:center;
        justify-content:center; padding:40px 20px; text-align:center;
        gap:16px; background:var(--surface2);
        position:absolute; inset:0; z-index:20; border-radius:20px;
    }
    .cam-denied svg { width:48px; height:48px; opacity:.8; color:var(--danger); }
    .cam-denied p { font-size:.9rem; color:var(--text-soft); margin:0; }
    .cam-denied .cam-warn { font-size:.8rem; color:var(--text-muted); background:var(--surface); border:1px solid var(--border); border-radius:12px; padding:10px 14px; line-height:1.6; }
    .cam-retry { padding:12px 24px; background:linear-gradient(135deg,var(--accent),#6366f1); color:#fff; border:none; border-radius:99px; font-family:'Plus Jakarta Sans',sans-serif; font-size:.9rem; font-weight:700; cursor:pointer; box-shadow:0 4px 20px var(--accent-glow); transition:all var(--t) var(--ease); }
    .cam-retry:hover { transform:translateY(-2px); box-shadow:0 6px 28px var(--accent-glow); }

    .scanner-status {
        text-align:center; padding:12px 24px;
        font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
        font-size:.85rem; color:var(--text-soft);
        display:flex; align-items:center; justify-content:center; gap:10px;
    }
    .status-dot { width:10px; height:10px; border-radius:50%; background:var(--accent2); animation:pulse 1.5s infinite; box-shadow:0 0 10px var(--accent-glow); }
    @keyframes pulse { 0%{opacity:1} 50%{opacity:.4} 100%{opacity:1} }

    .scanner-footer { padding:0 32px 32px; }
    .scanner-hint { font-size:.8rem; color:var(--text-muted); text-align:center; line-height:1.6; }

    #cameraSelectWrapper { display:none; margin-top:16px; }
    #cameraSelect { width:100%; max-width:300px; margin:0 auto; display:block; }

    @media(max-width:640px) {
        .scanner-wrap { gap:16px; }
        .scanner-box { border-radius:24px; }
        .scanner-header { padding:22px 20px 0; }
        .scanner-title { font-size:1.2rem; }
        .camera-area { margin:16px; width:calc(100% - 32px); padding-bottom:calc(100% - 32px); }
        .scanner-footer { padding:0 20px 24px; }
        .active-card { flex-direction:column; gap:16px; border-radius:20px; padding:18px 20px; }
        .checkout-btn { width:100%; text-align:center; }
    }
</style>
@endsection

@section('content')
<div class="scanner-wrap">

    {{-- Scanner --}}
    <div class="scanner-box">
        <div class="scanner-header">
            <div class="scanner-title">Scan Space QR Code</div>
            <div class="scanner-sub">Point your camera at the QR code posted at the space entrance</div>
            <div id="cameraSelectWrapper">
                <select id="cameraSelect" class="fsel" onchange="switchCamera(this.value)"></select>
            </div>
        </div>

        <div class="camera-area" id="cameraArea">
            {{-- Raw video element — we control this directly, no library wrapper --}}
            <video id="qr-video" playsinline autoplay muted></video>
            <canvas id="qr-canvas"></canvas>

            <div class="scan-overlay">
                <div class="scan-frame"><span></span><div class="scan-line"></div></div>
            </div>

            <div class="cam-denied" id="camDenied">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
                <p id="camDeniedMsg">Camera access denied. Please allow camera permission to scan QR codes.</p>
                <div class="cam-warn" id="camDeniedHint" style="display:none"></div>
                <button class="cam-retry" onclick="initScanner()">Try Again</button>
            </div>
        </div>

        <div class="scanner-status">
            <span class="status-dot"></span>
            <span id="statusText">Loading scanner...</span>
        </div>

        <div class="scanner-footer">
            <p class="scanner-hint">QR codes change daily at midnight.<br>Ask an admin or staff to show you the current QR code.</p>
        </div>
    </div>

</div>
@endsection

@section('scripts')
{{-- ZXing for QR decoding — works everywhere, no camera management --}}
<script src="https://cdn.jsdelivr.net/npm/@zxing/library@0.21.3/umd/index.min.js"></script>

<script>
/* ─────────────────────────────────────────────────────────────
   Raw WebRTC + ZXing scanner
   — No html5-qrcode wrapper (that library manages the video
     element internally and breaks in portrait on Android)
   — We own the <video> element so styles always apply
   ───────────────────────────────────────────────────────────── */

const video          = document.getElementById('qr-video');
const canvas         = document.getElementById('qr-canvas');
const ctx            = canvas.getContext('2d');
const camDenied      = document.getElementById('camDenied');
const cameraSelect   = document.getElementById('cameraSelect');
const cameraWrapper  = document.getElementById('cameraSelectWrapper');

let currentStream    = null;
let decodeInterval   = null;
let codeReader       = null;
let navigating       = false;

function updateStatus(msg) {
    const el = document.getElementById('statusText');
    if (el) el.innerText = msg;
}

function showError(msg) {
    camDenied.style.display = 'flex';
    document.getElementById('camDeniedMsg').textContent = msg;
    updateStatus('Camera unavailable');
}

/* ── Stop everything ──────────────────────────────────────── */
function stopStream() {
    if (decodeInterval) { clearInterval(decodeInterval); decodeInterval = null; }
    if (currentStream)  { currentStream.getTracks().forEach(t => t.stop()); currentStream = null; }
    video.srcObject = null;
}

/* ── Start stream with a specific deviceId ────────────────── */
function startStream(deviceId) {
    stopStream();
    camDenied.style.display = 'none';
    updateStatus('Starting camera...');

    /*
     * CRITICAL: do NOT specify width/height here.
     * Mobile browsers in portrait will reject or silently black-out
     * a stream requested with landscape dimensions.
     * facingMode:'environment' is the most reliable way to get the back camera.
     */
    const constraints = {
        video: deviceId
            ? { deviceId: { exact: deviceId } }
            : { facingMode: { ideal: 'environment' } },
        audio: false
    };

    navigator.mediaDevices.getUserMedia(constraints)
        .then(stream => {
            currentStream  = stream;
            video.srcObject = stream;

            // playsInline + muted + autoplay are set in HTML but we also
            // call play() explicitly for Safari compatibility
            return video.play();
        })
        .then(() => {
            updateStatus('Ready — point at a QR code');
            startDecoding();
        })
        .catch(err => {
            console.error('[camera] getUserMedia failed:', err);
            showError(err.message || String(err));
        });
}

/* ── ZXing decode loop ────────────────────────────────────── */
function startDecoding() {
    // ZXing BrowserQRCodeReader — pure JS, no camera management
    if (!codeReader) {
        codeReader = new ZXing.BrowserQRCodeReader();
    }

    decodeInterval = setInterval(() => {
        if (navigating) return;
        if (!video.videoWidth || !video.videoHeight) return; // stream not ready yet

        // Draw current video frame onto hidden canvas then decode
        canvas.width  = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        codeReader.decodeFromCanvas(canvas)
            .then(result => {
                const text = result.getText();
                if (text && text.includes('/checkin/scan')) {
                    navigating = true;
                    stopStream();
                    updateStatus('✓ QR detected — checking in...');
                    try {
                        const qrUrl  = new URL(text);
                        window.location.href = window.location.origin + qrUrl.pathname + qrUrl.search;
                    } catch {
                        window.location.href = text;
                    }
                }
            })
            .catch(() => {}); // NotFoundException on every non-QR frame — ignore
    }, 250); // ~4 fps decode; video still renders at full fps
}

/* ── Populate camera dropdown ─────────────────────────────── */
function populateCameras(devices, selectedId) {
    cameraSelect.innerHTML = '';
    devices.forEach((d, i) => {
        const opt   = document.createElement('option');
        opt.value   = d.deviceId;
        opt.text    = d.label || `Camera ${i + 1}`;
        opt.selected = d.deviceId === selectedId;
        cameraSelect.appendChild(opt);
    });
    if (devices.length > 1) cameraWrapper.style.display = 'block';
}

window.switchCamera = function(deviceId) {
    startStream(deviceId);
};

/* ── Init ─────────────────────────────────────────────────── */
function initScanner() {
    camDenied.style.display = 'none';
    navigating = false;

    if (!navigator.mediaDevices?.getUserMedia) {
        showError('Camera requires a secure connection (HTTPS).');
        return;
    }

    updateStatus('Requesting camera permission...');

    // Step 1: get permission by opening any camera
    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
        .then(stream => {
            // Release immediately — we just needed to unlock deviceId labels
            stream.getTracks().forEach(t => t.stop());
            return navigator.mediaDevices.enumerateDevices();
        })
        .then(devices => {
            const cams = devices.filter(d => d.kind === 'videoinput');
            if (!cams.length) throw new Error('No cameras found.');

            // Pick back camera if available
            let chosen = cams[0].deviceId;
            for (const c of cams) {
                const lbl = (c.label || '').toLowerCase();
                if (lbl.includes('back') || lbl.includes('rear') || lbl.includes('environment')) {
                    chosen = c.deviceId;
                    break;
                }
            }

            populateCameras(cams, chosen);
            startStream(chosen);
        })
        .catch(err => {
            console.error('[init] error:', err);
            showError(err.message || String(err));
        });
}

initScanner();

window.addEventListener('beforeunload', stopStream);
</script>
@endsection