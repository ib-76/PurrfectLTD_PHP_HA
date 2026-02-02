<?php

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Log\Log;

class LikesController extends AppController
{



    public function toggle()
    {
        $this->request->allowMethod(['post']);


        $UsersTable = $this->fetchTable('Users');
        $PetsTable = $this->fetchTable('Pets');

        $userId = $this->Authentication->getIdentity()->id;
        $petId = $this->request->getData('pet_id');

        $user = $UsersTable->get($userId);
        $pet = $PetsTable->get($petId);
        
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

            //  Log the like/unlike
            Log::info(
                sprintf(
                    'User %s %s pet %s.',
                    $user->user_email,
                    $isLiked ? 'liked' : 'unliked',
                    $pet->pet_name // make sure this matches your table column
                ),
                ['scope' => 'like']
            );
        } else {
            $this->Flash->error('Could not save your reaction.');
        }

        return $this->redirect($this->referer());
    }
}
