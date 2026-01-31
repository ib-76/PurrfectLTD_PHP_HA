<div class="col-3 mx-auto text-center">
    <h3>Login</h3>

    <?= $this->Form->create() ?>
    <?= $this->Form->control('user_email', ['required' => true, 'label' => false, 'class' => 'form-control mb-3', 'placeholder' => 'Enter your username']) ?>
    <?= $this->Form->control('password', ['required' => true, 'label' => false, 'class' => 'form-control mb-3', 'placeholder' => 'Enter your password']) ?>
    <?= $this->Form->submit('Login', ['class' => 'btn btn-success']); ?>
    <?= $this->Form->end() ?>

    <hr class="my-3">
    <?= $this->Html->link("Register", ['action' => 'add'], ['class' => 'btn btn-info']) ?>
</div>