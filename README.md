# 📊 Specifi Profit Optimiser (Backend API)

This is a **Laravel-based API backend** for the Specifi Profit Optimiser prototype. It powers:

- Quote calculations
- PDF/CSV report exports
- AI-powered quote suggestions using OpenAI GPT

The frontend is handled by a separate Nuxt/Vue application.

---

## 🚀 Features

### 💼 Quote API
- Accepts product line items, labor, overhead, target margin
- Calculates profit, margin, health indicator

### 🧠 AI Integration
- Uses OpenAI GPT (via API) to suggest:
  - Pricing or quantity changes
  - Product substitutions
  - Client-friendly summaries

### 📄 Report Export
- Exports quote summary as:
  - **PDF** (styled Blade view)
  - **CSV** (raw structured values)

### 🧾 Product Catalog
- Products seeded from real Bill of Materials (via seeder)
- Exposed via `/api/products`

---

## ⚙️ Setup Instructions

### 1. Clone the Repo

```bash
git clone https://github.com/ramsharan0230/profit-checker-back
cd profit-checker-back


### 2. Install Dependencies
composer install


### 3. Create .env File
cp .env.example .env

### 4. Generate App Key
php artisan key:generate

### 5. Run Migrations and Seed Data
php artisan migrate --seed

### 🧪 Run Tests
php artisan test

