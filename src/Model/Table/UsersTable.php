<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

//Model class
    
class UsersTable extends Table {
    
    public function initialize(array $config): void
    {
        //https://book.cakephp.org/5/en/orm/associations.html
        
     
         $this->hasMany("Pets");
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('first_name', 'create')
            ->notEmptyString('first_name', 'Name is required');

        $validator
            ->requirePresence('second_name', 'create')
            ->notEmptyString('second_name', 'Surname is required');

        return $validator;
    }
}

?>