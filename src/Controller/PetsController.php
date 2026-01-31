<?php

namespace App\Controller;

use Cake\Event\EventInterface;

class PetsController extends AppController
{

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        // Allow the pet detail page for guests
        $this->Authentication->addUnauthenticatedActions(['detail']);
    }


    //view: templates/Users/index.php

    public function index()
    {
        $PetsTable = $this->fetchTable("Pets");

        $allPets = $PetsTable->find()->all();

        //for debugging purposes
        //pr($allPets);
        // die;
        // dd($allPets);

        //pass the $allPets results to the view (Pets/index.php)
        $this->set("allPets", $allPets);
    }

    public function detail($petslug = null)
    {
        $PetsTable = $this->fetchTable("Pets");

        // Find ONE pet by slug
        $pet = $PetsTable->find()
            ->contain(['Users'])
            ->where(['LOWER(pet_name) LIKE' => str_replace('-', ' ', strtolower($petslug))])
            ->first();

        if (!$pet) {
            $this->Flash->error('Pet not found');
            return $this->redirect(['controller' => 'Pages', 'action' => 'purrfect_home']);
        }

        // Get likes/dislikes data
        $likesData = $this->getLikesData($pet->id);

        $this->set(compact('pet', 'likesData'));
    }

    public function add()
    {

        // Get the Pets table
        $petsTable = $this->fetchTable("Pets");


        // Check if the form was submitted
        if ($this->request->is("post")) {

            // Get the POST data
            $data = $this->request->getData();

            // Handle uploaded image
            $uploadedFile = $data['pet_image'] ?? null;

            if ($uploadedFile instanceof \Laminas\Diactoros\UploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
                $data['pet_image'] = file_get_contents($uploadedFile->getStream()->getMetadata('uri'));
            } else {
                $data['pet_image'] = null; // optional: handle empty file
            }

            // Get logged-in user ID
            $identity = $this->request->getAttribute('identity');

            if ($identity) {
                $data['user_id'] = $identity->getIdentifier(); // usually the users.id
            }

            // Create a new Pet entity
            $newPet = $petsTable->newEntity($data);

            // Try saving
            if ($petsTable->save($newPet)) {
                $this->Flash->success("Pet has been added successfully!");
                return $this->redirect(['controller' => 'Pages', 'action' => 'purrfecthome']);
            } else {
                // Collect validation errors
                $errors = $newPet->getErrors();
                $errorMessages = '';
                foreach ($errors as $error) {
                    $errorMessages .= " - " . array_values($error)[0] . "<br>";
                }

                $this->Flash->error("Error saving pet!<br>$errorMessages", ['escape' => false]);
            }
        }
    }




    public function delete($id)
    {
        $petTodelete = $this->Pets->find()->where(['id' => $id])->first();

        if ($petTodelete != null) {
            if ($this->Pets->delete($petTodelete)) {
                $this->Flash->success($petTodelete->pet_name . " deleted!");
            } else {
                $this->Flash->error("Something wrong happened while deleting the pet.");
            }
        } else {
            $this->Flash->error("Pet does not exist!");
        }

        // NO VIEW IS REQUIRED
        return $this->redirect(['controller' => 'Pages', 'action' => 'purrfecthome']);
    }


    public function edit($id)
    {

        // users/edit/4 <- $id will be set to 4
        $petsTable = $this->fetchTable("Pets");

        // get pet by that id
        $petToEdit = $petsTable->get($id);

        if ($petToEdit == null) {
            $this->Flash->error("Pet does not exist.");
            return $this->redirect(['action' => 'index']);
        }

        $this->set('pet', $petToEdit);

        if ($this->request->is(['post', 'put'])) {

            $data = $this->request->getData();

            // --- Handle uploaded image ---
            $uploadedFile = $data['pet_image'] ?? null;

            if ($uploadedFile instanceof \Laminas\Diactoros\UploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
                // Read file contents as string to store in DB (BLOB)
                $data['pet_image'] = file_get_contents($uploadedFile->getStream()->getMetadata('uri'));
            } else {
                // Keep old image if none uploaded
                $data['pet_image'] = $petToEdit->pet_image;
            }

            $petToEdit = $petsTable->patchEntity($petToEdit, $data);

            if ($petsTable->save($petToEdit)) {
                $this->Flash->success("Pet updated!");
                return $this->redirect(['controller' => 'Pages', 'action' => 'purrfecthome']);
            } else {
                $errors = $petToEdit->getErrors();
                $errorMessages = '';

                foreach ($errors as $error) {
                    $errorMessages .= " - " . array_values($error)[0] . "<br>";
                }

                $this->Flash->error("Error updating pet!<br>$errorMessages", ['escape' => false]);
            }
        }
    }


    public function toggle()
    {
        $this->request->allowMethod(['post']);

        $userId = $this->Authentication->getIdentity()->id;
        $petId = $this->request->getData('pet_id');
        $isLiked = $this->request->getData('is_liked');

        $like = $this->Likes->find()
            ->where(['pet_id' => $petId, 'user_id' => $userId])
            ->first();

        if (!$like) {
            $like = $this->Likes->newEntity([
                'pet_id' => $petId,
                'user_id' => $userId,
                'is_liked' => $isLiked
            ]);
        } else {
            $like->is_liked = $isLiked;
        }

        $this->Likes->save($like);

        return $this->redirect($this->referer());
    }



    // In PetsController.php
    public function getLikesData($petId)
    {
        $LikesTable = $this->fetchTable('Likes');

        $likesQuery = $LikesTable->find()
            ->contain(['Users']) // get user info
            ->where(['Likes.pet_id' => $petId])
            ->all();

        $data = [
            'likes_count' => 0,
            'dislikes_count' => 0,
            'liked_users' => [],
            'disliked_users' => [],
        ];

        foreach ($likesQuery as $like) {
            if ($like->is_liked) {
                $data['likes_count']++;
                // Only store first 5 users for testing
                if (count($data['liked_users']) < 5) {
                    $data['liked_users'][] = $like->user->first_name . ' ' . $like->user->second_name;
                }
            } else {
                $data['dislikes_count']++;
                // Only store first 5 users for testing
                if (count($data['disliked_users']) < 5) {
                    $data['disliked_users'][] = $like->user->first_name . ' ' . $like->user->second_name;
                }
            }
        }

        return $data;
    }
}
