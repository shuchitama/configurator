# Configurator

## Getting started

1. Fork this repository, then clone your fork of this repository

2. Copy the .env.example file in the root folder of the directory into a new .env file

3. Install dependencies using the following commands: 
- `composer install`
- `npm install`
- `npm run dev`

4. To access the files you upload into the app, create a symbolic link from `public/storage` to `storage/app/public` by running the following command: `php artisan storage:link`

5. Run `php artisan serve` to get a url for where your app is running. Go to the url in your browser to see the app in action.