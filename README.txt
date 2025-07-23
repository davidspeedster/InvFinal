InvestHub: Full PHP & MongoDB Platform

SETUP:
1. Install MongoDB, PHP 8+, and Composer.
2. In this folder, run: composer require mongodb/mongodb
3. Import demo data: mongo < seed.js
4. Copy your 'assets' (CSS, JS, images) into /assets/
5. Start your local server (XAMPP, WAMP, or php -S localhost:8000)
6. Register or login at /auth/register.php or /auth/login.php

ADMIN DASHBOARD (admin@investhub.et, no password in seed):
  - /admin/dashboard.php
  - /admin/manage-blog.php (CRUD blog)
  - /admin/manage-faq.php (CRUD FAQ)
  - /admin/manage-users.php
  - /admin/manage-investments.php
  - /admin/manage-messages.php

ENTREPRENEUR DASHBOARD:
  - /entrepreneur/dashboard.php
  - /entrepreneur/submit-proposal.php
  - /entrepreneur/view-submissions.php

INVESTOR DASHBOARD:
  - /investor/dashboard.php
  - /investor/browse-investments.php

PUBLIC:
  - /index.php
  - /about.php
  - /contact.php
  - /faq.php
  - /blog.php
  - /investment.php

**Drop in your /assets/ folder for all visuals to work!**