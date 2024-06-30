# JsonDataAccess

JsonDataAccess is a PHP class designed to interact with JSON files as a database. It provides a simple and flexible interface for performing CRUD (Create, Read, Update, Delete) operations, as well as various other data manipulations and queries. This project aims to offer a lightweight solution for projects that require structured data storage without the overhead of a full-fledged database management system.

## Table of Contents
1. [Introduction](#introduction)
2. [Getting Started](#getting-started)
3. [Usage Guide](#usage-guide)
    - [Initialization](#initialization)
    - [Get All Users](#get-all-users)
    - [Get User by ID](#get-user-by-id)
    - [Create a New User](#create-a-new-user)
    - [Update User](#update-user)
    - [Delete User](#delete-user)
    - [Search for Email](#search-for-email)
    - [Search Fields](#search-fields)
    - [Search All Fields](#search-all-fields)
    - [Count Rows](#count-rows)
    - [Search Between](#search-between)
    - [Sum Field Values](#sum-field-values)
    - [Average Field Values](#average-field-values)
    - [Sort Data](#sort-data)
    - [Get File Size](#get-file-size)
    - [Export to CSV](#export-to-csv)
    - [Export Selected Fields to CSV](#export-selected-fields-to-csv)
    - [Import from CSV](#import-from-csv)
4. [Contributing](#contributing)
5. [License](#license)

## Introduction

JsonDataAccess is a simple and efficient way to manage data in JSON files using PHP. It allows developers to easily perform a variety of data operations without needing to set up a traditional database. Whether you are building a small application, a prototype, or handling configuration data, JsonDataAccess provides the necessary tools to work with JSON data effectively.

## Getting Started

To start using JsonDataAccess, follow these steps:

1. **Install Composer**: Ensure you have [Composer](https://getcomposer.org/) installed on your system.
2. **Install Dependencies**: Run the following command to install the necessary dependencies:
   ```sh
   composer require your-vendor/json-data-access
   ```
3. **Include Autoloader**: Add the Composer autoloader to your project:
   ```php
   require_once __DIR__ . '/vendor/autoload.php';
   ```

## Usage Guide

### Initialization

Initialize the `JsonDataAccess` class with the path to your JSON file:

```php
use App\JsonDataAccess;

$jsonDB = new JsonDataAccess('data.json');
```

### Get All Users

Retrieve all users from the JSON database:

```php
$users = $jsonDB->getAll('users');
print_r($users);
```

### Get User by ID

Retrieve a user by their unique ID:

```php
$user = $jsonDB->getById('users', "658818a84aaaa");
print_r($user);
```

### Create a New User

Create a new user with specific attributes:

```php
$newUser = $jsonDB->create('users', ['name' => 'sam', 'email' => 'newuser@example.com']);
print_r($newUser);
```

### Update User

Update an existing user by their ID:

```php
$newUser['id'] = "658818a84aaaa";
$updatedUser = $jsonDB->update('users', $newUser['id'], ['name' => 'Updated User']);
print_r($updatedUser);
```

### Delete User

Delete a user by their ID:

```php
$updatedUser['id'] = "658818a84aaaa";
$deletedUser = $jsonDB->delete('users', $updatedUser['id']);
print_r($deletedUser);
```

### Search for Email

Search for a user by their email address:

```php
$user = $jsonDB->search('users', 'email', "newuser@example.com");
print_r($user);
```

### Search Fields

Search for users where either 'name' or 'email' matches a given value:

```php
$matches = $jsonDB->searchFields('users', ['name', 'email'], 'sam');
print_r($matches);
```

### Search All Fields

Search for users where any field matches a given value:

```php
$matches = $jsonDB->searchAllFields('users', 'sam');
print_r($matches);
```

### Count Rows

Count the number of users in the database:

```php
$userCount = $jsonDB->countRows('users');
echo "Number of users: " . $userCount;
```

### Search Between

Search for users with ages between a specified range:

```php
$agesBetween = $jsonDB->searchBetween('users', 'age', 20, 30);
print_r($agesBetween);
```

### Sum Field Values

Calculate the total sum of a field for all items in an entity:

```php
$totalAmount = $jsonDB->sum('transactions', 'amount');
echo "Total amount: " . $totalAmount;
```

### Average Field Values

Calculate the average value of a field for all items in an entity:

```php
$averageAmount = $jsonDB->average('transactions', 'amount');
echo "Average amount: " . $averageAmount;
```

### Sort Data

Sort users by a specified field in ascending or descending order:

```php
$sortedUsers = $jsonDB->sort('users', 'name', 'asc');
print_r($sortedUsers);

$sortedUsersDesc = $jsonDB->sort('users', 'name', 'desc');
print_r($sortedUsersDesc);
```

### Get File Size

Get the size of the JSON file:

```php
$fileSize = $jsonDB->getFileSize();
echo "File size: " . $fileSize;
```

### Export to CSV

Export all users to a CSV file:

```php
$data = $jsonDB->exportToCsv('users', 'users.csv');
print_r($data);
```

### Export Selected Fields to CSV

Export selected fields of users to a CSV file:

```php
$fieldsToExport = ['name', 'email'];
$data = $jsonDB->exportSelectedFieldsToCsv('users', $fieldsToExport, 'selected_fields_users.csv');
print_r($data);
```

### Import from CSV

Import data from a CSV file into a specified entity:

```php
$data = $jsonDB->importFromCsv('products', 'users.csv');
print_r($data);
```

## Contributing

We welcome contributions to improve JsonDataAccess. To contribute, please follow these steps:

1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Make your changes and commit them with clear commit messages.
4. Push your changes to your fork.
5. Create a pull request to the main repository.

## License

JsonDataAccess is open-source software licensed under the MIT License. For more details, please refer to the [LICENSE](LICENSE) file.

```
MIT License

Copyright (c) 2024 iQuipe Digital Enterprises.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```
```