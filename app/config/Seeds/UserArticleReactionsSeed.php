<?php

use App\Model\Entity\UserArticleReaction;
use Cake\Datasource\ConnectionManager;
use Migrations\AbstractSeed;

/**
 * UserArticleReactions seed.
 */
class UserArticleReactionsSeed extends AbstractSeed {
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run(): void {
        $faker = Faker\Factory::create();

        $connection = ConnectionManager::get('default')->selectQuery();
        $reactions  = $connection->select('id')->from('user_article_reactions')->execute()->count();
        if ($reactions > 0) {
            $this->_updateArticlesLikeCount($connection);
            return;
        }

        $data = [];

        // generate fake reactions
        for ($i = 0; $i < 1000; $i++) {
            $data[] = [
                'user_id'    => $faker->numberBetween(2, 11),
                'article_id' => $faker->numberBetween(1, 100),
                'reaction'   => UserArticleReaction::REACTION_LIKE,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        // remove duplicates by user_id and article_id
        $data = $this->_removeDuplicates($data);

        $table = $this->table('user_article_reactions');
        $table->insert($data)->save();

        $this->_updateArticlesLikeCount($connection);
    }

    // Function to remove duplicates by combination of user_id and article_id
    private function _removeDuplicates($data) {
        $result = [];
        foreach ($data as $item) {
            $key          = $item['user_id'] . '_' . $item['article_id'];
            $result[$key] = $item;
        }
        return array_values($result);
    }

    private function _updateArticlesLikeCount() {
        $connection = ConnectionManager::get('default');
        $articles   = $connection->execute('SELECT id FROM articles')->fetchAll('assoc');
        foreach ($articles as $article) {
            $likes = $connection->execute('SELECT COUNT(id) FROM user_article_reactions WHERE article_id = :article_id', ['article_id' => $article['id']])->fetchColumn(0);
            $connection->execute('UPDATE articles SET like_count = :like_count WHERE id = :article_id', ['like_count' => $likes, 'article_id' => $article['id']]);
        }
    }
}
