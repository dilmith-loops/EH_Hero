# 🚀 Hostinger Deployment Guide - Elephant House Wonder Hero

This guide walks you through deploying the Elephant House Wonder Hero platform to Hostinger under:
**`https://ai.loopsintegrated.co/EH-Hero`**

---

## 📌 Architecture Overview

- **Frontend**: Pre-built React Vite SPA in `dist/` (referenced with base `/EH-Hero/`)
- **Backend**: Laravel 11 in `backend/`
- **Routing**: Root `.htaccess` handles routing for Apache/LiteSpeed on Hostinger:
  - Frontend SPA & Assets: Served from `dist/`
  - Backend API (`/EH-Hero/api/*`): Routed to `backend/public/index.php`
  - IT Admin Portal (`/EH-Hero/EH-PORTAL-IT-ADMIN/*`): Routed to `backend/public/index.php`
  - Generated Image Storage (`/EH-Hero/storage/*`): Routed to `backend/public/storage/*`

---

## 🛠️ Step-by-Step Hostinger hPanel Setup

### Step 1: Create the Subdomain
1. Log in to **Hostinger hPanel**.
2. Go to **Websites** -> click **Manage** next to `loopsintegrated.co`.
3. In the left sidebar, search for **Subdomains**.
4. Enter:
   - **Subdomain name**: `ai` (full domain will be `ai.loopsintegrated.co`)
   - Check **Custom folder for subdomain** and set it to:
     `public_html/ai.loopsintegrated.co` (or your preferred document root)
5. Click **Create**.
6. Ensure SSL is enabled (hPanel automatically issues a free Let's Encrypt SSL certificate for subdomains).

---

### Step 2: Create the MySQL Database
1. In hPanel, go to **Databases** -> **Management**.
2. Under **Create a New MySQL Database and Database User**:
   - **MySQL Database name**: e.g., `eh_hero` (full name will look like `u123456789_eh_hero`)
   - **MySQL Username**: e.g., `eh_admin` (full user will look like `u123456789_eh_admin`)
   - **Password**: Enter a strong password (copy and save this)
3. Click **Create**.
4. Next to your newly created database, click **Enter phpMyAdmin**.
5. In phpMyAdmin:
   - Click the **Import** tab at the top.
   - Click **Choose File** and select:
     `backend/database/database_hostinger_export.sql`
   - Click **Go** / **Import** at the bottom.
   *(This instantly sets up all tables, the initial admin account, and default settings!)*

---

### Step 3: Deploy from GitHub via Hostinger Git
1. In hPanel, go to **Advanced** -> **Git**.
2. Fill in the deployment details:
   - **Repository URL**: `https://github.com/dilmith-loops/EH_Hero.git`
   - **Branch**: `main`
   - **Install directory**: `/EH-Hero` (inside `public_html/ai.loopsintegrated.co/EH-Hero`)
     *Note: If your subdomain root is `public_html/ai.loopsintegrated.co`, set the folder to `/EH-Hero` so the URL path matches `https://ai.loopsintegrated.co/EH-Hero`.*
3. Click **Create**.
4. Once created, click **Deploy** / **Pull** to pull the latest commit from GitHub.

---

### Step 4: Configure the Laravel Backend `.env`
1. In hPanel, go to **Files** -> **File Manager**.
2. Navigate to your deployed folder:
   `public_html/ai.loopsintegrated.co/EH-Hero/backend/`
3. Look for `.env.hostinger.example`.
4. Make a copy or rename it to `.env`.
5. Open `.env` and fill in your Hostinger MySQL database credentials:
   ```env
   APP_NAME="Elephant House Wonder Hero"
   APP_ENV=production
   APP_KEY=base64:Fz8XlXmsG97psCQb8XctDV4HpXbmTBFPKVX9BBzp/DI=
   APP_DEBUG=false
   APP_URL=https://ai.loopsintegrated.co/EH-Hero
   APP_TIMEZONE=Asia/Colombo

   DB_CONNECTION=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=u123456789_eh_hero
   DB_USERNAME=u123456789_eh_admin
   DB_PASSWORD=your_database_password_here

   SESSION_DRIVER=file
   FILESYSTEM_DISK=public
   CACHE_STORE=file
   QUEUE_CONNECTION=sync

   # Google Gemini API Key (Kept safe on server, never exposed in Git)
   GEMINI_API_KEY=your_gemini_api_key_here
   ```
6. Save the file.

---

### Step 5: Ensure Storage Directory & Permissions
1. In File Manager, ensure the following folders have write permissions (`755` or `775`):
   - `backend/storage/` (and all subfolders `app`, `framework`, `logs`)
   - `backend/bootstrap/cache/`
2. If `backend/public/storage` does not exist as a symlink:
   - In Hostinger hPanel **Advanced** -> **SSH Access**, connect via SSH and run:
     ```bash
     cd public_html/ai.loopsintegrated.co/EH-Hero/backend
     php artisan storage:link
     ```
   - *Alternative without SSH*: You can also create a folder named `storage` inside `backend/public/` if needed, or Laravel will route requests through the controller.

---

### Step 6: Verify Everything Works!

1. **Test Frontend Mobile App**:
   Visit:
   👉 **`https://ai.loopsintegrated.co/EH-Hero`**
   - Ensure the login screen loads with the branded background and treat options.
   - Enter your name to test camera/upload and generation.

2. **Test IT Admin Portal**:
   Visit:
   👉 **`https://ai.loopsintegrated.co/EH-Hero/EH-PORTAL-IT-ADMIN`**
   - **Email**: `admin@elephanthouse.lk`
   - **Password**: `password123`
   *(Once logged in, you can create new admins, edit existing admins, and change passwords from the "Admin Accounts" tab).*

3. **Test Maintenance Mode**:
   - In the Admin Portal Settings tab, switch Maintenance mode to **ON**.
   - Visit `https://ai.loopsintegrated.co/EH-Hero` in an incognito window -> You will see the branded Elephant House maintenance screen.
   - Switch Maintenance mode back to **OFF**.

---

## 🔄 Future Updates via GitHub
Whenever you push changes to `main` on GitHub:
1. Run `npm run build` locally if frontend changes were made.
2. Commit and push to GitHub.
3. In Hostinger hPanel -> **Advanced** -> **Git**, click **Pull** (or enable Auto-Deployment Webhook).
