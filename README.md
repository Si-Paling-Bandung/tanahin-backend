# Tanahin

Tanahin is a platform for selling, buying, repaying, and auctioning land with superior profiling features to make it easier to make installments and research data to help the public know the price of land.

## Feature

A. Frontend
( Backend Only )

B. Backend
- Dashboard
- Management:
1. Installment
2. Marketplace
3. Auction
4. Thread
5. User

C. API
- Authentication
- Get All Data and Manipulation
- Profiling integrated with 3rd party (Brick)

## Tech stack
Programming Languages: PHP
Frameworks: Laravel 8
Database: MySQL
Front-End               : HTML/JavaScript/CSS, Bootstrap
Library                 : 
- laravel/ui, using for Login / Register / Forget Password template.
- laravel/sanctum, using for API token.
- yajra/laravel-datatables-oracle, using for dynamic tables in Dashbaord.
Other: GitHub, Visual Studio Code, Composer, NPM, Postman, Apache

## Installation
- Clone the repo and `cd` into it
- Run `composer install`
- Run `npm install`
- Rename or copy `.env.example` file to `.env`
- Run `php artisan key:generate`
- Set your database credentials in your `.env` file
- Run `php artisan migrate --seed`
- Run `php artisan storage:link`

Tutorial from M. Faiz Triputra : https://www.youtube.com/watch?v=Sv3GmVoedMQ&t=173s

## CPanel Deployment
Assume you deploy wherever on domain or subdomain in CPanel, so you will use default root that is automatically created by CPanel. You can follow my tutorial : 

https://medium.com/@m.faiztpaduhai/deploy-laravel-ke-shared-hosting-cpanel-dari-private-github-repository-854a4620c8aa

Just in case you deploy in the main domain, and you can't change the root you can change symlink,

https://medium.com/@m.faiztpaduhai/symbolic-links-to-public-html-9b637ec3b20c

After deployment there's few thing to prepare, specially change to production environment, just follow

https://stackoverflow.com/questions/59663762/laravel-what-steps-should-one-take-to-make-a-laravel-app-ready-for-production

## All links related to this project
Please contact me (@faiz_triputra at Instagram)
