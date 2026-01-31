<?php
$isAuthorized = !empty($loggedUser);
?>

<div class="container my-5">

    <div class="card text-center border-secondary mb-4">
        <div class="card-body">
            <h5 class="card-title">My Pets</h5>
            <p class="card-text text-muted">Pets owned by me</p>
        </div>
    </div>

    <?php if ($isAuthorized): ?>
        <ul class="list-group">
            <?php foreach ($allPets as $pet): ?>
                <?php
                // Only show pets belonging to logged-in user
                if ($pet->user_id !== $loggedUser->id) continue;

                $petslug = strtolower(\Cake\Utility\Text::slug($pet->pet_name, '-'));
                $viewPetLink = $this->Url->build('/pet/' . $petslug);

                // Prepare small image
                $imageData = is_resource($pet->pet_image) ? stream_get_contents($pet->pet_image) : $pet->pet_image;
                $imgSrc = 'data:image/jpeg;base64,' . base64_encode($imageData);
                ?>
                <li class="list-group-item d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img src="<?= $imgSrc ?>" alt="<?= h($pet->pet_name) ?>" class="rounded me-3" style="width:50px; height:50px; object-fit:cover;">
                        <span><?= h($pet->pet_name) ?> </span>
                    </div>
                    <a href="<?= $viewPetLink ?>" class="btn btn-sm btn-primary">Detail</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            You must be logged in to see your pets.
        </div>
    <?php endif; ?>

</div>
