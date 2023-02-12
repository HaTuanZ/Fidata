<?php

namespace App\Console\Commands;

use Botble\ACL\Models\User;
use Illuminate\Console\Command;

class UserAddPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add {email} {permission*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User add permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        $permissions = $this->argument('permission');
        $user = User::where('email', $email)->first();
        if(!empty($user)){
            foreach($permissions as $permission){
                $user->addPermission($permission);
            }
            $user->save();
        }
        return 0;
    }
}
