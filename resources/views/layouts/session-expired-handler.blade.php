{{-- Global friendly handling when a form/session times out (replaces raw 419 errors) --}}
<script>
(function () {
    var sessionMsg = 'Your session timed out for security. Please refresh the page and try again.';

    function friendlySessionMessage(raw) {
        if (!raw) return sessionMsg;
        var text = String(raw);
        if (/419|page expired|csrf token mismatch/i.test(text)) {
            return sessionMsg;
        }
        return text;
    }

    function notifySessionExpired(msg) {
        var text = friendlySessionMessage(msg);
        if (typeof Swal !== 'undefined') {
            Swal.fire({ title: 'Session Expired', text: text, icon: 'warning', confirmButtonText: 'Refresh' })
                .then(function () { window.location.reload(); });
            return;
        }
        if (typeof swal === 'function') {
            swal('Session Expired', text, 'warning');
            return;
        }
        alert(text);
        window.location.reload();
    }

    if (typeof window.jQuery !== 'undefined') {
        jQuery(document).ajaxError(function (_event, xhr) {
            if (!xhr) return;
            var code = xhr.status;
            var bodyMsg = '';
            var bodyStatus = null;
            try {
                var json = xhr.responseJSON || (xhr.responseText ? JSON.parse(xhr.responseText) : null);
                if (json) {
                    if (typeof json.status !== 'undefined') bodyStatus = json.status;
                    bodyMsg = json.message || json.error || '';
                }
            } catch (e) {}

            if (code === 419 || bodyStatus === 419 || /419|page expired|csrf token mismatch/i.test(bodyMsg)) {
                notifySessionExpired(bodyMsg || sessionMsg);
            }
        });
    }
})();
</script>
