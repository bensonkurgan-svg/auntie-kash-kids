# Auntie Kash Kids Academy — Laravel 12 Platform

A full-stack children's online learning platform built with **Laravel 12**, **PHP 8.3**, and **MySQL**.

## Features
- 5 role-based dashboards (CEO, Admin, Tutor, Parent, Student)
- 12 enrichment programs with modules & lessons
- 5-step discovery/program-matching form with email notifications
- CMS with CEO approval workflow (homepage + about editing)
- Stripe checkout + webhook for enrollments
- Blog, reading library & parent resources
- GDPR data export & deletion
- Resend transactional email
- Full responsive design system (Fredoka + Nunito)

## Demo Accounts (password: `DemoPass123`)
| Role | Email |
|------|-------|
| CEO | ceo@auntiekash.com |
| Admin | admin@auntiekash.com |
| Tutor | tutor1@auntiekash.com |
| Parent | parent1@example.com |

## Local Setup
```bash
composer install
cp .env.example .env
php artisan key:generate
# set DB credentials in .env
php artisan migrate --seed
php artisan serve
```

## Deployment
See **AuntieKashKids-Railway-Deployment-Guide.docx** for full step-by-step Railway hosting instructions.

## Tech Stack
- Laravel 12 / PHP 8.3
- MySQL 8
- Blade templating
- Stripe PHP SDK
- Resend PHP SDK
- Database-backed sessions, cache & queue
