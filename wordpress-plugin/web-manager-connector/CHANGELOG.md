## 1.1.8

- Fixes update packages for legacy WordPress installs registered as `web-manager/web-manager.php`.
- Keeps the plugin file path stable during normal WordPress updates.

## 1.1.7

- Adds authenticated remote update checks against the web-manager central app.
- Registers updates in the normal WordPress Plugins update screen.
- Reports connector version and platform URL in the site stats endpoint.
- Keeps update checks safe when the central update server is unavailable.
