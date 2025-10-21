# GST 3B Classifier - Complete Implementation Guide

## Overview
The `Gst3BClassifier` is a comprehensive support class that automatically classifies purchase invoices into the correct GSTR-3B Table 4 buckets for Input Tax Credit (ITC) reporting. This ensures accurate GST compliance and simplified return filing.

## Files Created

### 1. Core Classifier
- **File**: `app/Support/Gst3BClassifier.php`
- **Purpose**: Main classification logic and reporting methods

### 2. Artisan Commands
- **File**: `app/Console/Commands/UpdateItcBuckets.php`
- **Command**: `php artisan itc:update-buckets`
- **Purpose**: Update bucket classifications for existing invoices

- **File**: `app/Console/Commands/ShowGstr3BSummary.php`  
- **Command**: `php artisan gstr3b:summary`
- **Purpose**: Generate GSTR-3B Table 4 reports

### 3. Model Integration
- **File**: `app/Models/PurchaseInvoice.php` (Enhanced)
- **Method**: `getGst3BClassification()`
- **Purpose**: Easy access to classification from model instances

### 4. Controller Integration
- **File**: `app/Http/Controllers/PurchaseInvoiceController.php` (Updated)
- **Feature**: Automatic bucket classification on invoice creation

## GSTR-3B Table 4 Bucket Classification

### Eligible ITC Buckets (Table 4A)

#### 4A1 - Import of Goods
- **Criteria**: `invoice_type = 'import'` OR `vendor_type = 'import'`
- **Description**: ITC on goods imported directly
- **Examples**: Raw materials, machinery imported from foreign suppliers

#### 4A2 - Import of Services
- **Criteria**: `vendor_type = 'sez'` OR `invoice_type = 'sez'`
- **Description**: ITC on services imported or from SEZ
- **Examples**: Software services, consultancy from SEZ units

#### 4A3 - Inward Supplies Liable to RCM
- **Criteria**: `reverse_charge = true` (excluding imports)
- **Description**: ITC on supplies where recipient pays GST
- **Examples**: Legal services, GTA services, goods from unregistered dealers

#### 4A5 - All Other ITC
- **Criteria**: Default eligible bucket for regular B2B purchases
- **Description**: ITC on regular business purchases
- **Examples**: Office supplies, raw materials from registered vendors

### Ineligible ITC Buckets (Table 4D)

#### 4D1 - Sec 17(5) Blocked
- **Criteria**: `itc_eligibility = 'blocked'`
- **Description**: ITC blocked under Section 17(5)
- **Examples**: Motor vehicles, food/beverages, personal use items

#### 4D2 - Others Ineligible
- **Criteria**: `itc_eligibility = 'ineligible'`
- **Description**: Other ineligible ITC scenarios
- **Examples**: Time-barred ITC, exempt supplies, capital goods restrictions

## Core Features

### 1. Single Invoice Classification
```php
use App\Support\Gst3BClassifier;
use App\Models\PurchaseInvoice;

$invoice = PurchaseInvoice::find(1);
$classification = Gst3BClassifier::classify($invoice);

// Returns:
[
    'code' => '4A5',
    'label' => 'ITC Available — All other ITC',
    'eligible' => true,
    'amounts' => [
        'taxable_value' => 10000.00,
        'cgst' => 900.00,
        'sgst' => 900.00,
        'igst' => 0.00,
        'total_tax' => 1800.00
    ]
]
```

### 2. Bulk Classification
```php
$invoices = PurchaseInvoice::whereBetween('invoice_date', [$from, $to])->get();
$grouped = Gst3BClassifier::classifyBulk($invoices);

// Returns grouped data by bucket codes with totals
```

### 3. GSTR-3B Table 4 Summary
```php
$summary = Gst3BClassifier::generateTable4Summary($fromDate, $toDate);

// Returns complete Table 4 structure with all buckets and totals
```

### 4. Filtered Summaries
```php
// Eligible ITC only (4A sections)
$eligible = Gst3BClassifier::getEligibleItcSummary($fromDate, $toDate);

// Ineligible ITC only (4D sections)
$ineligible = Gst3BClassifier::getIneligibleItcSummary($fromDate, $toDate);
```

## Artisan Commands

### Update ITC Buckets
```bash
# Update all invoices
php artisan itc:update-buckets

# Update specific date range
php artisan itc:update-buckets --from=2025-01-01 --to=2025-01-31

# Dry run to see what would change
php artisan itc:update-buckets --dry-run

# Update specific month
php artisan itc:update-buckets --from=2025-01-01 --to=2025-01-31
```

**Features:**
- Progress bar for large datasets
- Change tracking and summary
- Dry-run mode for testing
- Date range filtering
- Distribution summary by bucket

### GSTR-3B Summary Report
```bash
# Current month summary
php artisan gstr3b:summary

# Specific month
php artisan gstr3b:summary --month=2025-01

# Custom date range
php artisan gstr3b:summary --from=2025-01-01 --to=2025-03-31

# Eligible ITC only
php artisan gstr3b:summary --eligible-only

# Ineligible ITC only  
php artisan gstr3b:summary --ineligible-only
```

**Output includes:**
- Bucket-wise breakdown with counts and amounts
- CGST, SGST, IGST split
- Total eligible vs ineligible ITC
- Bucket descriptions
- Period summary

## Model Integration

### Direct Classification
```php
$invoice = PurchaseInvoice::find(1);
$classification = $invoice->getGst3BClassification();
```

### Query Scopes
```php
// Filter by bucket code
$invoices = PurchaseInvoice::byItcBucket('4A5')->get();

// Filter by eligibility
$eligible = PurchaseInvoice::byItcEligibility('eligible')->get();

// Filter by vendor type
$imports = PurchaseInvoice::byVendorType('import')->get();
```

## Controller Integration

### Automatic Classification
When a new purchase invoice is created, the system automatically:
1. Validates all form data
2. Calculates tax amounts
3. Classifies into appropriate ITC bucket
4. Stores bucket code and label in database

```php
// In PurchaseInvoiceController::store()
$tempInvoice = new PurchaseInvoice($data);
$classification = Gst3BClassifier::classify($tempInvoice);
$data['itc_bucket_code'] = $classification['code'];
$data['itc_bucket_label'] = $classification['label'];
```

## Classification Logic Flow

### Decision Tree
1. **Check ITC Eligibility**
   - If 'blocked' → 4D1 (Sec 17(5))
   - If 'ineligible' → 4D2 (Others)

2. **For Eligible ITC**
   - If Import (`invoice_type` or `vendor_type` = 'import') → 4A1
   - If Reverse Charge (`reverse_charge` = true) → 4A3
   - Default → 4A5 (All other ITC)

### Validation Rules
- Import invoices require BOE details
- RCM invoices must have `reverse_charge = true`
- GSTIN required for registered vendors
- Date validation prevents future dates

## Database Integration

### Automatic Updates
- New invoices get bucket classification on creation
- Existing invoices can be updated via command
- Migration adds bucket fields to existing table

### Performance Optimization
- Indexed bucket codes for fast filtering
- Composite indexes for reporting queries
- Efficient bulk operations for large datasets

## Reporting Features

### GSTR-3B Ready Data
- Pre-calculated bucket totals
- Proper CGST/SGST/IGST breakdown
- Eligible vs ineligible segregation
- Month-wise ITC tracking

### Export Capabilities
- Structured data for GSTR-3B filing
- Detailed invoice lists per bucket
- Amount-wise summaries
- Period-based filtering

## Usage Examples

### Monthly GSTR-3B Preparation
```php
// Generate January 2025 summary
$summary = Gst3BClassifier::generateTable4Summary(
    Carbon::parse('2025-01-01'),
    Carbon::parse('2025-01-31')
);

foreach ($summary as $bucket) {
    echo "Bucket {$bucket['code']}: ₹{$bucket['totals']['total_tax']}\n";
}
```

### ITC Reconciliation
```php
// Find all eligible ITC for a period
$eligible = Gst3BClassifier::getEligibleItcSummary($from, $to);
$totalItc = collect($eligible)->sum('totals.total_tax');

echo "Total ITC Claimed: ₹" . number_format($totalItc, 2);
```

### Data Migration
```php
// Update classifications after logic changes
$updated = Gst3BClassifier::updateBucketClassification();
echo "Updated {$updated} invoices";
```

## Error Handling

### Validation
- Invalid dates are rejected
- Missing required fields trigger errors
- Inconsistent tax calculations are flagged

### Fallbacks
- Unknown scenarios default to 4A5
- Missing data handled gracefully
- Comprehensive error messages

## Future Enhancements

### Planned Features
- 4A2 (Import of Services) auto-detection
- 4A4 (ISD) support when available
- Advanced RCM detection rules
- Integration with GSTR-3B API

### Extensibility
- Easy addition of new bucket types
- Configurable classification rules
- Custom reporting formats
- API endpoints for external systems

## Testing

### Command Testing
```bash
# Test with dry run
php artisan itc:update-buckets --dry-run

# Check summary for current month
php artisan gstr3b:summary

# Verify specific scenarios
php artisan gstr3b:summary --month=2025-01 --eligible-only
```

### Data Validation
- Check bucket distribution
- Verify amount calculations
- Confirm date range filters
- Test edge cases

This comprehensive classifier ensures accurate GSTR-3B compliance while providing powerful reporting and analysis capabilities for GST practitioners.