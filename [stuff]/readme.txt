git add .
git commit -m "changes"
git push origin main


ssh root@104.248.45.12
cd /var/www/fabsourcing
git pull origin main





php artisan db:seed --class=ServicesSeeder
php artisan db:seed --class=ProductCategorySeeder
php artisan db:seed --class=MethodStepsSeeder
php artisan db:seed --class=BlogPostSeeder