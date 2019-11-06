# kidkash
A simple app to keep track of gift cards and other money your kids have received. Authorized parents may enter transactions for adding money to and using money from various customized vendors. Each kid may login to check all their balances and see what they have available to them.

Project was built and maintained by [Scott Crowley](https://github.com/scottcrowley).

## Installation

#### Step 1

Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```bash
git clone git@github.com:scottcrowley/kidkash.git
cd kidkash && composer install && npm install
npm run dev
```

#### Step 2

Add all parent email addresses to the ***kidkash*** config file located in `/config/kidkash.php`. Each email address should be listed in the `parents` key of this file. See below for an example.
```php
return [
    'parents' => [
        'mom@example.com',
        'dad@example.com',
    ]
];
```
#### Step 3

Rename the `.env.example` file in the root of the site to `.env`.

#### Step 4

Create a database to use with the app. Provide all the necessary details about how to connect to the database in the .env file. The following keys should be updated with the appropriate details.
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kidkash
DB_USERNAME=my_root_account
DB_PASSWORD=my_root_password
```

#### Step 5

Migrate all the database tables by running the following command in your terminal
```bash
php artisan migrate
```

#### Step 6

Next, boot up a server and visit your ***kidkash*** app. If using a tool like Laravel Valet, of course the URL will default to http://kidkash.test. 

#### Step 7

Visit: http://kidkash.test/register to register a new parent user account. It is important to make sure you provide the same email address you listed in the ***kidkash*** config file above. If the email address provided during registration is not in the config file then a "kid" account is created and you won't be able to administer anything.

#### Step 8

Once you've created a parent account and have signed in, you can create accounts for your kid by clicking on the `Manage Kids` menu button or by visiting http://kidkash.test/kids. Photos can be uploaded for each kid, after they have been created by visiting their edit page.

You should also add some vendors (i.e. Amazon, Cash, etc.) by either clicking on the `Manage Vendors` menu button or by visiting http://kidkash.test/vendors.

Once you have added some kids and vendors, you can begin adding transactions by clicking the `Manage Transactions` menu button or by visiting http://kidkash.test/transactions.

Enjoy!!
