<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateUserTable extends Migrator
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
        $table = $this->table('user', [
            'comment' => 'users table',
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci'
        ]);
        $table
            ->addColumn( 'name', 'string', [ 'limit' => 50, 'null' => false ] )
            ->addColumn( 'email', 'string', [ 'limit' => 100, 'null' => false ] )
            ->addColumn( 'password', 'string', [ 'limit' => 255, 'null' => false ] )
            ->addColumn( 'status', 'integer', [ 'limit' => 1, 'default' => 0, 'null' => false ] )
            ->addColumn( 'last_login_time', 'datetime' )
            ->addColumn( 'failed_login_count', 'integer', [ 'limit' => 8, 'default' => 0 ] )
            ->addColumn( 'last_failed_login_time', 'datetime' )
            ->addColumn( 'last_login_ip', 'string', [ 'limit' => 50 ] )
            ->addColumn( 'create_time', 'datetime' )
            ->addColumn( 'update_time', 'datetime' )
            ->addColumn( 'info', 'text' )
            ->addIndex( [ 'name' ], [ 'unique' => true ] )
            ->addIndex( [ 'email' ], [ 'unique' => true ] )
            ->create();
    }
}
