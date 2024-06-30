<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\JsonDataAccess;

$jsonDB = new JsonDataAccess('data.json');

// Create a new user
$newUser = $jsonDB->create('users', ['name' => 'sam', 'email' => 'newuser@example.com']);
print_r($newUser);

// Get all users/
$users = $jsonDB->getAll('users');
print_r($users);

// Get user by ID
$user = $jsonDB->getById('users', "658818a84aaaa");
//print_r($user);

// Update user
$newUser['id'] = "658818a84aaaa";
$updatedUser = $jsonDB->update('users', $newUser['id'], ['name' => 'Updated User']);
print_r($updatedUser);

// Delete user
$updatedUser['id'] ="658818a84aaaa";
$deletedUser = $jsonDB->delete('users', $updatedUser['id']);
print_r($deletedUser);

// Get seach for email
$user = $jsonDB->search('users','email',"newuser@example.com");
print_r($user);

// Search for items where either 'name' or 'email' matches 'sam'
$matches = $jsonDB->searchFields('users', ['name', 'email'], 'sam');
print_r($matches);

// Search for items where any field matches 'sam'
$matches = $jsonDB->searchAllFields('users', 'sam');
print_r($matches);

// Count the number of users
$userCount = $jsonDB->countRows('users');
echo "Number of users: ". $userCount;

// Search for users with ages between 20 and 30
$agesBetween = $jsonDB->searchBetween('users', 'age', 20, 30);
print_r($agesBetween);

// Calculate the total sum of 'amount' field for all items in 'transactions'
$totalAmount = $jsonDB->sum('transactions', 'amount');
echo "Total amount: ". $totalAmount;

// Calculate the average of 'amount' field for all items in 'transactions'
$averageAmount = $jsonDB->average('transactions', 'amount');
echo "Average amount: ". $averageAmount;

// Sort users by name in ascending order
$sortedUsers = $jsonDB->sort('users', 'name', 'asc');
print_r($sortedUsers);

// Or sort them in descending order
$sortedUsersDesc = $jsonDB->sort('users', 'name', 'desc');
print_r($sortedUsersDesc);

// Get the size of the JSON file
$fileSize = $jsonDB->getFileSize();
echo "File size: ". $fileSize;

// Export users to a CSV file
$data =$jsonDB->exportToCsv('users', 'users.csv');
print_r($data);

// Specify which fields to export
$fieldsToExport = ['name','email'];
// Export selected fields to a CSV file
$data = $jsonDB->exportSelectedFieldsToCsv('users', $fieldsToExport, 'selected_fields_users.csv');
print_r($data);

// Import data from a CSV file into the 'products' entity
$data = $jsonDB->importFromCsv('products', 'users.csv');
print_r($data);

// Search for items where any field contains the string 'sam'
$matches = $jsonDB->isLike('users', 'and');
print_r($matches);