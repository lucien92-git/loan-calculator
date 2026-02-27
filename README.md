# Loan Calculator for Elementor

This repository contains a simple WordPress plugin that adds a **Loan Calculator** widget to Elementor.

## Features

- Elementor widget named **Loan Calculator**.
- Country selector with automatic currency formatting (USD, GBP, EUR, CAD, AUD, INR, JPY, MYR, SGD).
- Inputs for:
  - Loan amount
  - Annual interest rate
  - Term in years
- Outputs for:
  - Currency in use
  - Monthly payment
  - Total payment
  - Total interest
- Default values configurable in Elementor editor.
- Standalone UI preview page for quick front-end checks.

## Installation

1. Copy this folder into your WordPress `wp-content/plugins/` directory.
2. Activate **Loan Calculator for Elementor** in WordPress admin.
3. Open an Elementor page and search for **Loan Calculator** widget.
4. Drop it into the page and publish.

## Local UI Preview

You can preview and test the calculator UI without WordPress:

1. Start a static server in this repository (example):
   ```bash
   python3 -m http.server 8080
   ```
2. Open `http://localhost:8080/preview/` in your browser.
3. Change country or input values and confirm the payment summary updates.


## Troubleshooting

If styles look unchanged in WordPress/Elementor, clear cache and reload the page (the plugin now versions CSS/JS with file modified time to reduce stale caching).
