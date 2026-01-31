<?php
use Cake\Utility\Text;

$isAuthorized = !empty($loggedUser);

// Generate slug from pet name for cosmetic URL
$slug = strtolower(Text::slug($pet->pet_name, '-'));
?>

<!-- JS to update the browser URL without reloading -->
<script>
window.history.replaceState({}, '', '/PurrfectLTD/pet/edit/<?= $slug ?>');
</script>

<div class="container my-5">
    <?php if ($isAuthorized): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Edit Pet</h5>

                        <?= $this->Form->create($pet, [
                            'type' => 'file',
                            'url' => ['action' => 'edit', $pet->id] // backend still uses ID
                        ]) ?>

                        <?= $this->Form->control('pet_name', [
                            'class' => 'form-control',
                            'label' => 'Pet Name'
                        ]) ?>

                        <?= $this->Form->control('pet_type', [
                            'class' => 'form-control',
                            'label' => 'Pet Type'
                        ]) ?>

                        <?= $this->Form->control('pet_image', [
                            'type' => 'file',
                            'class' => 'form-control',
                            'label' => 'Pet Image'
                        ]) ?>

                        <br>

                        <?= $this->Form->button('Update Pet', [
                            'class' => 'btn btn-primary'
                        ]) ?>

                        <?= $this->Form->end() ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
