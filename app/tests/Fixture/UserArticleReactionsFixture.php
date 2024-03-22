<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UserArticleReactionsFixture
 */
class UserArticleReactionsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'article_id' => 1,
                'like' => 1,
                'created_at' => '2024-03-22 04:20:43',
                'updated_at' => '2024-03-22 04:20:43',
            ],
        ];
        parent::init();
    }
}
