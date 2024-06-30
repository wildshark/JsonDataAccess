# JsonDataAccess Coding Guide

This guide will help you understand how to use the JsonDataAccess class to interact with JSON files as a database in PHP. It covers initialization, CRUD operations, and various other data manipulation methods.

## Table of Contents
1. [Initialization](#initialization)
2. [CRUD Operations](#crud-operations)
    - [Get All Users](#get-all-users)
    - [Get User by ID](#get-user-by-id)
    - [Create a New User](#create-a-new-user)
    - [Update User](#update-user)
    - [Delete User](#delete-user)
3. [Advanced Queries](#advanced-queries)
    - [Search for Email](#search-for-email)
    - [Search Fields](#search-fields)
    - [Search All Fields](#search-all-fields)
    - [Count Rows](#count-rows)
    - [Search Between](#search-between)
4. [Data Aggregation](#data-aggregation)
    - [Sum Field Values](#sum-field-values)
    - [Average Field Values](#average-field-values)
5. [Sorting](#sorting)
    - [Sort Data](#sort-data)
6. [File Operations](#file-operations)
    - [Get File Size](#get-file-size)
    - [Export to CSV](#export-to-csv)
    - [Export Selected Fields to CSV](#export-selected-fields-to-csv)
    - [Import from CSV](#import-from-csv)

## Initialization

To begin using JsonDataAccess, you need to initialize the class with the path to your JSON file:

```php
use App\JsonDataAccess;

$jsonDB = new JsonDataAccess('data.json');
