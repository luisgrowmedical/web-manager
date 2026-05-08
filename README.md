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
To connect a WordPress site, install the canonical **web-manager** WordPress plugin:

1. Upload `wordpress-plugin/web-manager-connector/web-manager-connector.zip` from **Plugins > Add New > Upload Plugin**.
2. Activate the plugin in the WordPress Admin. WordPress should activate `web-manager-connector/web-manager-connector.php`.
3. The plugin name and admin menu remain **web-manager**.
4. If WordPress still says "The plugin file does not exist", copy `wordpress-plugin/fix-web-manager-active-plugin.php` to the WordPress root, visit it while logged in as an administrator, delete it, then install the ZIP again.
5. Navigate to the new **web-manager** menu in your WordPress sidebar.
6. Copy the **Site URL** and the generated **API Key** (you can use the "Copy" buttons in the modern panel).
7. In the web-manager app, go to **Sites > Connect New Site** and paste the details.

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
