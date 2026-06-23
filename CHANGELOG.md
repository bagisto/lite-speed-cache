# Changelog

All notable changes to **LiteSpeed Cache for Bagisto** are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-06-19

Initial release of the LiteSpeed Cache integration for Bagisto.

### Added

- **Full-page LiteSpeed caching** for the storefront: homepage, CMS pages, product & category pages, contact page, search results, and the category/product listing API endpoints.
- **Single tag-per-route cache tagging** with event-driven purging — product, category and CMS changes purge only the affected entries, and the home/header caches refresh on relevant catalog changes instead of flushing everything.
- **Customer-group cache vary** — public pages are cached per customer group (guest / general / wholesale) via the unencrypted `lsc_customer_group` cookie, and also vary by locale (`bagisto_locale`) and currency (`bagisto_currency`).
- **Private, per-user caching** for cart, compare and wishlist, isolated per customer/guest via the `lsc_private` cookie.
- **ESI (Edge Side Include) support** for the login dropdown and cart-count fragments, assembled per-user at the edge on LiteSpeed Web Server Enterprise, with an AJAX/Vue hole-punching fallback on OpenLiteSpeed.
- **Vary cookie synchronization on login/logout**, so the correct public (customer-group) page and private fragments are served immediately after an authentication change.
- **Admin debug mode and manual cache purge** from the admin panel.
- **Sensitive-route protection** — account and checkout routes are never cached.

### Requirements

- [Bagisto v2.3.6](https://github.com/bagisto/bagisto/tree/v2.3.6)
- LiteSpeed Web Server (or OpenLiteSpeed) with LSCache enabled.

### Setup notes

- The `Cache-Vary` directive (`lsc_customer_group,bagisto_locale,bagisto_currency`) must be registered in the LiteSpeed configuration (`server`, `vhost`, or `.htaccess`) for the customer-group / locale / currency vary to take effect. See the README for details.
