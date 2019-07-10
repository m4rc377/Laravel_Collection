
# Mini CRM
This is a repository of the crm system with laravel. Try to follow the tutorial series carefully on [Webmobtuts.com](http://webmobtuts.com)

## Getting started

First run this command in terminal to install composer dependencies
```
composer install
```

## Database & Email settings
Change your database and email settings in .env with with appropriate settings
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=homestead
DB_USERNAME=homestead
DB_PASSWORD=secret


MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=username
MAIL_PASSWORD=password
MAIL_ENCRYPTION=null
```


## Run migrations

Run this command to migrate
```
php artisan migrate
```

## Database seeding
```
php artisan db:seed
```

## Mailbox Folder seeding
```
php artisan db:seed --class=MailboxFolderSeeder
```

## Uploads Directory
```
mkdir public/uploads/
chmod -R 777 public/uploads/
```

