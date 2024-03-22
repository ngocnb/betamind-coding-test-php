<?php
use Migrations\AbstractMigration;

class CreateUserArticleReactions extends AbstractMigration {
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void{
        $table = $this->table('user_article_reactions');
        $table->addColumn('user_id', 'integer')
            ->addColumn('article_id', 'integer')
            ->addColumn('reaction', 'boolean', ['default' => false])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime')
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
