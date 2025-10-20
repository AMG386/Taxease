<script>
(function () {
  async function processUpload() {
    const alertBox = document.getElementById('invUploadAlert');
    const fileEl   = document.getElementById('invJsonFile');
    const textEl   = document.getElementById('invJsonText');

    function showAlert(msg, type) {
      alertBox.className = `alert alert-${type}`;
      alertBox.textContent = msg;
      alertBox.classList.remove('d-none');
    }

    let invoices = null;
    try {
      if (fileEl && fileEl.files && fileEl.files[0]) {
        const txt = await fileEl.files[0].text();
        invoices = JSON.parse(txt);
      } else if (textEl && textEl.value.trim() !== '') {
        invoices = JSON.parse(textEl.value.trim());
      }
    } catch (e) {
      return showAlert('Invalid JSON. Please check and try again.', 'danger');
    }

    if (!Array.isArray(invoices)) {
      return showAlert('Please provide an array of invoices JSON.', 'danger');
    }

    try {
      const res = await fetch('{{ route("invoices.import.json") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ invoices })
      });

      const out = await res.json();
      if (!res.ok || out.ok === false) {
        return showAlert('Validation failed. Check required fields (type, invoice_no, date, amounts).', 'danger');
      }

      showAlert(`${out.message}. Count: ${out.count}. GST Payable: ₹${out.gst_payable}`, 'success');
      // Let pages update their widgets
      window.dispatchEvent(new CustomEvent('taxease:invoices:uploaded', { detail: out }));

    } catch (err) {
      showAlert('Upload failed. Please try again.', 'danger');
      console.error(err);
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('btnInvUpload');
    if (btn) btn.addEventListener('click', processUpload);
  });

  // Optional: dashboard can call this to reload widgets
  window.taxease = window.taxease || {};
  window.taxease.refreshDashboard = async function () {
    try { if (typeof loadMetrics === 'function') await loadMetrics(); } catch(e){}
    try { if (typeof loadChart   === 'function') await loadChart();   } catch(e){}
    try { if (typeof loadRecentInvoices === 'function') await loadRecentInvoices(); } catch(e){}
  };
})();
</script>
