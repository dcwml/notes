<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateCategoryTable extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('category', [
            'comment' => '笔记分类（文件夹）',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci'
        ]);
        $table->addColumn('user_id', 'integer', ['limit' => 7, 'default' => 0, 'comment' => '用户ID'])
            ->addColumn('parent_id', 'integer', ['limit' => 7, 'default' => 0, 'comment' => '父级ID'])
            ->addColumn('name', 'string', ['limit' => 255, 'default' => '', 'comment' => '标题'])
            ->addColumn('create_time', 'datetime', ['comment' => '创建时间'])
            ->addColumn('update_time', 'datetime', ['comment' => '更新时间'])
            ->create();
    }
}
