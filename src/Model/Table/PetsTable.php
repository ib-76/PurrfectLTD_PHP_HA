<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class PetsTable extends Table {

    public function initialize(array $config): void
    {
        //https://book.cakephp.org/5/en/orm/associations.html
        
       $this->belongsTo("Users");
        $this->setDisplayField('pet_name');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('pet_name', 'create')
            ->notEmptyString('pet_name', 'Pet name is required');

        return $validator;
    }
}

?>