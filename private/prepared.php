<?php

/**
 * This function retrieves all cartoons using a simple SELECT statement.
 * There are no user-provided values or ?s required here.
 */
function get_user_dinos($user_id)
{
    $query = "SELECT * FROM dinobase WHERE user_id = ?;";
    $result = execute_prepared_statement($query, [$user_id], "i");

    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * This function executes a prepared statement with a provided query and its parameters.
 * 
 * @param string - $query 
 * @param array - $params
 * @param string - $types
 * @return mixed - Result set for SELECT queries, or TRUE/FALSE for other queries.
 */
function execute_prepared_statement($query, $params = [], $types = "")
{
    global $connection;

    $statement = $connection->prepare($query);

    // If our preparation fail, we need to handle the error and quit this function.
    if (!$statement) {
        die("Preparation failed: " . $connection->error);
    }

    // If we need to bind any parameters (i.e. if we're adding, editing, or deleting), we'll do so here. 
    if (!empty($params)) {
        $statement->bind_param($types, ...$params);
    }

    // This executes the statement right from within our IF condition.
    if (!$statement->execute()) {
        die("Execution failed: " . $statement->error);
    }

    // If it's a SELECT query, we should return the results so that we can print them out for the user.
    if (str_starts_with(strtoupper($query), "SELECT")) {
        return $statement->get_result();
    }

    // If we successfully executed our prepared statement (and it isn't a SELECT statement), this whole function will return TRUE.
    return TRUE;
}

/**
 * SELECT (retrieve) a specific dino by ID; used in the Edit page.
 * 
 * @param int $id
 * 
 * @return mysqli_result|BOOL|NULL
 */
function select_dino_by_id($id)
{
    $query = "SELECT * FROM dinobase WHERE id = ?;";
    $result = execute_prepared_statement($query, [$id], "i");
    return $result->fetch_assoc();
}

/**
 * INSERT (i.e. create or add) a new cartoon into the database; used in the Add page.
 * 
 * @param string $city_name
 * @param string|ENUM $province
 * @param int $population
 * @param int|BOOL $is_capital
 * @param string|NULL $trivia
 * 
 * @return BOOL - whether or not the prepared statement was properly executed.
 */
function insert_dino($genus_name, $species_name, $status, $year, $size, $weight, $continent, $country, $time_period, $description, $type, $is_alive, $url, $user_id)
{

    $query = "INSERT INTO dinobase (`genus_name`, `species_name`, `status`, `year`, `size`, `weight`, `continent`, `country`, `time_period`, `description`, `type`, `is_alive`, `url`, `user_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

    return execute_prepared_statement($query, [$genus_name, $species_name, $status, $year, $size, $weight, $continent, $country, $time_period, $description, $type, $is_alive, $url, $user_id], "sssiddissssssi");
}

/**
 * UPDATE an existing cartoon record; used in the Edit page.
 * 
 * @param string $cartoon_name
 * @param string $province
 * @param int $population
 * @param int $is_capital
 * @param string|null $trivia
 * @param int $cid
 * 
 * @return bool - status for whether or not the function worked.
 */
function update_dino($genus_name, $species_name, $status, $year, $size, $weight, $continent, $country, $time_period, $description, $type, $is_alive, $id, $url)
{
    $query = "UPDATE dinobase SET `genus_name` = ?, `species_name` = ?, `status` = ?, 
    `year` = ?, `size` = ?, `weight` = ?, `continent` = ?, `country` = ?, `time_period` = ?, `description` = ?, `type` = ?, `is_alive` = ?, `url` = ? WHERE `id` = ?;";

    return execute_prepared_statement(
        $query,
        [$genus_name, $species_name, $status, $year, $size, $weight, $continent, $country, $time_period, $description, $type, $is_alive, $url, $id],
        "sssiddissssisi"
    );
}

/**
 * DELETE a cartoon using the cartoon ID (i.e. the primary key); returns TRUE or FALSE.
 * 
 * @param int $id - the primary key.
 * 
 * @return BOOL|mysqli_result
 */
function delete_dino($id)
{
    $query = "DELETE FROM dinobase WHERE id = ?;";
    return execute_prepared_statement($query, [$id], "i");
}

function add_favourite($id)
{
    $query = "INSERT INTO favourites (dino_id, user_id) VALUES (?, ?);";
    return execute_prepared_statement($query, [$id, $_SESSION['user_id']], "ii");
}

function remove_favourite($id)
{
    $query = "DELETE FROM favourites WHERE dino_id = ? AND user_id = ?;";
    return execute_prepared_statement($query, [$id, $_SESSION['user_id']], "ii");
}

function get_fav_dinos($user_id) {
    $query = "SELECT dinobase.* FROM dinobase JOIN favourites ON dinobase.id = favourites.dino_id WHERE favourites.user_id = ?;";
    $result = execute_prepared_statement($query, [$user_id], "i");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function count_favourites_for_dino($dino_id) {
    $query = "SELECT COUNT(*) AS c FROM favourites WHERE dino_id = ?;";
    $result = execute_prepared_statement($query, [$dino_id], "i"); 
    $row = $result->fetch_assoc();
    return intval($row['c'] ?? 0);
}

function users_per_fav_dino($dino_id) {
    $query = "SELECT users FROM group_1_catalogue_admin JOIN favourites ON group_1_catalogue_admin.account_id = favourites.user_id WHERE favourites.dino_id = ?;";
    $result = execute_prepared_statement($query, [$dino_id], "i");
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    return array_map(fn($r) => $r['users'], $rows);
}

function profile_picture($user_id, $filename = null, $sync_admin = true)
{
    if (empty($user_id)) return null;


    if ($filename === null) {
        $query = "SELECT pic_url FROM profile_pics WHERE user_id = ?;";
        $result = execute_prepared_statement($query, [$user_id], "i");
        $row = $result->fetch_assoc();
        return $row['pic_url'] ?? null;
    }

    $query = "SELECT pic_id FROM profile_pics WHERE user_id = ?;";
    $result = execute_prepared_statement($query, [$user_id], "i");

    if ($result && $result->num_rows > 0) {
        $query = "UPDATE profile_pics SET pic_url = ? WHERE user_id = ?;";
        $ok = execute_prepared_statement($query, [$filename, $user_id], "si");
    } else {
        $query = "INSERT INTO profile_pics (user_id, pic_url) VALUES (?, ?);";
        $ok = execute_prepared_statement($query, [$user_id, $filename], "is");
    }

    if ($sync_admin) {
        $query = "UPDATE group_1_catalogue_admin SET profile_url = ? WHERE account_id = ?;";
        execute_prepared_statement($query, [$filename, $user_id], "si");
    }

    return $ok;
}
function get_featured_dinos(){
    $query = "SELECT * FROM dinobase WHERE is_alive = ?;";
    $is_alive = 1;

    $result = execute_prepared_statement($query, [$is_alive], "i");
    return $result->fetch_all(MYSQLI_ASSOC);
}