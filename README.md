# Confidence Club Members (CCM)

Professional membership and finance management system built with Laravel. It includes:
- Member management with admission fees
- Monthly dues tracking
- Contributions and special contributions
- Donations, income, expenses, and loans
- Receipt generation (PDF) for all payments
- Reports and transparency portal
- Role-based access (Admin, Treasurer, Viewer)

## Deploy on Render (Free Tier, PostgreSQL)

This repo includes a `Dockerfile` and `render.yaml` for one‑click deployment using a Render PostgreSQL database.

### Steps
1. Push this repo to GitHub.
2. In Render: **New → Blueprint** and connect your repo.
3. Render will create:
   - a web service (`ccm-app`)
   - a PostgreSQL database (`ccm-db`)
4. In the Render dashboard, set:
   - `APP_URL` to your real Render URL (example: `https://ccm-app.onrender.com`)
   - `APP_KEY` (generate locally using `php artisan key:generate --show`)
   - `RECEIPTS_DISK=s3`
   - `AWS_ACCESS_KEY_ID`
   - `AWS_SECRET_ACCESS_KEY`
   - `AWS_DEFAULT_REGION`
   - `AWS_BUCKET`
   - `AWS_URL` (optional)
   - `AWS_ENDPOINT` (optional for non-AWS providers)
5. Deploy. Migrations run automatically on start.

### After First Deploy
If you want default users, open the Render Shell and run:
```
php artisan db:seed
```

### Notes
- `APP_KEY` is generated automatically by Render.
- Receipts, storage symlink, and migrations are handled in `docker/entrypoint.sh`.
- Receipt PDFs are stored on S3 when `RECEIPTS_DISK=s3`.
- `SESSION_SECURE_COOKIE=true` is set to keep cookies HTTPS-only in production.
- HTTPS is forced automatically when `APP_URL` starts with `https://`.
- Render uses `/health` for the service health check.

## Local Setup (Optional)
```
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan serve
```
