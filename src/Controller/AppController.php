<?php
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

class AppController extends Controller
{
public $loggedUser;
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
       $this->loadComponent('Authentication.Authentication');
        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/5/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
          //if the user is logged in...
        if ($user = $this->Authentication->getIdentity()) {
            //pass this to all the views as $loggedUser
            $this->set('loggedUser', $user);

            //pass this to ALL controllers as $this->loggedUser
            $this->loggedUser = $user;

            //pr($user);
        }

        //pr($_SESSION);
    }
 public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        // Nothing else here — no redirect, no global unauthenticated actions
    }

}