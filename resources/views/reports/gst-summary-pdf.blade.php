<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>GST Summary {{ $summary['period'] }}</title>
  <style>body{font-family:DejaVu Sans, sans-serif;font-size:12px}h2{margin-bottom:0}table{width:100%;border-collapse:collapse;margin-top:12px}th,td{border:1px solid #ddd;padding:8px;text-align:left}</style>
</head>
<body>
  <h2>GST Summary — {{ $summary['period'] }}</h2>
  <p><b>Sales Tax:</b> ₹{{ number_format($summary['sales_tax'],2) }} |
     <b>ITC:</b> ₹{{ number_format($summary['itc'],2) }} |
     <b>Payable:</b> ₹{{ number_format($summary['payable'],2) }}</p>

  <h3>Sales</h3>
  <table><tr><th>Taxable</th><th>CGST</th><th>SGST</th><th>IGST</th></tr>
    <tr>
      <td>₹{{ number_format($summary['buckets']['sales']['taxable'],2) }}</td>
      <td>₹{{ number_format($summary['buckets']['sales']['cgst'],2) }}</td>
      <td>₹{{ number_format($summary['buckets']['sales']['sgst'],2) }}</td>
      <td>₹{{ number_format($summary['buckets']['sales']['igst'],2) }}</td>
    </tr>
  </table>

  <h3>Purchase (ITC)</h3>
  <table><tr><th>Taxable</th><th>CGST</th><th>SGST</th><th>IGST</th></tr>
    <tr>
      <td>₹{{ number_format($summary['buckets']['purchase']['taxable'],2) }}</td>
      <td>₹{{ number_format($summary['buckets']['purchase']['cgst'],2) }}</td>
      <td>₹{{ number_format($summary['buckets']['purchase']['sgst'],2) }}</td>
      <td>₹{{ number_format($summary['buckets']['purchase']['igst'],2) }}</td>
    </tr>
  </table>
</body>
</html>
