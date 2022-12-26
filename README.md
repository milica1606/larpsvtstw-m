
# Laravel 9 + Vite + Svelte + Tailwind CSS

This starter template includes:

-   Laravel 9
-   Vite
-   Svelte
-   Tailwind CSS (w/ `@tailwindcss/forms` and `@tailwindcss/aspect-ratio`)
-   Axios
-   TypeScript

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<p align="center"><a href="https://vitejs.dev" target="_blank"><img src="https://vitejs.dev/logo-with-shadow.png" width="400"></a> <a href="https://svelte.dev" target="_blank"><img src="https://svelte.dev/images/twitter-thumbnail.jpg" width="400"></a> <a href="https://tailwindcss.com/" target="_blank"><img src="https://tailwindcss.com/_next/static/media/tailwindcss-logotype.ed60a6f85c663923c4d6ee9d85f359cd.svg" width="400"></a></p>

## Information

-   The app is located in [`resources/js/`](/resources/js/).
-   You can use [`resources/css/`](/resources/css/) for styling.

## Installation

Install the composer dependencies:

```bash
composer install
```

Make a copy of the `.env.example` file named `.env`:

```bash
cp .env.example .env
```

Generate an app key:

```bash
php artisan key:generate
```

Install all `package.json` dependencies:

```bash
npm install
```

Run vite to server your assets/bundle:

```bash
npm run dev
```

Open a new terminal instance and serve the application:

```bash
php artisan serve
```

## Deployment

Build the app:

```bash
npm run build
```

Now all you have to do is serve the Laravel app in a production environment.
