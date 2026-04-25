<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($tutorial['judul']) ?> - Advanced Project</title>
<!-- Favicon -->
<link href="<?= base_url() ?>NiceAdmin/assets/img/favicon.png" rel="icon">

<!-- Bootstrap (pakai yang sudah di-bundle di NiceAdmin) -->
<link href="<?= base_url() ?>NiceAdmin/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() ?>NiceAdmin/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

<!-- Prism.js untuk syntax highlighting -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/themes/prism-tomorrow.min.css">

    <style>
        /* Notion-like Core Typography and Colors */
        :root {
            --notion-text: #37352f;
            --notion-text-light: #9b9a97;
            --notion-bg: #ffffff;
            --notion-bg-hover: #efeff1;
            --notion-border: #e9e9e7;
            --notion-font: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, "Apple Color Emoji", Arial, sans-serif, "Segoe UI Emoji", "Segoe UI Symbol";
            --notion-code: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
        }

        body {
            background: var(--notion-bg);
            font-family: var(--notion-font);
            color: var(--notion-text);
            line-height: 1.6;
        }
        
        .presentation-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 3rem 4rem; /* More generous padding like Notion */
        }

        @media (max-width: 768px) {
            .presentation-wrapper {
                padding: 2rem 1.5rem;
            }
        }

        /* Minimalist Sticky Header */
        .presentation-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            padding: 1.5rem 0 2.5rem 0;
            margin-bottom: 2rem;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .presentation-header h2 {
            font-size: 2.5rem; /* Large typical notion title */
            font-weight: 700;
            letter-spacing: -0.03em;
            margin-bottom: 0.5rem;
            color: var(--notion-text);
        }

        .presentation-header .text-muted {
            font-size: 1rem;
            color: var(--notion-text-light) !important;
        }

        /* Flat detail steps */
        .detail-step {
            padding: 0.5rem 0;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        .detail-step.new-item {
            animation: fadeIn 0.4s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Step Number */
        .step-number {
            min-width: 1.6rem;
            height: 1.6rem;
            border-radius: 50%;
            background: var(--notion-bg-hover);
            color: var(--notion-text-light);
            font-size: 0.78rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            margin-top: 0.15rem;
            flex-shrink: 0;
            font-family: var(--notion-code);
        }

        /* Content Blocks */
        .detail-step.text-type p {
            font-size: 1.1rem;
            line-height: 1.65;
            margin-bottom: 0;
            white-space: normal; /* changed from pre-wrap because TinyMCE generates explicit block elements */
        }
        
        /* Notion-like Rich Text Styling */
        .rich-text-content h1, .rich-text-content h2, .rich-text-content h3 {
            font-weight: 700;
            color: var(--notion-text);
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .rich-text-content h1 { font-size: 1.8rem; }
        .rich-text-content h2 { font-size: 1.5rem; }
        .rich-text-content h3 { font-size: 1.25rem; }
        .rich-text-content p { margin-bottom: 0.8rem; }
        .rich-text-content ul, .rich-text-content ol { padding-left: 1.5rem; margin-bottom: 1rem; }
        .rich-text-content li { margin-bottom: 0.4rem; font-size: 1.1rem;}
        .rich-text-content strong { font-weight: 600; }
        .rich-text-content em { font-style: italic; }
        
        .detail-step.image-type img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
            cursor: zoom-in;
            border: 1px solid var(--notion-border);
            margin: 0.5rem 0;
        }

        .detail-step.code-type pre {
            margin: 0.5rem 0 0 0;
            border-radius: 6px;
            font-family: var(--notion-code);
            border: 1px solid var(--notion-border);
            padding: 1rem;
        }
        
        .detail-step.code-type pre code {
            font-family: var(--notion-code);
            text-shadow: none !important;
        }

        /* Bookmark-like URL Block */
        .detail-step.url-type a {
            display: flex;
            align-items: center;
            font-size: 1rem;
            color: var(--notion-text);
            text-decoration: none;
            border: 1px solid var(--notion-border);
            padding: 0.8rem 1rem;
            border-radius: 4px;
            background: #fff;
            transition: background 0.2s;
            margin-top: 0.5rem;
            word-break: break-all;
        }

        .detail-step.url-type a:hover {
            background: var(--notion-bg-hover);
        }

        .detail-step.url-type a i {
            margin-right: 10px;
            color: var(--notion-text-light);
            font-size: 1.2rem;
        }

        /* Live Indicator -> Last Edit */
        .live-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            padding: 0;
            background: transparent;
            color: var(--notion-text-light);
            font-style: italic;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--notion-bg);
            border-radius: 4px;
            color: var(--notion-text-light);
            border: 1px dashed var(--notion-border);
        }
        .empty-state h5 {
            color: var(--notion-text);
            font-weight: 600;
        }

        /* Lightbox sederhana */
        .lightbox-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            cursor: zoom-out;
        }
        .lightbox-overlay.active {
            display: flex;
        }
        .lightbox-overlay img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="presentation-wrapper">
<!-- Header -->
<div class="presentation-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
        <div>
            <h2 class="mb-1"><?= esc($tutorial['judul']) ?></h2>
            <small class="text-muted">
                <?= esc($tutorial['kode_matkul']) ?> — <?= esc($tutorial['nama_matkul']) ?>
            </small>
        </div>
        <div>
            <span class="live-indicator connected" id="liveIndicator">
                <span>Last edit: -</span>
            </span>
        </div>
    </div>
</div>

<!-- Details Container (akan di-render via JS) -->
<div id="detailsContainer">
    <!-- Initial render server-side (untuk SEO & fast first paint) -->
    <?php if (empty($details)) { ?>
        <div class="empty-state">
            <i class="bi bi-hourglass-split" style="font-size: 3rem; opacity: 0.3;"></i>
            <h5 class="mt-3">Belum ada langkah tutorial yang ditampilkan</h5>
            <p class="mb-0">Halaman akan otomatis memperbarui saat creator menampilkan langkah.</p>
        </div>
    <?php } else { ?>
        <?php foreach ($details as $i => $d) { ?>
            <div class="detail-step <?= $d['type'] ?>-type" data-detail-id="<?= $d['id'] ?>">
                <div class="d-flex align-items-start">
                    <div class="step-number"><?= $i + 1 ?></div>
                    <div class="flex-grow-1">
                        <?php if ($d['type'] == 'text') { ?>
                            <div class="rich-text-content notion-text-block"><?= $d['text'] ?></div>
                        <?php } elseif ($d['type'] == 'image' && $d['gambar']) { ?>
                            <img src="<?= base_url('uploads/' . $d['gambar']) ?>" 
                                 alt="Step <?= $d['order'] ?>" 
                                 class="zoomable">
                        <?php } elseif ($d['type'] == 'code') { ?>
                            <pre><code class="language-<?= esc($d['code_language'] ?? 'plaintext') ?>"><?= esc($d['code']) ?></code></pre>
                        <?php } elseif ($d['type'] == 'url') { ?>
                            <a href="<?= esc($d['url']) ?>" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-link-45deg"></i> <?= esc($d['url']) ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>
</div>
<!-- Lightbox -->
<div class="lightbox-overlay" id="lightbox">
    <img src="" alt="">
</div>
<!-- Prism.js -->
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/prism.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-markup.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-markup-templating.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-php.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-javascript.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-markup.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-css.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-python.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-sql.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-json.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/prismjs@1.29.0/components/prism-bash.min.js"></script>
<script>
(function() {
    'use strict';
    
    const SLUG = <?= json_encode($slug) ?>;
    const DATA_URL = <?= json_encode(base_url('presentation/' . $slug . '/data')) ?>;
    const UPLOADS_URL = <?= json_encode(base_url('uploads/')) ?>;
    const POLL_INTERVAL = 3000; //update setiap 3 detik
    
    const container = document.getElementById('detailsContainer');
    const liveIndicator = document.getElementById('liveIndicator');
    
    let currentDetailsStr = "[]";
    let pollFailCount = 0;
    
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function nl2br(text) {
        return escapeHtml(text).replace(/\n/g, '<br>');
    }
    
    function renderDetail(d, isNew, index) {
        let inner = '';
        if (d.type === 'text') {
            inner = `<div class="rich-text-content notion-text-block">${d.text || ''}</div>`;
        } else if (d.type === 'image' && d.gambar) {
            inner = `<img src="${UPLOADS_URL}${encodeURIComponent(d.gambar)}" alt="Step ${d.order}" class="zoomable">`;
        } else if (d.type === 'code') {
            const lang = d.code_language || 'plaintext';
            inner = `<pre><code class="language-${escapeHtml(lang)}">${escapeHtml(d.code || '')}</code></pre>`;
        } else if (d.type === 'url') {
            inner = `<a href="${escapeHtml(d.url || '#')}" target="_blank" rel="noopener noreferrer">
                        <i class="bi bi-link-45deg"></i> ${escapeHtml(d.url || '')}
                     </a>`;
        }
        
        const newClass = isNew ? ' new-item' : '';
        return `<div class="detail-step ${d.type}-type${newClass}" data-detail-id="${d.id}">
                    <div class="d-flex align-items-start">
                        <div class="step-number">${index + 1}</div>
                        <div class="flex-grow-1">${inner}</div>
                    </div>
                </div>`;
    }

    function renderDetails(details, previousIds) {
        if (!details || details.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-hourglass-split" style="font-size: 3rem; opacity: 0.3;"></i>
                    <h5 class="mt-3">Belum ada langkah tutorial yang ditampilkan</h5>
                    <p class="mb-0">Halaman akan otomatis memperbarui saat creator menampilkan langkah.</p>
                </div>`;
            return;
        }
        
        container.innerHTML = details.map((d, i) => {
            const isNew = previousIds && !previousIds.has(d.id);
            return renderDetail(d, isNew, i);
        }).join('');
        
        if (typeof Prism !== 'undefined') {
            Prism.highlightAll();
        }
        initLightbox();
    }

    let lastEditTimeString = '';

    function setIndicator(status, isNewUpdate = false) {
        if (status === 'connected') {
            liveIndicator.className = 'live-indicator connected';
            if (isNewUpdate || !lastEditTimeString) {
                const now = new Date();
                lastEditTimeString = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            }
            liveIndicator.innerHTML = `<span>Last edit: ${lastEditTimeString}</span>`;
        } else if (status === 'error') {
            liveIndicator.className = 'live-indicator error';
            liveIndicator.innerHTML = '<span>Connection Error</span>';
        }
    }

    async function poll() {
        try {
            // Append timestamp to utterly defeat GET caches
            const cacheBuster = DATA_URL + (DATA_URL.includes('?') ? '&' : '?') + '_t=' + new Date().getTime();
            
            const response = await fetch(cacheBuster, {
                method: 'GET',
                cache: 'no-store',
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
            });

            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            
            const data = await response.json(); // tambah timestamp untuk mencegah cache browser
            pollFailCount = 0;
            
            if (data.status === 'success' && data.details) {
                const newDetailsStr = JSON.stringify(data.details);
                // JUpdate DOM jika data benar benar berubah
                if (newDetailsStr !== currentDetailsStr) {
                    const previousIds = new Set(Array.from(container.querySelectorAll('.detail-step')).map(el => parseInt(el.dataset.detailId))); //simpan ID lama
                    renderDetails(data.details, previousIds);  // render baru 
                    currentDetailsStr = newDetailsStr;  //update baseline
                    setIndicator('connected', true); // set time ke current
                } else {
                    setIndicator('connected', false);
                }
            }
        } catch (err) {
            pollFailCount++;
            console.warn('AJAX Poll error:', err);
            if (pollFailCount >= 3) setIndicator('error');
        }
    }

    function initLightbox() {
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = lightbox.querySelector('img');
        container.querySelectorAll('img.zoomable').forEach(img => {
            img.addEventListener('click', () => {
                lightboxImg.src = img.src;
                lightbox.classList.add('active');
            });
        });
        lightbox.onclick = () => lightbox.classList.remove('active');
    }

    // Set first baseline by sending a manual poll right away
    poll();   //langsung poll saat pertama load
    setInterval(poll, POLL_INTERVAL);  //ulangi poll setiap interval

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') poll();
    });
})();
</script>
</body>
</html>
