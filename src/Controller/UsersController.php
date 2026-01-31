<?php
namespace App\Controller;

use Cake\Event\EventInterface;

class UsersController extends AppController
{
public function beforeFilter(EventInterface $event): void
{
    parent::beforeFilter($event);

    // Allow unauthenticated users for login and addUser
    $this->Authentication->addUnauthenticatedActions([
        'login', 'add'
    ]);

  
}
    public function index()
    {
        $Users = $this->fetchTable('Users');
        $allUsers = $Users->find()->all();

        // Get currently logged-in user identity
    $identity = $this->Authentication->getIdentity();
    $loggedUser = $identity ?? null;

    // Pass both users and loggedUser to the view
    $this->set(compact('allUsers', 'loggedUser'));
    }

    public function banUser($userId)
    {
        $Users = $this->fetchTable('Users');
        $user = $Users->get($userId);

        if ($user->is_banned) {
            $this->Flash->warning('User is already banned.');
            return $this->redirect(['action' => 'index']);
        }

        $user->is_banned = 1;

        if ($Users->save($user)) {
            $this->Flash->success('User has been banned successfully.');
        } else {
            $this->Flash->error('Failed to ban user. Please try again.');
        }

        return $this->redirect(['action' => 'index']);
    }

   public function login()
{
    $this->request->allowMethod(['get', 'post']);
    $result = $this->Authentication->getResult();

    // Already logged in
    if ($result && $result->isValid()) {

        $user = $result->getData(); // get the logged-in user entity

        // Check if banned
        if ($user->is_banned) {
            $this->Authentication->logout();
            $this->Flash->error('Your account has been banned.');
            return $this->redirect(['action' => 'login']);
        }

        // Successful login, not banned
        return $this->redirect([
            'controller' => 'Pages',
            'action' => 'purrfecthome',
        ]);
    }

    // Login attempt failed
    if ($this->request->is('post') && (!$result || !$result->isValid())) {
        $this->Flash->error('Invalid email or password.');
    }
}
    public function logout()
    {
        $result = $this->Authentication->getResult();

        if ($result && $result->isValid()) {
            $this->Authentication->logout();
        }

        return $this->redirect([
            'controller' => 'Users',
            'action' => 'login'
        ]);
    }

    public function add()
    {
        $Users = $this->fetchTable('Users');

        if ($this->request->is('post')) {
            $user = $Users->newEntity($this->request->getData());

            if ($Users->save($user)) {
                $this->Flash->success('User has been saved!');
                return $this->redirect([ 'controller' => 'Pages','action' => 'purrfecthome']);
            }

            $errors = $user->getErrors();
            $errorMessages = '';

            foreach ($errors as $error) {
                $errorMessages .= ' - ' . array_values($error)[0] . '<br>';
            }

            $this->Flash->error(
                "Error saving user!<br>$errorMessages",
                ['escape' => false]
            );
        }
    }
}
