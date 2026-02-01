<?php

namespace App\Controller;

use Cake\View\JsonView;

class ApiController extends AppController
{
    public function viewClasses(): array
    {
        return [JsonView::class];
    }

    //https://book.cakephp.org/5/en/development/routing.html#restful-routing
    //https://book.cakephp.org/5/en/development/rest.html#rest



    public function beforeFilter(\Cake\Event\EventInterface $event): void
    {
        parent::beforeFilter($event);
        // Allow unauthenticated users to access the 'login' action
        $this->Authentication->addUnauthenticatedActions(['index', 'view', 'delete','add']);
    }


    //http://localhost/MyApp/api.json
    public function index()
    {
        $usersTable = $this->fetchTable("Users");

        $users = $usersTable->find()->select(['first_name', 'second_name', 'id'])->contain(
            ["Pets" => function ($q) {
                return $q->select(['Pets.pet_name', 'pet_type', 'Pets.id']);
            }]
        )->all();

        $this->set('users', $users);

        $this->viewBuilder()->setOption('serialize', ['users']); //to output based on format passed
    }

    //http://localhost/MyApp/api/[id].json
    public function view($userId)
    {
        $usersTable = $this->fetchTable("Users");

        $user = $usersTable->findById($userId)->select(['first_name', 'second_name', 'id'])->contain(
            ["Pets" => function ($q) {
                return $q->select(['Pets.pet_name', 'pet_type', 'id']);
            }]
        )->first();

        if ($user == null) {
            $error = "User does not exist";
            $this->set('error', $error);
            $this->viewBuilder()->setOption('serialize', ['error']); //to output based on format passed

        } else {
            $this->set('user', $user);
            $this->viewBuilder()->setOption('serialize', ['user']); //to output based on format passed
        }
    }
public function delete($petid)
{
    $this->request->allowMethod(['delete']); // only allow DELETE

    $message = '';

    // Fetch the Pets table
    $PetsTable = $this->fetchTable('Pets');

    try {
        $pet = $PetsTable->get($petid); // fetch pet
        if ($PetsTable->delete($pet)) {
            $message = 'Deleted';
        } else {
            $message = 'Error deleting pet';
        }
    } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
        $message = 'Pet not found';
    }

    $this->set('message', $message);
    $this->viewBuilder()->setOption('serialize', ['message']);
}



public function add()
{
    $this->request->allowMethod(['post']);
    $petsTable = $this->fetchTable('Pets');
    $data = $this->request->getData();

    // Use the same working logic as your app
    $uploadedFile = $data['pet_image'] ?? null;
    if ($uploadedFile instanceof \Laminas\Diactoros\UploadedFile && $uploadedFile->getError() === UPLOAD_ERR_OK) {
        // Stream uploaded file directly into DB BLOB
        $data['pet_image'] = file_get_contents($uploadedFile->getStream()->getMetadata('uri'));
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Pet image is required',
        ];
        $this->set(compact('response'));
        $this->viewBuilder()->setOption('serialize', ['response']);
        return;
    }

    // Create and save entity
    $newPet = $petsTable->newEntity($data);
    $petsTable->save($newPet);

    // Return JSON
    $this->set([
        'response' => [
            'status' => 'success',
            'message' => 'Pet added successfully',
            'pet' => $newPet
        ]
    ]);
    $this->viewBuilder()->setOption('serialize', ['response']);
}



}
