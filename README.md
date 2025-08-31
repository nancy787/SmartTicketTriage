# Smart Ticket Triage & Dashboard
A Laravel SPA for help-desk ticket submission, AI classification, and analytics.
## Setup Steps
1. **Clone the repo**
   ```bash
   git clone <repo-url>
   cd SmartTicketTriage

2. **install php Dependencies**
    composer install

3. **Copy environment file**
  cp .env.example .env

4. **genereta environment key**
php artisan key:generate

5. **add and update database**
 add 
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

6. **run migration and seeders**
php artisan migrate
php artisan db:seed

7. **Build frontend**
npm run dev      # for local dev
npm run build    # for production

8. **Start queue worker**
php artisan queue:work

9. **Start queue worker**
php artisan serve
