<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;

class LikesTable extends Table {

    public function initialize(array $config): void
    {
        //https://book.cakephp.org/5/en/orm/associations.html
        
       
        // Associations
        $this->belongsTo('Pets');

        $this->belongsTo('Users');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('pet_id')
            ->requirePresence('pet_id', 'create')
            ->notEmptyString('pet_id');

        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->boolean('is_liked')
            ->requirePresence('is_liked', 'create')
            ->notEmptyString('is_liked');

        return $validator;
    }


    public function buildRules(RulesChecker $rules): RulesChecker
    {
        // 👇 THIS reflects your UNIQUE (pet_id, user_id)
        $rules->add(
            $rules->isUnique(
                ['pet_id', 'user_id'],
                'You already reacted to this pet.'
            )
        );

        // Optional but good practice
        $rules->add($rules->existsIn(['pet_id'], 'Pets'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));

        return $rules;
    }
}

?>