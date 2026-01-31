<?php

use Cake\Utility\Text;

$isAuthorized = !empty($loggedUser);
?>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    rel="stylesheet">

<div class="container my-5">

    <!-- ADD PET FORM -->
    <?php if ($isAuthorized): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Add Pet</h5>

                        <form method="post"
                            action="<?= $this->Url->build(['controller' => 'Pets', 'action' => 'add']) ?>"
                            enctype="multipart/form-data">

                            <input type="hidden"
                                name="_csrfToken"
                                value="<?= $this->request->getAttribute('csrfToken') ?>">

                            <div class="mb-3">
                                <label class="form-label">Pet Name</label>
                                <input type="text" name="pet_name" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pet Type</label>
                                <input type="text" name="pet_type" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pet Image</label>
                                <input type="file" name="pet_image" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Add Pet
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


    <!-- PET CARDS -->
    <div class="row g-4">

        <?php foreach ($allPets as $pet): ?>

            <?php
            $imageData = is_resource($pet->pet_image)
                ? stream_get_contents($pet->pet_image)
                : $pet->pet_image;

            $petslug = strtolower(Text::slug($pet->pet_name, '-'));
            $viewPetLink = $this->Url->build('/pet/' . $petslug);
            ?>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card text-white bg-secondary h-100 shadow-sm">

                    <img
                        src="data:image/jpeg;base64,<?= base64_encode($imageData) ?>"
                        class="card-img-top"
                        alt="<?= h($pet->pet_name) ?>"
                        style="height:250px; object-fit:cover;">

                    <div class="card-body d-flex flex-column">

                        <h5 class="card-title"><?= h($pet->pet_name) ?></h5>
                        <p class="card-text"><?= h($pet->pet_type) ?></p>

                        <p class="card-text text-muted small">
                            Added by: <?= h($pet->user->first_name) ?>
                        </p>

                        <a href="<?= $viewPetLink ?>"
                            class="btn btn-light btn-sm mt-auto w-100">
                            View
                        </a>

                        <!-- LIKE / UNLIKE -->
                        <?php if ($isAuthorized): ?>
                            <div class="d-flex justify-content-end gap-2 mt-2">

                                <?php if ($pet->user_is_liked === null): ?>
                                    <!-- EMPTY THUMBS UP -->
                                    <form method="post"
                                        action="<?= $this->Url->build(['controller' => 'Likes', 'action' => 'toggle']) ?>">
                                        <input type="hidden" name="_csrfToken"
                                            value="<?= $this->request->getAttribute('csrfToken') ?>">
                                        <input type="hidden" name="pet_id" value="<?= $pet->id ?>">
                                        <input type="hidden" name="is_liked" value="1">

                                        <button type="submit"
                                            class="btn btn-sm btn-link text-light p-0"
                                            title="Like">
                                            <i class="bi bi-hand-thumbs-up"></i>
                                        </button>
                                    </form>

                                <?php elseif ($pet->user_is_liked == 1): ?>
                                    <!-- FILLED THUMBS UP -->
                                    <form method="post"
                                        action="<?= $this->Url->build(['controller' => 'Likes', 'action' => 'toggle']) ?>">
                                        <input type="hidden" name="_csrfToken"
                                            value="<?= $this->request->getAttribute('csrfToken') ?>">
                                        <input type="hidden" name="pet_id" value="<?= $pet->id ?>">
                                        <input type="hidden" name="is_liked" value="0">

                                        <button type="submit"
                                            class="btn btn-sm btn-link text-light p-0"
                                            title="Dislike">
                                            <i class="bi bi-hand-thumbs-up-fill"></i>
                                        </button>
                                    </form>

                                <?php else: ?>
                                    <!-- FILLED THUMBS DOWN -->
                                    <form method="post"
                                        action="<?= $this->Url->build(['controller' => 'Likes', 'action' => 'toggle']) ?>">
                                        <input type="hidden" name="_csrfToken"
                                            value="<?= $this->request->getAttribute('csrfToken') ?>">
                                        <input type="hidden" name="pet_id" value="<?= $pet->id ?>">
                                        <input type="hidden" name="is_liked" value="1">

                                        <button type="submit"
                                            class="btn btn-sm btn-link text-light p-0"
                                            title="Like">
                                            <i class="bi bi-hand-thumbs-down-fill"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>


                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

        <?php endforeach; ?>

    </div>
</div>