<?php
$isAuthorized = !empty($loggedUser);

// Image handling
$imageData = is_resource($pet->pet_image) ? stream_get_contents($pet->pet_image) : $pet->pet_image;

// Edit/Delete links
$editPetLink   = $this->Url->build('/pets/edit/' . $pet->id);
$deletePetLink = $this->Url->build('/pets/delete/' . $pet->id);

// Added date
$addedDate     = $pet->date ? $pet->date->i18nFormat('dd MMMM yyyy HH:mm') : '';

// Likes/dislikes data is already passed from controller
$remainingLikes = $likesData['likes_count'] - count($likesData['liked_users']);
$remainingDislikes = $likesData['dislikes_count'] - count($likesData['disliked_users']);
?>

<style>
/* Center card vertically and horizontally */
body {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f5f5f5;
}

/* Open dropdown on hover */
.dropdown:hover .dropdown-menu {
    display: block;
}

/* Optional: style the dropdown items */
.dropdown-menu li {
    padding: 4px 8px;
    font-size: 0.85rem;
}

/* Ensure dropdown appears above the card (dropup) */
.dropup .dropdown-menu {
    top: auto;
    bottom: 100%;
    margin-bottom: 5px;
    left: 0;
}
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">

            <div class="card text-white bg-secondary pet-card"
                 style="display:flex; flex-direction:column; border-radius:0.5rem; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.15);">

                <!-- Pet Image -->
                <img src="data:image/jpeg;base64,<?= base64_encode($imageData) ?>"
                     class="card-img-top"
                     alt="<?= h($pet->pet_name) ?>"
                     style="width:100%; height:200px; object-fit:cover; display:block;">

                <div class="card-body d-flex flex-column" style="flex:1; padding:0.75rem;">
                    <h5 class="card-title"><?= h($pet->pet_name) ?></h5>
                    <p class="card-text mb-1"><?= h($pet->pet_type) ?></p>
                    <p class="card-text text-muted mb-1" style="font-size:0.85rem;">Added: <?= $addedDate ?></p>
                    <p class="card-text text-muted mb-2" style="font-size:0.85rem;">By Owner: <?= h($pet->user->first_name . ' ' . $pet->user->second_name) ?></p>

                    <!-- Buttons -->
                    <div class="mt-auto mb-2">
                        <?php if ($isAuthorized && ($pet->user_id === $loggedUser->id || $loggedUser->is_admin)): ?>
                            <a href="<?= $editPetLink ?>" class="btn btn-warning btn-sm mb-1 w-100">Edit</a>
                            <a href="<?= $deletePetLink ?>" class="btn btn-danger btn-sm mb-1 w-100" onclick="return confirm('Are you sure?')">Delete</a>
                        <?php endif; ?>
                        <a href="<?= $this->Url->build('/') ?>" class="btn btn-light btn-sm mb-1 w-100">Back</a>
                    </div>

                   <!-- Likes / Dislikes Section -->
<div class="d-flex gap-3 align-items-center mt-3">

    <!-- Thumbs Up (Dropup) -->
    <div class="dropdown dropup">
        <a class="d-flex align-items-center text-decoration-none"
           href="#"
           role="button"
           id="likeDropdown<?= $pet->id ?>"
           data-bs-toggle="dropdown"
           aria-expanded="false">
            <i class="bi bi-hand-thumbs-up-fill text-white fs-5 me-1"></i> <!-- icon -->
            <span class="ms-1 text-dark"><?= $likesData['likes_count'] ?></span>
        </a>

        <ul class="dropdown-menu dropdown-menu-dark p-2" aria-labelledby="likeDropdown<?= $pet->id ?>" style="min-width:200px;">
            <?php foreach ($likesData['liked_users'] as $userName): ?>
                <li><?= h($userName) ?></li>
            <?php endforeach; ?>
            <?php if ($remainingLikes > 0): ?>
                <li class="text-center text-muted" style="font-size:0.85rem;">
                    and <?= $remainingLikes ?> more...
                </li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Thumbs Down (Dropup) -->
    <div class="dropdown dropup">
        <a class="d-flex align-items-center text-decoration-none"
           href="#"
           role="button"
           id="dislikeDropdown<?= $pet->id ?>"
           data-bs-toggle="dropdown"
           aria-expanded="false">
            <i class="bi bi-hand-thumbs-down-fill text-white fs-5 me-1"></i> <!-- icon -->
            <span class="ms-1 text-dark"><?= $likesData['dislikes_count'] ?></span>
        </a>

        <ul class="dropdown-menu dropdown-menu-dark p-2" aria-labelledby="dislikeDropdown<?= $pet->id ?>" style="min-width:200px;">
            <?php foreach ($likesData['disliked_users'] as $userName): ?>
                <li><?= h($userName) ?></li>
            <?php endforeach; ?>
            <?php if ($remainingDislikes > 0): ?>
                <li class="text-center text-muted" style="font-size:0.85rem;">
                    and <?= $remainingDislikes ?> more...
                </li>
            <?php endif; ?>
        </ul>
    </div>

</div>
                </div> <!-- .card-body -->
            </div> <!-- .card -->

        </div> <!-- .col -->
    </div> <!-- .row -->
</div> <!-- .container -->
