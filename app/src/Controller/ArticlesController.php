<?php
declare (strict_types = 1);

namespace App\Controller;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 * @method \App\Model\Entity\Article[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ArticlesController extends AppController {
    /**
     * @param \Cake\Event\EventInterface $event
     * @return \Cake\Http\Response|void|null
     */
    public function beforeFilter(\Cake\Event\EventInterface $event) {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['index', 'view']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index() {
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $articles = $this->paginate($this->Articles, [
            'limit' => 10,
            'order' => ['Articles.id' => 'desc'],
        ]);

        $this->set('success', true);
        $this->set(compact('articles'));
        $this->viewBuilder()->setOption('serialize', ['success', 'articles']);
    }

    /**
     * View method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null) {
        $article = $this->Articles->get($id, [
            'contain' => ['Users', 'UserArticleReactions'],
        ]);

        $this->set('success', true);
        $this->set(compact('article'));
        $this->viewBuilder()->setOption('serialize', ['success', 'article']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add() {
        $this->request->allowMethod(['post']);

        $article            = $this->Articles->newEmptyEntity();
        $data               = $this->request->getData();
        $data['user_id']    = $this->Authentication->getIdentityData('id');
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $article            = $this->Articles->patchEntity($article, $data);

        if ($this->Articles->save($article)) {
            $this->set(
                [
                    'success' => true,
                    'data'    => $article->toArray(),
                ]
            );
        } else {
            $this->set(
                [
                    'success' => false,
                    'errors'  => $article->getErrors(),
                ]
            );
        }

        $this->viewBuilder()->setOption('serialize', ['success', 'data', 'errors']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null) {
        $this->request->allowMethod(['put', 'patch']);

        $article = $this->Articles->get($id, [
            'contain' => [],
        ]);

        // verify author
        if ($article->user_id !== $this->Authentication->getIdentityData('id')) {
            $this->set(
                [
                    'success' => false,
                    'errors'  => 'You are not authorized to edit this article.',
                ]
            );
            $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
            return;
        }

        $data               = $this->request->getData();
        $data['updated_at'] = date('Y-m-d H:i:s');
        $article            = $this->Articles->patchEntity($article, $this->request->getData());
        if ($this->Articles->save($article)) {
            $this->set(
                [
                    'success' => true,
                    'data'    => $article->toArray(),
                ]
            );
        } else {
            $this->set(
                [
                    'success' => false,
                    'errors'  => $article->getErrors(),
                ]
            );
        }
        $this->viewBuilder()->setOption('serialize', ['success', 'data', 'errors']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Article id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null) {
        $this->request->allowMethod(['delete']);
        $article = $this->Articles->get($id, [
            'contain' => [],
        ]);

        // verify author
        if ($article->user_id !== $this->Authentication->getIdentityData('id')) {
            $this->set(
                [
                    'success' => false,
                    'errors'  => 'You are not authorized to delete this article.',
                ]
            );
            $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
            return;
        }

        if ($this->Articles->delete($article)) {
            $this->set(
                [
                    'success' => true,
                ]
            );
        } else {
            $this->set(
                [
                    'success' => true,
                    'errors'  => $article->getErrors(),
                ]
            );
        }

        $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
    }
}
