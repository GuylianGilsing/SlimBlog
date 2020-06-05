<?php

use Phinx\Migration\AbstractMigration;

class UsersMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        // Only create the users table when it does not exist.
        if(!$this->hasTable('users'))
        {
            $table = $this->table('users', ['id' => 'id', 'primary_key' => 'id', 'signed' => false]);
            $table->addColumn('name', 'string', ['limit' => 120])
                ->addColumn('email', 'string', ['limit' => 200])
                ->addColumn('password', 'text')
                ->addIndex(['name', 'email'], ['unique' => true])
                ->create();
        }
    }

    public function down()
    {
        if($this->hasTable('users'))
            $this->table('users')->drop();
    }
}
