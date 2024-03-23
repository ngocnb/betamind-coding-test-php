<?php

declare (strict_types = 1);

namespace App\Controller;

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
        $this->Authentication->allowUnauthenticated(['login', 'register']);
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
            $user->token = $this->_generateToken();
            $user        = $this->Users->save($user);
            $user        = $this->Users->get($user->id);

            $this->set([
                'success' => true,
                'user'    => $user,
            ]);
            $this->viewBuilder()->setOption('serialize', ['success', 'user']);
        } else {
            $this->set(
                [
                    'success' => false,
                    'errors'  => $result->getErrors(),
                ]
            );
        }
    }

    public function register() {
        $this->request->allowMethod(['post']);
        $data               = $this->request->getData();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $user               = $this->Users->newEntity($data);
        if ($this->Users->save($user)) {
            $this->set(
                [
                    'success' => true,
                    'data'    => $user->toArray(),
                ]
            );
        } else {
            $this->set(
                [
                    'success' => false,
                    'errors'  => $user->getErrors(),
                ]
            );
        }
        $this->viewBuilder()->setOption('serialize', ['success', 'data', 'errors']);
    }

    /**
     * @param int $length
     * @return array|string|string[]|null
     */
    private function _generateToken(int $length = 36) {
        $random  = base64_encode(Security::randomBytes($length));
        $cleaned = preg_replace('/[^A-Za-z0-9]/', '', $random);
        return $cleaned;
    }
}