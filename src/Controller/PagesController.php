<?php
namespace App\Controller;
use Cake\Event\EventInterface;

class PagesController extends AppController
{


public function beforeFilter(EventInterface $event): void
{
    parent::beforeFilter($event);

    $this->Authentication->addUnauthenticatedActions(['purrfecthome']);
}
   

public function purrfecthome()
{
    $Pets  = $this->fetchTable('Pets');
    $Likes = $this->fetchTable('Likes');

    $userId = $this->Authentication->getIdentity()?->id;

    // 1. Fetch pets
    $allPets = $Pets->find()
        ->contain(['Users'])
        ->all();

    // 2. Fetch likes ONLY for this user
    $likes = [];
    if ($userId) {
        $likes = $Likes->find()
            ->where(['user_id' => $userId])
            ->all();
    }

    // 3. Index likes by pet_id
    $likesByPet = [];
    foreach ($likes as $like) {
        $likesByPet[$like->pet_id] = $like->is_liked;
    }

    // 4. Attach value directly to pet entities
    foreach ($allPets as $pet) {
        $pet->user_is_liked = $likesByPet[$pet->id] ?? null;
    }

    $this->set(compact('allPets'));
}


}
      

