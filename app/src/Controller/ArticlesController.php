<?php
declare (strict_types = 1);

namespace App\Controller;
use App\Model\Entity\UserArticleReaction;

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
            'contain' => ['Users' => function ($q) {
                return $q->select(['id', 'email']);
            }],
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
            'contain' => ['Users' => function ($q) {
                return $q->select(['id', 'email']);
            }],
        ]);

        try {
            // get current logged in user
            $userId = $this->Authentication->getIdentityData('id');
            // check if user has already liked the article
            $reacted = $this->Articles->UserArticleReactions->exists(
                [
                    'article_id = ' => $id,
                    'user_id = '    => $userId,
                    'reaction = '   => UserArticleReaction::REACTION_LIKE,
                ]
            );

            $this->set('reacted', $reacted);
            if ($userId === $article->user_id) {
                $this->set('is_author', true);
            } else {
                $this->set('is_author', false);
            }
        } catch (\Exception $e) {
            // user is not logged in
            // do nothing
            $this->set('reacted', false);
            $this->set('is_author', false);
        }

        $this->set('success', true);
        $this->set(compact('article'));
        $this->viewBuilder()->setOption('serialize', ['success', 'article', 'reacted', 'is_author']);
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

    /**
     * React method
     * @param string|null $id Article id.
     *
     */
    public function react($id = null) {
        $this->request->allowMethod(['post']);

        $article = $this->Articles->get($id);

        // check if user has already reacted
        $existingReaction = $this->Articles->UserArticleReactions->find()
            ->where([
                'article_id = ' => $id,
                'user_id = '    => $this->Authentication->getIdentityData('id'),
                'reaction = '   => UserArticleReaction::REACTION_LIKE,
            ])
            ->first();

        if ($existingReaction) {
            $this->set(
                [
                    'success' => false,
                    'errors'  => 'You have already liked this article.',
                ]
            );
            $this->viewBuilder()->setOption('serialize', ['success', 'errors']);
            return;
        }

        // add reaction
        $data['user_id']    = $this->Authentication->getIdentityData('id');
        $data['article_id'] = $id;
        $data['reaction']   = UserArticleReaction::REACTION_LIKE;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $articleReaction    = $this->Articles->UserArticleReactions->newEmptyEntity();
        $articleReaction    = $this->Articles->UserArticleReactions->patchEntity($articleReaction, $data);

        if ($this->Articles->UserArticleReactions->save($articleReaction)) {
            $article->like_count = $this->Articles->UserArticleReactions->find()
                ->where(['article_id = ' => $data['article_id'], 'reaction = ' => UserArticleReaction::REACTION_LIKE])
                ->count();
            $this->Articles->save($article);

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
                    'errors'  => $articleReaction->getErrors(),
                ]
            );
        }

        $this->viewBuilder()->setOption('serialize', ['success', 'data', 'errors']);
    }
}
