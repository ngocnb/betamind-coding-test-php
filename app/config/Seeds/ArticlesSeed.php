<?php
declare (strict_types = 1);

use Migrations\AbstractSeed;

/**
 * Articles seed.
 */
class ArticlesSeed extends AbstractSeed {
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
    public function run(): void{
        $faker = Faker\Factory::create();

        $connection = \Cake\Datasource\ConnectionManager::get('default')->selectQuery();
        // get all articles
        $articles = $connection->select('id')->from('articles')->execute()->count();

        if ($articles > 0) {
            return;
        }

        // generate fake articles
        for ($i = 0; $i < 100; $i++) {
            $data[] = [
                'user_id'    => $faker->numberBetween(1, 11),
                'title'      => $faker->sentence(6),
                'body'       => $faker->paragraph(3),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $table = $this->table('articles');
        $table->insert($data)->save();
    }
}
