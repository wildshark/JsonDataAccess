<?php
/*
    Copyright (c) [Year] [Your Name or Your Company]
    All rights reserved.

    This code is a proprietary software, and its usage, modification, and distribution
    are subject to the terms and conditions defined in the license agreement.
*/
//namespace App;
namespace App;
class JsonDataAccess
{
    private $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    private function loadData()
    {
        $data = file_get_contents($this->filename);
        return json_decode($data, true);
    }

    private function saveData($data)
    {
        $jsonData = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->filename, $jsonData);
    }

    private function convertSize($bytes) {
 
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2). ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2). ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2). ' KB';
        } else {
            $bytes = $bytes. ' Bytes';
        }
        return $bytes;
    }

    public function getFileSize()
    {
        $bytes = filesize($this->filename);
        return $this->convertSize($bytes);
    }

    public function countRows($entity)
    {
        $data = $this->loadData();
    
        if (!isset($data[$entity])) {
            return 0;
        }
    
        return count($data[$entity]);
    }

    public function getAll($entity)
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            return [];
        }

        return $data[$entity];
    }

    public function getById($entity, $id)
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            return null;
        }

        $items = $data[$entity];

        foreach ($items as $item) {
            if ($item['id'] == $id) {
                return $item;
            }
        }

        return null;
    }

    public function create($entity, $item,$descending = true)
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            $data[$entity] = [];
        }

        $item['id'] = uniqid(); // Generate a unique ID (you may want to use a more robust method)

        if($descending != true) {
            $data[$entity][] = $item;
        }else{
            array_unshift($data[$entity],$item);
        }
        
        $this->saveData($data);

        return $item;
    }

    public function update($entity, $id, $updatedItem)
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            return null;
        }

        $items = &$data[$entity];

        foreach ($items as &$item) {
            if ($item['id'] == $id) {
                $item = array_merge($item, $updatedItem);
                $this->saveData($data);
                return $item;
            }
        }

        return null;
    }

    public function delete($entity, $id)
    {
        $data = $this->loadData();

        if (!isset($data[$entity])) {
            return null;
        }

        $items = &$data[$entity];

        foreach ($items as $key => $item) {
            if ($item['id'] == $id) {
                unset($items[$key]);
                $this->saveData($data);
                return $item;
            }
        }

        return null;
    }

    public function search($entity,$fields = false,$value)
    {

        if($fields !=false){
            return $this->searchFields($entity, $fields, $value);
        }else{
            return $this->searchAllFields($entity, $value);
        }
    
    }

   public function searchFields($entity, $fields, $value)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return [];
       }
   
       $items = $data[$entity];
       $results = [];
   
       foreach ($items as $item) {
           foreach ($fields as $field) {
               if (isset($item[$field]) && $item[$field] == $value) {
                   $results[] = $item;
                   break 2; // Breaks out of both loops once a match is found
               }
           }
       }
   
       return count($results) > 0? $results : null;
   }

   public function searchAllFields($entity, $value)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return [];
       }
   
       $items = $data[$entity];
       $results = [];
   
       foreach ($items as $item) {
           foreach ($item as $field => $fieldValue) {
               if ($fieldValue == $value) {
                   $results[] = $item;
                   break 2; // Breaks out of both loops once a match is found
               }
           }
       }
   
       return count($results) > 0? $results : null;
   }

   private function searchBetween($entity, $field, $lowerBound, $upperBound)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return [];
       }
   
       $items = $data[$entity];
       $results = [];
   
       foreach ($items as $item) {
           if (isset($item[$field]) && $item[$field] >= $lowerBound && $item[$field] <= $upperBound) {
               $results[] = $item;
           }
       }
   
       return count($results) > 0? $results : null;
   }

   private function searchWordsLike($entity, $pattern)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return [];
       }
   
       $items = $data[$entity];
       $results = [];
   
       foreach ($items as $item) {
           foreach ($item as $field => $fieldValue) {
               if (strpos($fieldValue, $pattern)!== false) {
                   $results[] = $item;
                   break; // Break out of the inner loop once a match is found
               }
           }
       }
   
       return $results;
   }

   public function sort($entity, $field, $order = 'asc')
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return []; // Return empty array if entity doesn't exist
       }
   
       usort($data[$entity], function($a, $b) use ($field, $order) {
           if ($order === 'asc') {
               return strcmp($a[$field], $b[$field]); // For strings
               // return $a[$field] <=> $b[$field]; // For PHP 7.0+
           } else { // Descending order
               return strcmp($b[$field], $a[$field]); // For strings
               // return $b[$field] <=> $a[$field]; // For PHP 7.0+
           }
       });
   
       return $data[$entity];
   }

   public function isBetween($entity, $field, $lowerBound, $upperBound){
        return $this->searchBetween($entity, $field, $lowerBound, $upperBound);
   }

   public function isLike($entity, $pattern){
        return $this->searchWordsLike($entity, $pattern) !== false;
   }

   public function orContain($entity, $pattern){
        return $this->searchWordsLike($entity, $pattern) !== false;
   }

    public function export($entity,$csvFilename,$selectedFields = false){
        if($selectedFields == false){
            return $this->exportSelectedFieldsToCsv($entity, $selectedFields, $csvFilename);
        }else{
            return $this->exportToCsv($entity, $csvFilename);
        }
    }

    public function import($entity,$csvFilename, $selectedFields = false){
        if($selectedFields == false){
            return $this->importFromCsv($entity, $csvFilename);
        }else{
            return $this->importSelectedFieldsFromCsv($entity, $selectedFields, $csvFilename);
        }
    }

   public function sum($entity, $field)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return 0;
       }
   
       $total = 0;
   
       foreach ($data[$entity] as $item) {
           if (isset($item[$field]) && is_numeric($item[$field])) {
               $total += $item[$field];
           }
       }
   
       return $total;
   }

   public function average($entity, $field)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           return 0;
       }
   
       $totalSum = 0;
       $totalItems = count($data[$entity]);
   
       foreach ($data[$entity] as $item) {
           if (isset($item[$field]) && is_numeric($item[$field])) {
               $totalSum += $item[$field];
           }
       }
   
       if ($totalItems == 0) {
           return 0; // Avoid division by zero
       }
   
       return $totalSum / $totalItems;
   }

   private function exportToCsv($entity, $csvFilename)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           die("Entity '$entity' does not exist.");
       }
   
       $csvContent = "";
   
       // Add headers to the CSV content
       $headers = array_keys((array)$data[$entity][0]); // Assuming all items have the same structure
       $csvContent.= implode(",", $headers). "\n"; // First row with column names
   
       // Add data rows to the CSV content
       foreach ($data[$entity] as $row) {
           $csvContent.= implode(",", array_map(function($cell) {
               return "\"$cell\""; // Wrap cell values in quotes to handle commas and newlines properly
           }, $row)). "\n";
       }
   
       // Write the CSV content to a file
       file_put_contents($csvFilename, $csvContent);
   
       echo "CSV file exported successfully.";
   }

   private function exportSelectedFieldsToCsv($entity, $selectedFields, $csvFilename)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           die("Entity '$entity' does not exist.");
       }
   
       $csvContent = "";
   
       // Determine headers based on selected fields
       $headers = $selectedFields;
       $csvContent.= implode(",", $headers). "\n"; // First row with column names
   
       // Add data rows to the CSV content
       foreach ($data[$entity] as $row) {
           $rowData = [];
           foreach ($selectedFields as $field) {
               if (isset($row[$field])) {
                   $rowData[] = "\"". $row[$field]. "\""; // Wrap cell values in quotes
               } else {
                   $rowData[] = ""; // Empty cell if field is not set
               }
           }
           $csvContent.= implode(",", $rowData). "\n";
       }
   
       // Write the CSV content to a file
       file_put_contents($csvFilename, $csvContent);
   
       echo "CSV file exported successfully.";
   }

   private function importFromCsv($entity, $csvFilename)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           $data[$entity] = []; // Initialize entity if it doesn't exist
       }
   
       // Check if the CSV file exists and is readable
       if (!is_readable($csvFilename)) {
           die("Cannot read the CSV file: $csvFilename");
       }
   
       // Open the CSV file
       $handle = fopen($csvFilename, 'r');
       if (!$handle) {
           die("Failed to open the CSV file: $csvFilename");
       }
   
       // Read the first line to get the headers
       $headers = fgetcsv($handle);
   
       // Loop through the rest of the lines and convert them to associative arrays
       while (($row = fgetcsv($handle))!== FALSE) {
           $item = array_combine($headers, $row); // Combine headers with row values
           $data[$entity][] = $item; // Add the item to the entity
       }
   
       fclose($handle);
   
       // Save the updated data back to the JSON file
       $this->saveData($data);
   
       echo "CSV file imported successfully.";
   }
   
   private function importSelectedFieldsFromCsv($entity, $selectedFields, $csvFilename)
   {
       $data = $this->loadData();
   
       if (!isset($data[$entity])) {
           $data[$entity] = []; // Initialize entity if it doesn't exist
       }
   
       // Check if the CSV file exists and is readable
       if (!is_readable($csvFilename)) {
           die("Cannot read the CSV file: $csvFilename");
       }
   
       // Open the CSV file
       $handle = fopen($csvFilename, 'r');
       if (!$handle) {
           die("Failed to open the CSV file: $csvFilename");
       }
   
       // Read the first line to get the headers
       $headers = fgetcsv($handle);
   
       // Prepare a map of selected fields to their indices for quick access
       $fieldIndices = array_flip(array_intersect_key($headers, $selectedFields));
   
       // Loop through the rest of the lines and convert them to associative arrays
       while (($row = fgetcsv($handle)!== FALSE)) {
           $item = [];
           foreach ($selectedFields as $field) {
               $index = $fieldIndices[$field];
               if ($index!== null) { // Check if the field is in the selected fields list
                   $item[$field] = $row[$index]; // Assign value to the item array
               }
           }
           $data[$entity][] = $item; // Add the item to the entity
       }
   
       fclose($handle);
   
       // Save the updated data back to the JSON file
       $this->saveData($data);
   
       echo "CSV file imported successfully.";
   }  
}