<?php

$row = $row ?? [];
$year = $year ?? 0;
$size = $size ?? 0;
$weight = $weight ?? 0;
$continent = $continent ?? 'Unknown';
$country = $country ?? 'Unknown';
$time_period = $time_period ?? 0;
$description = $description ?? 'No description available';


function field($array, $key, $default = 'Unknown') {
    return $array[$key] ?? $default;
}
?>

<div class="card px-0">
    <div class="card-header text-bg-dark">
        <h3 class="card-title fw-light fs-5">
            <?= field($row, 'country', $country); ?>
        </h3>
    </div>

    <div class="card-body">

        <p class="card-text"><span class="fw-bold">Genus: </span>
            <?= field($row, 'genus_name'); ?>
        </p>

        <p class="card-text"><span class="fw-bold">Species: </span>
            <?= field($row, 'species_name'); ?>
        </p>

        <p class="card-text"><span class="fw-bold">Status: </span>
            <?= field($row, 'status'); ?>
        </p>

        <p class="card-text"><span class="fw-bold">Year: </span>
            <?= htmlspecialchars($year); ?>
        </p>

        <p class="card-text"><span class="fw-bold">Size: </span>
            <?= number_format($size); ?>
        </p>

        <p class="card-text"><span class="fw-bold">Weight: </span>
            <?= number_format($weight); ?>
        </p>

        <p class="card-text"><span class="fw-bold">Continent: </span>
            <?= $continent; ?>
        </p>

        <p class="card-text"><span class="fw-bold">Country: </span>
            <?= $country; ?>
        </p>

        <p class="card-text"><span class="fw-bold">Time Period: </span>

            <?= htmlspecialchars($time_period); ?>

        </p>

        <p class="card-text"><span class="fw-bold">Description: </span>
            <?= htmlspecialchars($description); ?>
        </p>

        <p class="card-text"><span class="fw-bold">Type: </span>
            <?= field($row, 'type'); ?>
        </p>

        <p class="card-text"><span class="fw-bold">Alive: </span>
            <?= field($row, 'is_alive') == 1 ? 'Yes' : 'No'; ?>
        </p>

    </div>
</div>