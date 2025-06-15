<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions">
    <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

---

## 📊 Specifi Profit Optimiser — Senior Developer Assessment

This Laravel-based application is a prototype of a **Profitability Checker Tool** for AV dealers to evaluate their quotes, improve profit margins, and get intelligent suggestions via OpenAI.

### ✨ Features

- **Quote Input Form**
  - Add multiple products or services with quantity, cost, and sell price
  - Set labor hours, labor cost per hour, fixed overheads, and target margin

- **Profit Calculations**
  - Calculates gross profit and margin
  - Includes labor and overhead costs
  - Flags low-margin quotes
  - Highlights profitability status with color indicators (Green / Amber / Red)

- **🧠 AI Suggestions (OpenAI GPT Integration)**
  - Generates suggestions to:
    - Improve margins
    - Recommend pricing adjustments
    - Suggest product substitutions
    - Communicate profitability in client-friendly terms

- **📄 Export Capabilities**
  - Export quote summary to **PDF** and **CSV**
  - CSV includes full breakdown and calculated metrics
  - PDF uses a styled view for professional presentation

- **📦 Product Catalog Display**
  - Loads real-world product data from a Bill of Materials (BOM)
  - Includes SKU, MPN, Trade and Retail pricing

---

### 📁 Project Structure Highlights

- `app/Services/ExportReportService.php` – Handles PDF & CSV report generation
- `app/Services/OpenAIService.php` – Manages AI prompt generation and API interaction
- `resources/views/exports/summary.blade.php` – PDF template
- `tests/Feature/ExportReportControllerTest.php` – Integration tests for report generation
- `tests/Feature/ExportReportServiceTest.php` – Unit tests for report logic
- `components/QuoteForm.vue` – Form UI for quote input and export
- `components/QuoteSummary.vue` – Summary display with health indicator and AI output
- `components/Products.vue` – Product catalog listing component

---

### 🧪 Testing

Run all tests:

```bash
php artisan test
