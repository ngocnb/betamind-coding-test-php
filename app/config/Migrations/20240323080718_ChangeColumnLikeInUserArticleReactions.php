<?php
declare (strict_types = 1);

use Migrations\AbstractMigration;

class ChangeColumnLikeInUserArticleReactions extends AbstractMigration {
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function up(): void {
        $table = $this->table('user_article_reactions');
        $table->renameColumn('like', 'reaction')->save();
        $table->changeColumn('reaction', 'integer', [
            'default' => 0,
        ])->save();
    }

    public function down(): void {
        $table = $this->table('user_article_reactions');
        $table->renameColumn('reaction', 'like')->save();
        $table->changeColumn('like', 'boolean', [
            'default' => false,
        ])->save();
    }
}
