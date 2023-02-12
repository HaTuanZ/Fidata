<?php

namespace App\Console\Commands;

use Botble\ACL\Models\Role;
use Illuminate\Console\Command;

class RoleAddPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:role:add {name} {permission*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Role add permissions';

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
        $name = $this->argument('name');
        $permissions = $this->argument('permission');
        $role = Role::where('name', $name)->first();
        if(!empty($role)){
            foreach($permissions as $permission){
                $role->addPermission($permission);
            }
            $role->save();
        }
        return 0;
    }
}
