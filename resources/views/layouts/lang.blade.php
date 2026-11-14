<style>
/* GTranslate floating widget repositioning */
.gtranslate_wrapper {
    border-radius: 8px !important;
    padding: 4px 6px !important;
    border: 1px solid rgba(51, 65, 85, 0.6) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3) !important;
    max-width: 160px !important;
    background: rgba(15, 23, 42, 0.95) !important;
    backdrop-filter: blur(8px) !important;
    position: fixed !important;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* On mobile: move just above bottom nav */
@media (max-width: 767px) {
    .gtranslate_wrapper {
        bottom: 52px !important;
        left: 12px !important;
        z-index: 45 !important;
    }
}

/* On desktop: position normally */
@media (min-width: 768px) {
    .gtranslate_wrapper {
        bottom: 20px !important;
        left: 20px !important;
        z-index: 45 !important;
    }
}

.gtranslate_wrapper select {
    font-size: 10px !important;
    padding: 2px 4px !important;
    height: auto !important;
    background: transparent !important;
    color: #cbd5e1 !important;
    border: none !important;
}

.gtranslate_wrapper img {
    width: 14px !important;
    height: auto !important;
    margin-right: 4px !important;
}

.gtranslate_wrapper * {
    font-size: 11px !important;
    line-height: 1.3 !important;
}
</style>
<div class="gtranslate_wrapper"></div>
<script>
    window.gtranslateSettings = {
        default_language: "en",
        alt_flags:{"en":"usa"},
        wrapper_selector: ".gtranslate_wrapper",
        flag_style: "3d",
    };
</script>
<script src="https://cdn.gtranslate.net/widgets/latest/float.js" defer></script>

 