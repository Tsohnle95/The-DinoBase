<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data" class="border border-secondary-subtle rounded shadow-sm p-3">

    <h2 class="fs-4 fs-light mb-4">Dino Details</h2>

    <!-- Genus Name -->
    <div class="mb-4">
        <label for="genus_name" class="form-label">Genus Name</label>
        <input type="text" id="genus_name" name="genus_name" class="form-control" value="<?= htmlspecialchars(($_POST['genus_name'] ?? $dino['genus_name'] ?? '')); ?>">
        <p class="form-text">What Genus does your Dino belong to?</p>
    </div>


    <!-- Species name -->
    <div class="mb-4">
        <label for="species_name" class="form-label">Species Name</label>
        <input type="text" id="species_name" name="species_name" class="form-control" value="<?= htmlspecialchars(($_POST['species_name'] ?? $dino['species_name'] ?? '')); ?>">
        <p class="form-text">What is the name of your species?</p>
    </div>


    <!-- | valid | Noem nudum | noem dubium-->
    <div class="mb-4">
        <label for="status" class="form-label">Dino Status</label>
        <select name="status" id="status" class="form-select">
            <option value="">-- Please Select --</option>
            <?php
            foreach ($enumerated_array as $key => $value) {
                    $form_val = $_POST['status'] ?? $dino['status'] ?? '';
            $selected = ($form_val == $key) ? 'selected' : '';
            echo "<option value=\"$key\" $selected>$value</option>";
            }
            ?>
        </select>
    </div>


    <!-- discovery year -->
    <div class="mb-4">
        <label for="year" class="form-label">Discovery Year</label>
        <input type="number" class="form-control" name="year" id="year" value="<?= htmlspecialchars(($_POST['year'] ?? $dino['year'] ?? '')); ?>">
        <p class="form-text">When was this Dino discovered?</p>
    </div>


    <!-- Size -->
    <div class="mb-4">
        <label for="size" class="form-label">How tall is this Dino?</label>
        <input type="number" step="0.01" min="0" placeholder="e.g, 1.50" id="size" name="size" class="form-control" value="<?= htmlspecialchars(($_POST['size'] ?? $dino['size'] ?? '')); ?>">
        <p class="form-text">Enter this Dino's height in m.</p>
    </div>


    <!-- weight -->
    <div class="mb-4">
        <label for="weight" class="form-label">How much does this Dino weigh in kg?</label>
        <input type="number" step="0.01" min="0" placeholder="e.g, 1.50" id="weight" name="weight" class="form-control" value="<?= htmlspecialchars(($_POST['weight'] ?? $dino['weight'] ?? '')); ?>">
        <p class="form-text">Enter this Dino's weight in kg.</p>
    </div>


    <!-- continent  -->
    <div class="mb-4">
        <label for="continent" class="form-label">What continent is this Dino from?</label>
        <select name="continent" id="continent" class="form-select">
            <option value="">-- Please Select --</option>
            <?php
            foreach ($continents_array as $key => $value) {
               $form_val = $_POST['continent'] ?? $dino['continent'] ?? '';
            $selected = ($form_val == $key) ? 'selected' : '';
            echo "<option value=\"$key\" $selected>$value</option>";
            }
            ?>
        </select>
    </div>


    <!-- country -->
    <div class="mb-4">
        <label for="country" class="form-label">Country</label>
        <input type="text" id="country" name="country" class="form-control" max="50" value="<?= htmlspecialchars(($_POST['country'] ?? $dino['country'] ?? '')); ?>">
        <p class="form-text">What country is this Dino from?</p>
    </div>


    <!-- time period  -->
    <div class="mb-4">
        <label for="time_period" class="form-label">What time period is this Dino from?</label>
        <select name="time_period" id="time_period" class="form-select">
            <option value="">-- Please Select --</option>
            <?php
            foreach ($time_period_array as $key => $value) {

                    $form_val = $_POST['time_period'] ?? $dino['time_period'] ?? '';
            $selected = ($form_val == $key) ? 'selected' : '';
            echo "<option value=\"$key\" $selected>$value</option>";
            }
            ?>
        </select>
    </div>


    <!-- description -->
    <div class="mb-4">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control" maxlength="500"><?= htmlspecialchars(($_POST['description'] ?? $dino['description'] ?? '')); ?></textarea>
        <p class="form-text">Please describe your Dino.</p>
    </div>

    <!-- type Terrestial','Avian','Aquatic -->
      <div class="mb-4">
        <label for="type" class="form-label">What type is your Dino?</label>
        <select name="type" id="type" class="form-select">
            <option value="">-- Please Select --</option>
            <?php
            foreach ($dino_type as $key => $value) {

                    $form_val = $_POST['type'] ?? $dino['type'] ?? '';
            $selected = ($form_val == $key) ? 'selected' : '';
            echo "<option value=\"$key\" $selected>$value</option>";
            }
            ?>
        </select>
    </div>


    <!-- still alive? radio buttons -->
    <label class="form-label d-block">Is this Dino still alive today? (It will appear on the featured page if it is)</label>


    <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="alive" id="is-alive" value="1"
            <?= ($_POST['alive'] ?? $dino['alive'] ?? '') == 1 ? 'checked' : '' ?>>
        <label class="form-check-label" for="is-alive">Yes</label>
    </div>


    <div class="form-check form-check-inline mb-4">
        <input class="form-check-input" type="radio" name="alive" id="not-alive" value="0"
            <?= ($_POST['alive'] ?? $dino['alive'] ?? '') == 0 ? 'checked' : '' ?>>
        <label class="form-check-label" for="not-alive">No</label>
    </div>

<!-- hidden input for id  -->
  <input type="hidden" name="id" value="<?= htmlspecialchars($dino['id'] ?? ''); ?>">

    <!-- Dino Image Upload Section -->
    <div class="mb-4">
        <h3 class="fs-5 mb-3">Dino Image</h3>
        <label for="img-file" class="form-label">Upload Image</label>
        <input type="file" id="img-file" name="img-file" accept=".png, .jpg, .jpeg, .webp, .avif" class="form-control">
        <p class="form-text">Accepted file types: AVIF, JPG, JPEG, PNG, WebP (Max 2MB)</p>
    </div>
    
    <!-- Submit -->
    <input type="submit" id="submit" name="submit" value="Submit" class="btn btn-lg btn-dark my-4 d-block">
</form>


<!-- file name (to do later) -->

<!-- url (to do later) -->