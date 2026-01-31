<h1>Add new User</h1>



<?php
    //https://book.cakephp.org/5/en/views/helpers/form.html

    echo $this->Form->create(); //open the <form> tag with the needed attributes

    echo $this->Form->control("first_name", ['label' => false, 'class' => 'form-control mb-3', 'placeholder' => 'Enter name']);

    echo $this->Form->control("second_name", ['label' => false, 'class' => 'form-control mb-3', 'placeholder' => 'Enter surname']);

    echo $this->Form->control("user_email", ['label' => false, 'class' => 'form-control mb-3', 'placeholder' => 'Enter your email']);
    
    echo $this->Form->control("password", ['label' => false, 'class' => 'form-control mb-3', 'placeholder' => 'Enter your password']);

  
    
  
    echo $this->Form->submit("Add user", ['class' => 'btn btn-warning']);

    echo $this->Form->end(); // closes the <form> tags
?>