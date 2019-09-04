<?php

use Illuminate\Database\Seeder;

class AdminTablesSeeder extends Seeder
{

    protected $user = 'admin';

    // 密码：admin
    protected $password = '$2y$10$/GLygvCZRg8GEA7u6XhzO.BVJeOUTgcT0V2cPpQvxlVB.ISVGv6tm';

    protected $role = 'administrator';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createAdminUsers();
        // 已交由单独的seeder处理
        //$this->createAdminPermissions();
        $this->createAdminRoles();
        $this->assignPermissionsToSuperRole();
        $this->assignPermissionsToSuperUser();
        $this->assignRoleToUser();
        $this->createMenus();
    }

    private function createAdminUsers()
    {
        $data = [
            'username' => $this->user,
            'password' => $this->password,
            'name' => $this->user,
        ];
        DB::table(config('admin.database.users_table'))->insert($data);
    }

    private function createAdminPermissions()
    {
        $data = [
            'name' => '所有权限',
            'slug' => '*',
            'http_path' => '*',
        ];
        DB::table(config('admin.database.permissions_table'))->insert($data);

        // 根据现有的路由列表创建权限
        $data = [];
        $routes = Route::getRoutes();
        foreach ($routes as $route) {
            // 排除调试路由，比如 debugbar
            if (substr($route->uri,0, 5) !== 'admin') {
                continue;
            }
            $routeName = $route->getName() ?: $route->uri;
            $method = is_array($route->methods) ? $route->methods[0] : $route->methods;
            $name = $method . '_' . $routeName;
            $data[] = [
                'name' => $name,
                'slug' => $routeName,
                'http_method' =>$method,
                'http_path' => str_replace('admin', '', $route->uri),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table(config('admin.database.permissions_table'))->insert($data);
    }

    private function createAdminRoles()
    {
        $data = [
            [
                'name' => '超级管理员',
                'slug' => $this->role,
            ],
            [
                'name' => '管理员',
                'slug' => 'manager',
            ],
            // 是否播放未审核项目声音通知
            [
                'name' => '维修审核通知',
                'slug' => 'repair-review-notice',
            ],
            // 是否播放未打印项目声音通知
            [
                'name' => '维修打印通知',
                'slug' => 'repair-print-notice',
            ],
        ];
        DB::table(config('admin.database.roles_table'))->insert($data);
    }

    private function assignPermissionsToSuperRole()
    {
        $role = DB::table(config('admin.database.roles_table'))
            ->where('name', '超级管理员')
            ->first();

        $permission = DB::table(config('admin.database.permissions_table'))
            ->where('slug', '*')
            ->first();

        DB::table(config('admin.database.role_permissions_table'))
            ->insert([
                'role_id' => $role->id,
                'permission_id' => $permission->id,
            ]);
    }

    private function assignPermissionsToSuperUser()
    {
        $user = DB::table(config('admin.database.users_table'))
            ->where('name',$this->user)
            ->first();

        $permission = DB::table(config('admin.database.permissions_table'))
            ->where('slug', '*')
            ->first();

        DB::table(config('admin.database.user_permissions_table'))
            ->insert([
                'user_id' => $user->id,
                'permission_id' => $permission->id,
            ]);
    }

    private function createMenus()
    {
        $data = [
            [
                'parent_id' => 0,
                'title' => '居住',
                'icon' => 'fa-male',
                'uri' => '/',
                'order' => 1,
            ],
            [
                'parent_id' => 0,
                'title' => '费用',
                'icon' => 'fa-yen',
                'uri' => 'bills',
                'order' => 2,
            ],
            [
                'parent_id' => 0,
                'title' => '维修',
                'icon' => 'fa-wrench',
                'uri' => '/',
                'order' => 3,
            ],
            [
                'parent_id' => 0,
                'title' => '系统设置',
                'icon' => 'fa-cogs',
                'uri' => '/',
                'order' => 4,
            ],

        ];
        DB::table(config('admin.database.menu_table'))
            ->insert($data);

        $liveId = DB::table(config('admin.database.menu_table'))
            ->where('title', '居住')
            ->value('id');

        $lives = [
            [
                'parent_id' => $liveId,
                'title' => '居住信息',
                'icon' => 'fa-circle-o',
                'uri' => 'lives',
                'order' => '1',
            ],
            [
                'parent_id' => $liveId,
                'title' => '房间',
                'icon' => 'fa-circle-o',
                'uri' => 'rooms',
                'order' => '2',
            ],
            [
                'parent_id' => $liveId,
                'title' => '人员',
                'icon' => 'fa-circle-o',
                'uri' => 'people',
                'order' => '3',
            ],
            [
                'parent_id' => $liveId,
                'title' => '记录',
                'icon' => 'fa-circle-o',
                'uri' => 'records',
                'order' => '4',
            ],
            [
                'parent_id' => $liveId,
                'title' => '类型',
                'icon' => 'fa-circle-o',
                'uri' => 'types',
                'order' => '5',
            ],
            [
                'parent_id' => $liveId,
                'title' => '类型变动',
                'icon' => 'fa-circle-o',
                'uri' => 'type_histories',
                'order' => '6',
            ],
            [
                'parent_id' => $liveId,
                'title' => '续签记录',
                'icon' => 'fa-circle-o',
                'uri' => 'renewals',
                'order' => '7',
            ],
        ];
        DB::table(config('admin.database.menu_table'))
            ->insert($lives);

        $billId = DB::table(config('admin.database.menu_table'))
            ->where('title', '费用')
            ->value('id');

        $bills = [
            [
                'parent_id' => $billId,
                'title' => '缴费',
                'icon' => 'fa-circle-o',
                'uri' => 'bills/charge',
                'order' => 1,
            ],
            [
                'parent_id' => $billId,
                'title' => '明细',
                'icon' => 'fa-circle-o',
                'uri' => 'bills',
                'order' => 2,
            ],
            [
                'parent_id' => $billId,
                'title' => '新增',
                'icon' => 'fa-circle-o',
                'uri' => 'bills/create',
                'order' => 3,
            ],
            [
                'parent_id' => $billId,
                'title' => '导入',
                'icon' => 'fa-circle-o',
                'uri' => 'bills/import',
                'order' => 4,
            ],
            [
                'parent_id' => $billId,
                'title' => '统计',
                'icon' => 'fa-circle-o',
                'uri' => 'bills/statistics',
                'order' => 5,
            ],
            [
                'parent_id' => $billId,
                'title' => '类型',
                'icon' => 'fa-circle-o',
                'uri' => 'bill_types',
                'order' => 6,
            ],
        ];
        DB::table(config('admin.database.menu_table'))
            ->insert($bills);


        $repairId = DB::table(config('admin.database.menu_table'))
            ->where('title', '维修')
            ->value('id');

        $repairs = [
            [
                'parent_id' => $repairId,
                'title' => '报修',
                'icon' => 'fa-circle-o',
                'uri' => 'repairs/create',
                'order' => 1,
            ],
            [
                'parent_id' => $repairId,
                'title' => '未审核项目',
                'icon' => 'fa-circle-o',
                'uri' => 'repairs/unreviewed',
                'order' => 2,
            ],
            [
                'parent_id' => $repairId,
                'title' => '未打印项目',
                'icon' => 'fa-circle-o',
                'uri' => 'repairs/unprinted',
                'order' => 3,
            ],
            [
                'parent_id' => $repairId,
                'title' => '未完工项目',
                'icon' => 'fa-circle-o',
                'uri' => 'repairs/unfinished',
                'order' => 4,
            ],
            [
                'parent_id' => $repairId,
                'title' => '已完工项目',
                'icon' => 'fa-circle-o',
                'uri' => 'repairs/finished',
                'order' => 5,
            ],
            [
                'parent_id' => $repairId,
                'title' => '未通过项目',
                'icon' => 'fa-circle-o',
                'uri' => 'repairs/unpassed',
                'order' => 6,
            ],
            [
                'parent_id' => $repairId,
                'title' => '工种',
                'icon' => 'fa-circle-o',
                'uri' => 'repair_types',
                'order' => 7,
            ],
            [
                'parent_id' => $repairId,
                'title' => '材料',
                'icon' => 'fa-circle-o',
                'uri' => 'repair_items',
                'order' => 8,
            ],

        ];
        DB::table(config('admin.database.menu_table'))
            ->insert($repairs);


        $configId = DB::table(config('admin.database.menu_table'))
            ->where('title', '系统设置')
            ->value('id');

        $configs = [
            [
                'parent_id' => $configId,
                'title' => '用户',
                'icon' => 'fa-users',
                'uri' => 'auth/users',
                'order' => 1,
            ],
            [
                'parent_id' => $configId,
                'title' => '角色',
                'icon' => 'fa-user',
                'uri' => 'auth/roles',
                'order' => 2,
            ],
            [
                'parent_id' => $configId,
                'title' => '权限',
                'icon' => 'fa-ban',
                'uri' => 'auth/permissions',
                'order' => 3,
            ],
            [
                'parent_id' => $configId,
                'title' => '系统菜单',
                'icon' => 'fa-bars',
                'uri' => 'auth/menu',
                'order' => 4,
            ],
            [
                'parent_id' => $configId,
                'title' => '操作记录',
                'icon' => 'fa-history',
                'uri' => 'auth/logs',
                'order' => 5,
            ],
            [
                'parent_id' => $configId,
                'title' => '备份',
                'icon' => 'fa-copy',
                'uri' => 'backup',
                'order' => 6,
            ],
        ];
        DB::table(config('admin.database.menu_table'))
            ->insert($configs);
    }

    private function assignRoleToUser()
    {
        $adminRole = DB::table(config('admin.database.roles_table'))->where('slug', 'administrator')->first();
        $adminUser = DB::table(config('admin.database.users_table'))->first();
        $data = [
            'role_id' => $adminRole->id,
            'user_id' => $adminUser->id,
        ];
        DB::table(config('admin.database.role_users_table'))->insert($data);
    }
}
