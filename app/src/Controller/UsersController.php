<?php

declare (strict_types = 1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Security;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController {
    /**
     * @param \Cake\Event\EventInterface $event
     * @return \Cake\Http\Response|void|null
     */
    public function beforeFilter(\Cake\Event\EventInterface $event) {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['login']);
    }
    /**
     *
     */
    public function login() {
        $this->request->allowMethod(['post']);
        $authentication = $this->request->getAttribute('authentication');
        $result         = $authentication->getResult();
        if ($result) {
            $userIdentity = $this->Authentication->getIdentity();

            $user        = $userIdentity->getOriginalData();
            $user->token = $this->generateToken();
            $user        = $this->Users->save($user);
            $user        = $this->Users->get($user->id);

            $this->set(compact('user'));
            $this->viewBuilder()->setOption('serialize', ['user']);
        } else {
            throw new NotFoundException(__('User not found'));
        }
    }

    /**
     * @param int $length
     * @return array|string|string[]|null
     */
    private function generateToken(int $length = 36) {
        $random  = base64_encode(Security::randomBytes($length));
        $cleaned = preg_replace('/[^A-Za-z0-9]/', '', $random);
        return $cleaned;
    }
}