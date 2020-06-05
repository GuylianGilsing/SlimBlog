<?php


use Phinx\Seed\AbstractSeed;

class UserSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'name' => "admin",
                'email' => "admin@example.com",
                'password' => password_hash('admin', PASSWORD_BCRYPT),
            ]
        ];

        $usersTable = $this->table('users');
        $usersTable->insert($data)
                    ->saveData();
    }
}
