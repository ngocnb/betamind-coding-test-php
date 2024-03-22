<?php
declare (strict_types = 1);

use Cake\Auth\DefaultPasswordHasher;
use Migrations\AbstractSeed;

/**
 * Users seed.
 */
class UsersSeed extends AbstractSeed {
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
        $faker      = Faker\Factory::create();
        $connection = \Cake\Datasource\ConnectionManager::get('default')->selectQuery();
        $count      = $connection->select('id')->from('users')->execute()->count();
        if ($count > 0) {
            return;
        }

        $data = [
            [
                'email'      => 'admin@betamind.test',
                'password'   => (new DefaultPasswordHasher())->hash('P@ssw0rd'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        // generate fake data
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'email'      => $faker->email,
                'password'   => (new DefaultPasswordHasher())->hash($faker->password(8)),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        $table = $this->table('users');
        $table->insert($data)->save();
    }
}
