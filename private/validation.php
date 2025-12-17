<?php


/* DATA VALIDATION */

/**
 * This will validate our user input on the add and edit pages of our application.
 * 
 * @param string $genus_name
 * @param string $species_name
 * @param string $status
 * @param int $year (not NULL)
 * @param int $size
 * @param int $weight
 * @param int $continent
 * @param string $country
 * @param string $time_period
 * @param string $description
 * @param string $type
 * @param int $is_alive [boolean] 
 */
function dinosaur_input($genus_name, $species_name, $status, $year, $size, $weight, $continent, $country, $time_period, $description, $type, $is_alive, $enumerated_array, $continents_array, $time_period_array, $dino_type)
{

    global $connection;
    $errors = [];
    $validated_data = [];

    // Validate Genus Name
    $genus_name = trim($genus_name);
    if (!empty($genus_name)) {
        if (strlen($genus_name) > 100) {
            $errors[] = "genus_name must be 100 characters or fewer.";
        }
        // Use mysqli_real_escape_string to help sanitize input before database insertion.
        $validated_data['genus_name'] = mysqli_real_escape_string($connection, $genus_name);
    } else {
        $validated_data['genus_name'] = null;
    }

    // Validate Species Name
    $species_name = trim($species_name);
    if (!empty($species_name)) {
        if (strlen($species_name) > 100) {
            $errors[] = "url must be 100 characters or fewer.";
        }
        // Use mysqli_real_escape_string to help sanitize input before database insertion.
        $validated_data['species_name'] = mysqli_real_escape_string($connection, $species_name);
    } else {
        $validated_data['species_name'] = null;
    }

    // Validate Status
    if (empty($status)) {
        $errors[] = "status is required.";
    } elseif (!array_key_exists($status, $enumerated_array)) {
        $errors[] = "Status error.";
    }

    $validated_data['status'] = $status;

    // Validate Year
    $year = filter_var($year, FILTER_SANITIZE_NUMBER_INT);
    if (empty($year)) {
        $errors[] = "year is required.";
    } elseif (!filter_var($year, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 9999]])) {
        $errors[] = "Year must be positive.";
    }

    $validated_data['year'] = $year;


    // Validate Size
    $size = filter_var($size, FILTER_SANITIZE_NUMBER_FLOAT);
    if (empty($size)) {
        $errors[] = "Size is required.";
    } elseif (!is_numeric($size)) {
        $errors[] = "Size must be a number.";
    } else {
        $size_val = (float) $size;
        if ($size_val < 0.1 || $size_val > 99999.99) {
            $errors[] = "Size must be between 0.1 and 99999.99.";
        } else {
            $validated_data['size'] = number_format($size_val, 2, '.', '');
        }
    }


    // Validate Weight
    $weight = filter_var($weight, FILTER_SANITIZE_NUMBER_FLOAT);
    if (empty($weight)) {
        $errors[] = "Weight is required.";
    } elseif (!is_numeric($weight)) {
        $errors[] = "Weight must be a number.";
    } else {
        $weight_val = (float) $weight;
        if ($weight_val < 0.1 || $weight_val > 99999999999999.9) {
            $errors[] = "Weight must be between 0.1 and 99999999999999.9.";
        } else {
            $validated_data['weight'] = number_format($weight_val, 1, '.', '');
        }
    }

    // Validate Continent
    if (empty($continent)) {
        $errors[] = "continent is required.";
    } elseif (!array_key_exists($continent, $continents_array)) {
        $errors[] = "continent error.";
    }

    $validated_data['continent'] = $continent;


    // Validate Country
    $country = trim($country);
    if (empty($country)) {
        $errors[] = "country is required.";
    } elseif (strlen($country) < 2 || strlen($country) > 36) {
        $errors[] = "country must be between 2 and 30 characters.";
    } elseif (preg_match('/["\']/', $country)) { // Sanitize quotes
        $country = mysqli_real_escape_string($connection, $country);
    }

    $validated_data['country'] = $country;

    // Validate Time Period
    if (empty($time_period)) {
        $errors[] = "Time Period is required.";
    } elseif (!array_key_exists($time_period, $time_period_array)) {
        $errors[] = "Time Period Error";
    }

    $validated_data['time_period'] = $time_period;

    // Validate Description
    $description = trim($description);
    if (!empty($description)) {
        if (strlen($description) > 500) {
            $errors[] = "Description must be 500 characters or fewer.";
        }
        // Use mysqli_real_escape_string to help sanitize input before database insertion.
        $validated_data['description'] = mysqli_real_escape_string($connection, $description);
    } else {
        $errors[] = "Write a short description for this dino.";
    }


    // Validate Type
    if (empty($type)) {
        $errors[] = "type is required.";
    } elseif (!array_key_exists($type, $dino_type)) {
        $errors[] = "Type Error";
    }

    $validated_data['type'] = $type;


    // Validate alive
    if (isset($is_alive)) {
        if ($is_alive !== '1' && $is_alive !== '0') {
            $errors[] = "Invalid selection for alive.";
        } else {
            // Convert the value to a boolean: true if '1', false if '0'.
            $validated_data['is_alive'] = ($is_alive === '1');
        }
    } else {
        // Default to 0 (or false) if no selection is made.
        $validated_data['is_alive'] = 0;
    }


    // A function can only return one value, so we're packing a few things into an array.
    return [
        'is_valid' => empty($errors),
        'errors' => $errors,
        'data' => $validated_data
    ];
}
