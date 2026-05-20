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
    #reader {
        width:100%; height:100%; display:block;
    }
    #reader video {
        width:100% !important; height:100% !important;
        object-fit:cover !important; display:block !important;
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

    .cam-denied {
        display:none; flex-direction:column; align-items:center;
        justify-content:center; padding:40px 20px; text-align:center;
        gap:16px; aspect-ratio:1; background:var(--surface2);
    }
    .cam-denied svg { width:48px; height:48px; opacity:.8; color:var(--danger); }
    .cam-denied p { font-size:.9rem; color:var(--text-soft); }
    .cam-denied .cam-warn { font-size:.8rem; color:var(--text-muted); background:var(--surface); border:1px solid var(--border); border-radius:12px; padding:10px 14px; line-height:1.6; }
    .cam-retry { padding:12px 24px; background:linear-gradient(135deg,var(--accent),#6366f1); color:#fff; border:none; border-radius:99px; font-family:'Plus Jakarta Sans',sans-serif; font-size:.9rem; font-weight:700; cursor:pointer; box-shadow:0 4px 20px var(--accent-glow); transition:all var(--t) var(--ease); }
    .cam-retry:hover { transform:translateY(-2px);box-shadow:0 6px 28px var(--accent-glow); }

    @media(max-width:640px) {
        .scanner-wrap { gap:16px; }
        .scanner-box { border-radius:24px; }
        .scanner-header { padding:22px 20px 0; }
        .scanner-title { font-size:1.2rem; }
        .camera-area { margin:16px; }
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
            <div id="cameraSelectWrapper" style="display:none; margin-top:16px;">
                <select id="cameraSelect" class="fsel" style="width:100%; max-width:300px; margin:0 auto; display:block;" onchange="switchCamera(this.value)"></select>
            </div>
        </div>

        <div class="camera-area" id="cameraArea">
            <div id="reader"></div>
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
                <p id="camDeniedMsg">Camera access denied. Please allow camera permission to scan QR codes.</p>
                <div class="cam-warn" id="camDeniedHint" style="display:none"></div>
                <button class="cam-retry" onclick="startCamera()">Try Again</button>
            </div>
        </div>

        <div class="scanner-status" id="scannerStatus">
            <span class="status-dot"></span>
            <span id="statusText">Loading scanner interface...</span>
        </div>

        <div class="scanner-footer">
            <p class="scanner-hint">QR codes change daily at midnight.<br>Ask an admin or staff to show you the current QR code.</p>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/eruda"></script>
<script>eruda.init();</script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
const statusText = document.getElementById('statusText');
const camDenied  = document.getElementById('camDenied');
const readerElement = document.getElementById('reader');
const cameraSelect = document.getElementById('cameraSelect');
const cameraSelectWrapper = document.getElementById('cameraSelectWrapper');
let html5QrCode;
let availableCameras = [];

function populateCameraSelect(devices, selectedId) {
    cameraSelect.innerHTML = '';
    devices.forEach(device => {
        const option = document.createElement('option');
        option.value = device.deviceId;
        option.text = device.label || `Camera ${cameraSelect.length + 1}`;
        if (device.deviceId === selectedId) option.selected = true;
        cameraSelect.appendChild(option);
    });
    if (devices.length > 1) {
        cameraSelectWrapper.style.display = 'block';
    }
}

window.switchCamera = function(cameraId) {
    if (html5QrCode && html5QrCode.isScanning) {
        statusText.textContent = 'Switching camera...';
        html5QrCode.stop().then(() => {
            startScannerWithId(cameraId);
        }).catch(err => {
            console.error("Failed to stop scanner", err);
            startScannerWithId(cameraId);
        });
    } else {
        startScannerWithId(cameraId);
    }
};

function startScannerWithId(cameraId) {
    camDenied.style.display = 'none';
    readerElement.style.display = 'block';
    updateStatus('Initializing camera...');

    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode("reader");
    }

    // Pass video constraints safely through the config object rather than the first argument
    const config = { 
        fps: 10,
        videoConstraints: {
            deviceId: { exact: cameraId },
            width: { ideal: 640 },
            height: { ideal: 480 }
        }
    };

    console.log("Starting scanner with camera ID:", cameraId);

    // html5-qrcode requires exactly 1 key if passing an object, 
    // or we can just pass the string if we didn't want custom constraints.
    // However, since we want to prevent portrait crashes with custom constraints, 
    // we use a single-key object here, or just the string, and put the rest in videoConstraints.
    // Actually, if we pass string, html5-qrcode handles it well. Let's just pass the string
    // and rely on videoConstraints in the config.
    html5QrCode.start(
        cameraId,
        config,
        (decodedText) => {
            if (decodedText.includes('/checkin/scan')) {
                html5QrCode.pause();
                updateStatus('✓ QR detected — checking in...');
                try {
                    const qrUrl = new URL(decodedText);
                    const fixedUrl = window.location.origin + qrUrl.pathname + qrUrl.search;
                    window.location.href = fixedUrl;
                } catch {
                    window.location.href = decodedText;
                }
            }
        },
        (errorMessage) => {}
    ).then(() => {
        console.log("Scanner started successfully.");
        updateStatus('Ready — point at a QR code');
    }).catch((err) => {
        console.error("Scanner failed to start:", err);
        readerElement.style.display = 'none';
        camDenied.style.display = 'flex';
        updateStatus('Camera start failed');
        document.getElementById('camDeniedMsg').textContent = err.message || err;
    });
}

function updateStatus(msg) {
    const el = document.getElementById('statusText');
    if (el) el.innerText = msg;
}

function initScanner() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
        readerElement.style.display = 'none';
        camDenied.style.display = 'flex';
        updateStatus('Camera unavailable (HTTPS needed)');
        document.getElementById('camDeniedMsg').textContent = 'Camera requires a secure connection (HTTPS).';
        document.getElementById('camDeniedHint').style.display = 'block';
        return;
    }

    updateStatus('Requesting camera permissions...');

    // We must request permission first, otherwise enumerateDevices returns empty deviceIds.
    // We use safe landscape constraints to prevent the tablet camera driver from crashing in portrait mode.
    navigator.mediaDevices.getUserMedia({ video: { width: { ideal: 640 }, height: { ideal: 480 } } })
    .then(stream => {
        // Stop the stream immediately, we just needed it to trigger the permission prompt
        stream.getTracks().forEach(t => t.stop());
        return navigator.mediaDevices.enumerateDevices();
    })
    .then(devices => {
        const videoDevices = devices.filter(d => d.kind === 'videoinput');
        if (videoDevices.length > 0) {
            availableCameras = videoDevices;
            let cameraId = videoDevices[0].deviceId;
            for (let i = 0; i < videoDevices.length; i++) {
                if (videoDevices[i].label) {
                    const lbl = videoDevices[i].label.toLowerCase();
                    if (lbl.includes('back') || lbl.includes('rear') || lbl.includes('environment')) {
                        cameraId = videoDevices[i].deviceId;
                        break;
                    }
                }
            }
            populateCameraSelect(videoDevices, cameraId);
            
            if (!cameraId) {
                throw new Error("Permission granted but deviceId is still empty.");
            }
            startScannerWithId(cameraId);
        } else {
            throw new Error("No cameras found on this device.");
        }
    })
    .catch(err => {
        console.error("Failed to get cameras", err);
        updateStatus('Failed to get cameras: ' + (err.message || err));
    });
}

// Start on load
initScanner();

// Cleanup on unload
window.addEventListener('beforeunload', () => {
    if (html5QrCode && html5QrCode.isScanning) {
        html5QrCode.stop().catch(() => {});
    }
});
</script>
@endsection