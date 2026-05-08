# web-manager

A lightweight, modern SaaS-style platform to audit and monitor WordPress sites.

## Installation Instructions

### 1. Database Setup
1. Create a MySQL database named `web_manager`.
2. Import the schema from `database/schema.sql`.
3. Configure your database credentials in `config/database.php`.

### 2. Web Server Configuration
1. Point your web server (MAMP, XAMPP, Nginx, or Apache) to the project folder.
2. The entry point is `public/index.php`.
3. **Default Admin Login:**
   - **Username:** `admin`
   - **Password:** `password` (Please change this immediately after first login).

### 3. Connecting WordPress Sites
To connect a WordPress site, you must install the **web-manager Connector** plugin:

1. Copy the entire folder `wordpress-plugin/web-manager-connector/`.
2. Paste it into your WordPress `wp-content/plugins/` directory.
3. Activate the plugin in the WordPress Admin.
4. Navigate to the new **web-manager** menu in your WordPress sidebar.
5. Copy the **Site URL** and the generated **API Key** (you can use the "Copy" buttons in the modern panel).
6. In the web-manager app, go to **Sites > Connect New Site** and paste the details.

## Features
- **Secure Connection:** Uses a custom API Key and custom endpoint (bypasses REST API restrictions).
- **Multi-User:** Role-based access control (Admin, Collaborator, Viewer).
- **Site Inventory:** Track pages, posts, drafts, themes, plugins, and images.
- **Performance Monitoring:** Tracks the estimated weight of the site (uploads, plugins, themes).
- **Printify-inspired UI:** Clean, modern, and responsive design.

## Technical Details
- **Stack:** PHP 7.4+, MySQL/MariaDB, Vanilla JS, Vanilla CSS.
- **Security:** CSRF protection, password hashing (bcrypt), and API Key validation.
- **No Dependencies:** No frameworks or heavy libraries required.
