<?php
namespace App\Controller;

use Cake\Event\EventInterface;

class LikesController extends AppController
{



public function toggle()
{
    $this->request->allowMethod(['post']);

    $userId = $this->Authentication->getIdentity()->id;
    $petId = $this->request->getData('pet_id');
    $isLiked = $this->request->getData('is_liked');

    //for debugging purposes
      //  pr($userId);
       // pr( $petId);
      //  pr ($isLiked);
     //   die;
      //  dd($isLiked);


   $userLike = $this->Likes->find()
    ->where(['pet_id' => $petId, 'user_id' => $userId])
    ->first();

if (!$userLike) {
    $userLike = $this->Likes->newEntity([
        'pet_id' => $petId,
        'user_id' => $userId,
        'is_liked' => $isLiked
    ]);
} else {
    $userLike->is_liked = $isLiked;
}

if ($this->Likes->save($userLike)) {
    $this->Flash->success($isLiked ? 'You liked this pet!' : 'You unliked this pet.');
} else {
    $this->Flash->error('Could not save your reaction.');
}

    return $this->redirect($this->referer());
}




}