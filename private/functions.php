<?php
// HERES A CHANGE PROOF
//Since functions is included in the header of every page, i'll just store our arrays out of the way here and they'll always be available. - ty
$enumerated_array = [
    'Valid' => 'Valid',
    'Nomen Nudum' => 'Nomen Nudum',
    'Nomen Dubium' => 'Nomen Dubium',
    'Rejected' => 'Rejected'
];



$continents_array = [
    1 => "North America",
    2 => "South America",
    3 => "Africa",
    4 => "Europe",
    5 => "Asia",
    6 => "Australia",
    7 => "Antarctica"
];

$time_period_array = [
    'Early Triassic' => 'Early Triassic',
    'Late Triassic' => 'Late Triassic',
    'Early Jurassic' => 'Early Jurassic',
    'Late Jurassic' => 'Late Jurassic',
    'Early Cretaceous' => 'Early Cretaceous',
    'Late Cretaceous' => 'Late Cretaceous'
];

$dino_type = [
    'Terrestrial' => 'Terrestrial',
    'Avian' => 'Avian',
    'Aquatic' => 'Aquatic'
];



/* RESULTS TABLE */

/**
 * This counts the number of records we currently have in our table (in case any have been added or removed).
 */
function count_records()
{
    global $connection;
    $sql = "SELECT COUNT(*) FROM dinobase;";
    $results = mysqli_query($connection, $sql);
    $fetch = mysqli_fetch_row($results);
    return $fetch[0];
}

/**
 * This function lets us grab only the records we need for one page of paginated results.
 * 
 * @param int $limit
 * @param int $offset
 * @return bool|mysqli_result 
 */
function find_records($limit = 12, $offset = 0)
{
    global $connection;

    /*
        We'll need to write this as a prepared statement with one of two possibilities:

        1. There is a limit, but no offset (ex. page 1);
        2. There is both a limit and an offset.
    */
    $sql = "SELECT * FROM dinobase"; // Make sure you don't terminate inside of your string!

    if ($limit > 0) {
        // If there is a limit (and there should be), we'll tack this onto the end of our SQL statement.
        $sql .= " LIMIT ?";

        if ($offset > 0) {
            // If there is an offset, we'll add it too.
            $sql .= " OFFSET ?";

            // In this case, we have two parameters (both integers).
            $statement = $connection->prepare($sql);
            $statement->bind_param("ii", $limit, $offset);
        } else {
            // If there is no offset, we just have one parameter (the limit).
            $statement = $connection->prepare($sql);
            $statement->bind_param("i", $limit);
        }
    }

    $statement->execute();
    return $statement->get_result();
}

/**
 * This function fetches all of the dinos in our database and prints them out in an HTML table.
 * Note: We will add some parameters later on when we need it for our Edit and Delete pages.
 * 
 * @param ?callable $button_callback - A callback function that generates the 'actions' column content.
 * 
 * @return void (because this function prints a table and handles potential errors on its own)
 */
function generate_table($dinos, $button_callback = null)
{
    // If we got at least one record back, we'll spit out a table.
    if (count($dinos) > 0) {

        echo "<table class=\"table table-bordered table-hover\"> \n
              <thead> \n
              <tr class=\"table-dark\"> \n
              <th scope=\"col\">Image</th> \n
              <th scope=\"col\">Species Name</th> \n";

        if ($button_callback != null) {
            echo "<th scope=\"col\">Action</th> \n";
        }

        echo "</tr> \n
              </thead> \n
              <tbody> \n";

        foreach ($dinos as $dino) {
            extract($dino);
            echo "<tr> \n
                  <td>
                    <img src=\"../data/img/thumbs/$url\" alt=\"$genus_name thumbnail\"style=\"max-width: 100px; height: auto;\">
                  </td> \n
                  <td>$species_name</td> \n
                  ";

            if ($button_callback != null) {
                // This is our callback function (which is a function passed into another function).
                $buttons = call_user_func($button_callback, $dino);

                echo "<td>$buttons</td> \n";
            }

            echo "</tr> \n";
        }

        echo "</tbody> \n
              </table> \n
              <aside> \n
              <h3 class=\"fs-5 fw-normal\">Notes</h3> \n
              <p class=\"text-muted my-3\">&starf; Example notes.</p> \n
              <p class=\"text-muted my-3\">Hover over <i class=\"bi bi-info-circle\" data-bs-toggle=\"tooltip\" title=\"I'm a tooltip!\"></i> to see additional trivia about the city.</p> \n
              </aside> \n";
    } else {
        echo "<h2 class=\"fw-light\">Oh no!</h2>";
        echo "<p>We're sorry, but we weren't able to find anything.</p>";
    }
}

