(function() {
    var scanner = null;
    var roots = Array.prototype.slice.call(document.querySelectorAll('[data-scanner]'));

    function visibleRoot() {
        for (var i = 0; i < roots.length; i++) {
            if (roots[i].offsetParent !== null) return roots[i];
        }
        return roots[0] || null;
    }

    function Scanner(root) {
        this.root = root;
        this.video = root.querySelector('[data-qr-video]');
        this.canvas = root.querySelector('[data-qr-canvas]');
        this.ctx = this.canvas.getContext('2d', { willReadFrequently: true });
        this.status = root.querySelector('[data-status]');
        this.denied = root.querySelector('[data-cam-denied]');
        this.deniedMsg = root.querySelector('[data-cam-denied-msg]');
        this.retryButton = root.querySelector('[data-retry-camera]');
        this.inlineRetryButton = root.querySelector('[data-retry-inline]');
        this.fileInput = root.querySelector('[data-qr-file]');
        this.stream = null;
        this.frameId = null;
        this.decodeTimer = null;
        this.detector = null;
        this.zxingReader = null;
        this.zxingPromise = null;
        this.navigating = false;
        this.decoding = false;
        this.bind();
    }

    Scanner.prototype.bind = function() {
        var self = this;
        this.retryButton.addEventListener('click', function() { self.start(); });
        this.inlineRetryButton.addEventListener('click', function() { self.start(); });
        this.fileInput.addEventListener('change', function(event) { self.decodeUpload(event); });
    };

    Scanner.prototype.setStatus = function(message) {
        if (this.status) this.status.textContent = message;
        this.root.classList.toggle('is-ready', message.indexOf('Ready') === 0 || message.indexOf('QR found') === 0 || message.indexOf('Checking in') === 0);
    };

    Scanner.prototype.showError = function(message) {
        if (this.denied) this.denied.style.display = 'flex';
        if (this.deniedMsg) this.deniedMsg.textContent = message;
        this.setStatus('Camera unavailable');
    };

    Scanner.prototype.stop = function() {
        if (this.frameId) cancelAnimationFrame(this.frameId);
        if (this.decodeTimer) clearInterval(this.decodeTimer);
        this.frameId = null;
        this.decodeTimer = null;
        this.decoding = false;

        if (this.stream) {
            this.stream.getTracks().forEach(function(track) { track.stop(); });
            this.stream = null;
        }

        this.video.pause();
        this.video.removeAttribute('src');
        this.video.srcObject = null;
        this.video.load();
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    };

    Scanner.prototype.waitForVideo = function() {
        var self = this;
        if (this.video.readyState >= 2 && this.video.videoWidth && this.video.videoHeight) {
            return Promise.resolve();
        }

        return new Promise(function(resolve) {
            var done = false;
            var finish = function() {
                if (done) return;
                done = true;
                self.video.removeEventListener('loadedmetadata', finish);
                self.video.removeEventListener('canplay', finish);
                resolve();
            };

            self.video.addEventListener('loadedmetadata', finish, { once: true });
            self.video.addEventListener('canplay', finish, { once: true });
            setTimeout(finish, 1800);
        });
    };

    Scanner.prototype.constraints = function() {
        return {
            video: {
                facingMode: { ideal: 'environment' },
                width: { ideal: 1280 },
                height: { ideal: 720 }
            },
            audio: false
        };
    };

    Scanner.prototype.start = function() {
        var self = this;
        this.stop();
        this.navigating = false;
        if (this.denied) this.denied.style.display = 'none';

        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            this.showError('Camera requires HTTPS and a supported browser.');
            return;
        }

        this.setStatus('Starting camera...');

        navigator.mediaDevices.getUserMedia(this.constraints())
            .then(function(stream) {
                self.stream = stream;
                self.video.muted = true;
                self.video.autoplay = true;
                self.video.playsInline = true;
                self.video.setAttribute('muted', '');
                self.video.setAttribute('autoplay', '');
                self.video.setAttribute('playsinline', '');
                self.video.srcObject = stream;

                return self.waitForVideo()
                    .then(function() { return self.video.play(); })
                    .then(function() {
                        self.setStatus('Camera ready');
                        self.drawPreview();
                        self.startDecoder();
                    });
            })
            .catch(function(error) {
                console.error('[scanner] camera start failed', error);
                self.showError(error.message || String(error));
            });
    };

    Scanner.prototype.drawPreview = function() {
        var self = this;

        var draw = function() {
            if (!self.stream || self.video.readyState < 2) {
                self.frameId = requestAnimationFrame(draw);
                return;
            }

            if (self.video.videoWidth && self.video.videoHeight) {
                if (self.canvas.width !== self.video.videoWidth || self.canvas.height !== self.video.videoHeight) {
                    self.canvas.width = self.video.videoWidth;
                    self.canvas.height = self.video.videoHeight;
                }
                self.ctx.drawImage(self.video, 0, 0, self.canvas.width, self.canvas.height);
            }

            self.frameId = requestAnimationFrame(draw);
        };

        draw();
    };

    Scanner.prototype.startDecoder = function() {
        var self = this;
        if (this.decodeTimer) clearInterval(this.decodeTimer);

        this.setStatus('Ready - point at a QR code');

        this.decodeTimer = setInterval(function() {
            if (self.navigating || self.decoding || !self.canvas.width || !self.canvas.height) return;
            self.decoding = true;
            self.decodeFrame()
                .then(function(text) {
                    self.decoding = false;
                    if (text) self.handleResult(text);
                })
                .catch(function() {
                    self.decoding = false;
                });
        }, 300);
    };

    Scanner.prototype.decodeFrame = function() {
        if ('BarcodeDetector' in window) {
            if (!this.detector) {
                this.detector = new BarcodeDetector({ formats: ['qr_code'] });
            }

            return this.detector.detect(this.canvas).then(function(codes) {
                return codes && codes.length ? codes[0].rawValue : null;
            });
        }

        var self = this;
        return this.loadZxing().then(function() {
            if (!self.zxingReader) self.zxingReader = new window.ZXing.BrowserQRCodeReader();
            return self.zxingReader.decodeFromCanvas(self.canvas)
                .then(function(result) { return result ? result.getText() : null; })
                .catch(function() { return null; });
        });
    };

    Scanner.prototype.decodeImageSource = function(source) {
        if ('BarcodeDetector' in window) {
            if (!this.detector) {
                this.detector = new BarcodeDetector({ formats: ['qr_code'] });
            }

            return this.detector.detect(source).then(function(codes) {
                return codes && codes.length ? codes[0].rawValue : null;
            });
        }

        var self = this;
        return this.loadZxing().then(function() {
            if (!self.zxingReader) self.zxingReader = new window.ZXing.BrowserQRCodeReader();
            return self.zxingReader.decodeFromImageElement(source)
                .then(function(result) { return result ? result.getText() : null; })
                .catch(function() { return null; });
        });
    };

    Scanner.prototype.decodeUpload = function(event) {
        var self = this;
        var file = event.target.files && event.target.files[0];
        if (!file) return;

        this.setStatus('Reading QR image...');

        var imageUrl = URL.createObjectURL(file);
        var image = new Image();

        image.onload = function() {
            self.decodeImageSource(image)
                .then(function(text) {
                    URL.revokeObjectURL(imageUrl);
                    self.fileInput.value = '';

                    if (text) {
                        self.handleResult(text);
                        return;
                    }

                    self.setStatus('No QR code found in image');
                })
                .catch(function(error) {
                    URL.revokeObjectURL(imageUrl);
                    self.fileInput.value = '';
                    console.error('[scanner] image decode failed', error);
                    self.setStatus('Could not read QR image');
                });
        };

        image.onerror = function() {
            URL.revokeObjectURL(imageUrl);
            self.fileInput.value = '';
            self.setStatus('Could not load QR image');
        };

        image.src = imageUrl;
    };

    Scanner.prototype.loadZxing = function() {
        var self = this;
        if (window.ZXing) return Promise.resolve();
        if (this.zxingPromise) return this.zxingPromise;

        this.setStatus('Loading scanner engine...');
        this.zxingPromise = new Promise(function(resolve, reject) {
            var script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/@zxing/library@0.21.3/umd/index.min.js';
            script.async = true;
            script.onload = function() {
                self.setStatus('Ready - point at a QR code');
                resolve();
            };
            script.onerror = function() {
                reject(new Error('Could not load scanner engine. Refresh and try again.'));
            };
            document.head.appendChild(script);
        });

        return this.zxingPromise;
    };

    Scanner.prototype.handleResult = function(text) {
        if (!text || text.indexOf('/checkin/scan') === -1) return;

        this.navigating = true;
        this.stop();
        this.setStatus('QR found - checking in...');

        try {
            var qrUrl = new URL(text);
            window.location.href = window.location.origin + qrUrl.pathname + qrUrl.search;
        } catch (error) {
            window.location.href = text;
        }
    };

    window.initScanner = function() {
        var root = visibleRoot();
        if (!root) return;
        if (scanner) scanner.stop();
        scanner = new Scanner(root);
        scanner.start();
    };

    window.addEventListener('beforeunload', function() {
        if (scanner) scanner.stop();
    });

    window.addEventListener('orientationchange', function() {
        setTimeout(function() {
            if (scanner) scanner.start();
        }, 450);
    });

    window.initScanner();
})();
