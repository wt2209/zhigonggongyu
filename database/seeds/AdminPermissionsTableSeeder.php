<?php

use Illuminate\Database\Seeder;

class AdminPermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('admin_permissions')->delete();
        
        \DB::table('admin_permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '所有权限',
                'slug' => '*',
                'http_method' => NULL,
                'http_path' => '*',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'name' => '系统用户管理',
                'slug' => 'users.all',
                'http_method' => 'GET,POST,PUT,DELETE',
                'http_path' => '/auth/users*',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:22:30',
            ),
            2 =>
            array (
                'id' => 9,
                'name' => '系统角色管理',
                'slug' => 'roles.all',
                'http_method' => 'GET,POST,PUT,DELETE',
                'http_path' => '/auth/roles*',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:23:22',
            ),
            3 =>
            array (
                'id' => 16,
                'name' => '系统权限管理',
                'slug' => 'permissions.all',
                'http_method' => 'GET,POST,PUT,DELETE',
                'http_path' => '/auth/permissions*',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:24:12',
            ),
            4 =>
            array (
                'id' => 23,
                'name' => '系统菜单管理',
                'slug' => 'menu.all',
                'http_method' => 'GET,POST,PUT,DELETE',
                'http_path' => '/auth/menu*',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:24:56',
            ),
            5 =>
            array (
                'id' => 29,
                'name' => '系统日志管理',
                'slug' => 'logs.all',
                'http_method' => 'GET,POST,PUT,DELETE',
                'http_path' => '/auth/logs*',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:26:01',
            ),
            6 =>
            array (
                'id' => 31,
                'name' => '登录退出',
                'slug' => 'auth.login',
                'http_method' => 'GET,POST',
                'http_path' => '/auth/login
/auth/logout',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:29:23',
            ),
            7 =>
            array (
                'id' => 34,
                'name' => '系统个人设置',
                'slug' => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path' => '/auth/setting',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:28:19',
            ),
            8 =>
            array (
                'id' => 36,
                'name' => '首页',
                'slug' => 'home.index',
                'http_method' => 'GET',
                'http_path' => NULL,
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:29:48',
            ),
            9 =>
            array (
                'id' => 37,
                'name' => '全局搜索',
                'slug' => 'home.search',
                'http_method' => 'GET',
                'http_path' => '/search',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:30:01',
            ),
            10 =>
            array (
                'id' => 38,
                'name' => '房间首页',
                'slug' => 'rooms.index',
                'http_method' => 'GET',
                'http_path' => '/rooms',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:30:19',
            ),
            11 =>
            array (
                'id' => 39,
                'name' => '房间修改',
                'slug' => 'rooms.edit',
                'http_method' => 'GET,PUT',
                'http_path' => '/rooms/{room}/edit
/rooms/{room}',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:30:53',
            ),
            12 =>
            array (
                'id' => 41,
                'name' => '人员首页',
                'slug' => 'people.index',
                'http_method' => 'GET',
                'http_path' => '/people',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:32:38',
            ),
            13 =>
            array (
                'id' => 42,
            'name' => '人员(by身份证)',
                'slug' => 'people.identify',
                'http_method' => 'GET',
                'http_path' => '/people/by_identify',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:28:29',
            ),
            14 =>
            array (
                'id' => 43,
                'name' => '记录首页',
                'slug' => 'records.index',
                'http_method' => 'GET',
                'http_path' => '/records',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:33:05',
            ),
            15 =>
            array (
                'id' => 44,
                'name' => '类型首页',
                'slug' => 'types.index',
                'http_method' => 'GET',
                'http_path' => '/types',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:33:19',
            ),
            16 =>
            array (
                'id' => 45,
                'name' => '类型创建',
                'slug' => 'types.create',
                'http_method' => 'GET,POST',
                'http_path' => '/types/create
/types',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:58:49',
            ),
            17 =>
            array (
                'id' => 47,
                'name' => '类型修改',
                'slug' => 'types.edit',
                'http_method' => 'GET,PUT',
                'http_path' => '/types/{type}/edit
/types/{type}',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 15:59:29',
            ),
            18 =>
            array (
                'id' => 49,
                'name' => '类型删除',
                'slug' => 'types.destroy',
                'http_method' => 'DELETE',
                'http_path' => '/types/{type}',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:00:15',
            ),
            19 =>
            array (
                'id' => 50,
                'name' => '类型变动首页',
                'slug' => 'type_histories.index',
                'http_method' => 'GET',
                'http_path' => '/type_histories',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:00:40',
            ),
            20 =>
            array (
                'id' => 51,
                'name' => '居住首页',
                'slug' => 'lives.index',
                'http_method' => 'GET',
                'http_path' => '/lives',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:01:08',
            ),
            21 =>
            array (
                'id' => 52,
                'name' => '居住修改',
                'slug' => 'lives.edit',
                'http_method' => 'GET,PUT',
                'http_path' => '/lives/{id}/edit
/lives/{id}',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:01:41',
            ),
            22 =>
            array (
                'id' => 54,
                'name' => '居住入住',
                'slug' => 'lives.create',
                'http_method' => 'GET,POST',
                'http_path' => '/lives/create
/lives',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:04:29',
            ),
            23 =>
            array (
                'id' => 56,
                'name' => '居住调房',
                'slug' => 'lives.change',
                'http_method' => 'GET,PUT',
                'http_path' => '/lives/{id}/change
/lives/{id}/move',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:05:12',
            ),
            24 =>
            array (
                'id' => 57,
                'name' => '居住续签',
                'slug' => 'lives.prolong',
                'http_method' => 'GET,PUT',
                'http_path' => '/lives/{id}/prolong
/lives/{id}/renew',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:05:37',
            ),
            25 =>
            array (
                'id' => 59,
                'name' => '居住退房',
                'slug' => 'lives.quit',
                'http_method' => 'PUT',
                'http_path' => '/lives/{id}/quit',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:06:13',
            ),
            26 =>
            array (
                'id' => 61,
                'name' => '续签记录',
                'slug' => 'renewals.index',
                'http_method' => 'GET',
                'http_path' => '/renewals',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:06:57',
            ),
            27 =>
            array (
                'id' => 62,
                'name' => '通知',
                'slug' => 'notices.notice',
                'http_method' => 'GET',
                'http_path' => '/notices*',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:07:47',
            ),
            28 =>
            array (
                'id' => 65,
                'name' => '维修材料首页',
                'slug' => 'repair_items.index',
                'http_method' => 'GET',
                'http_path' => '/repair_items',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:08:23',
            ),
            29 =>
            array (
                'id' => 66,
                'name' => '维修材料创建',
                'slug' => 'repair_items.create',
                'http_method' => 'GET,POST',
                'http_path' => '/repair_items/create
/repair_items',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:09:04',
            ),
            30 =>
            array (
                'id' => 68,
                'name' => '维修材料修改',
                'slug' => 'repair_items.edit',
                'http_method' => 'GET,PUT',
                'http_path' => '/repair_items/{repair_item}/edit
/repair_items/{repair_item}',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:09:52',
            ),
            31 =>
            array (
                'id' => 70,
                'name' => '维修工种首页',
                'slug' => 'repair_types.index',
                'http_method' => 'GET',
                'http_path' => '/repair_types',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:10:29',
            ),
            32 =>
            array (
                'id' => 71,
                'name' => '维修工种创建',
                'slug' => 'repair_types.create',
                'http_method' => 'GET,POST',
                'http_path' => '/repair_types/create
/repair_types',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:11:01',
            ),
            33 =>
            array (
                'id' => 73,
                'name' => '维修工种修改',
                'slug' => 'repair_types.edit',
                'http_method' => 'GET,PUT',
                'http_path' => '/repair_types/{repair_type}/edit
/repair_types/{repair_type}',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:11:42',
            ),
            34 =>
            array (
                'id' => 75,
                'name' => '维修报修',
                'slug' => 'repairs.create',
                'http_method' => 'GET,POST',
                'http_path' => '/repairs/create
/repairs',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:13:43',
            ),
            35 =>
            array (
                'id' => 76,
                'name' => '维修明细',
                'slug' => 'repairs.show',
                'http_method' => 'GET',
                'http_path' => '/repairs/{id}/show',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:12:58',
            ),
            36 =>
            array (
                'id' => 77,
                'name' => '维修修改',
                'slug' => 'repairs.edit',
                'http_method' => 'GET,PUT',
                'http_path' => '/repairs/{id}/edit
/repairs/{id}/update',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:13:25',
            ),
            37 =>
            array (
                'id' => 80,
                'name' => '维修未审核项',
                'slug' => 'repairs.unreviewed',
                'http_method' => 'GET',
                'http_path' => '/repairs/unreviewed',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:14:48',
            ),
            38 =>
            array (
                'id' => 81,
                'name' => '维修未通过项',
                'slug' => 'repairs.unpassed',
                'http_method' => 'GET',
                'http_path' => '/repairs/unpassed',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:15:03',
            ),
            39 =>
            array (
                'id' => 82,
                'name' => '维修未打印项',
                'slug' => 'repairs.unprinted',
                'http_method' => 'GET',
                'http_path' => '/repairs/unprinted',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:15:16',
            ),
            40 =>
            array (
                'id' => 83,
                'name' => '维修未完工项',
                'slug' => 'repairs.unfinished',
                'http_method' => 'GET',
                'http_path' => '/repairs/unfinished',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:15:45',
            ),
            41 =>
            array (
                'id' => 84,
                'name' => '维修已完工项',
                'slug' => 'repairs.finished',
                'http_method' => 'GET',
                'http_path' => '/repairs/finished',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:15:58',
            ),
            42 =>
            array (
                'id' => 85,
                'name' => '维修审核',
                'slug' => 'repairs.review',
                'http_method' => 'GET,PUT',
                'http_path' => '/repairs/{id}/review
/repairs/review',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:16:28',
            ),
            43 =>
            array (
                'id' => 87,
                'name' => '维修完工',
                'slug' => 'repairs.finish',
                'http_method' => 'GET,PUT',
                'http_path' => '/repairs/{id}/finish
/repairs/finish',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:17:26',
            ),
            44 =>
            array (
                'id' => 89,
                'name' => '维修批量审核',
                'slug' => 'repairs.batch-review',
                'http_method' => 'PUT',
                'http_path' => '/repairs/batch_review',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:17:43',
            ),
            45 =>
            array (
                'id' => 90,
                'name' => '维修批量完工',
                'slug' => 'repairs.batch-finish',
                'http_method' => 'PUT',
                'http_path' => '/repairs/batch_finish',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:18:01',
            ),
            46 =>
            array (
                'id' => 91,
                'name' => '维修打印',
                'slug' => 'repairs.print',
                'http_method' => 'PUT',
                'http_path' => '/repairs/{id}/print',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:18:11',
            ),
            47 =>
            array (
                'id' => 92,
                'name' => '维修材料',
                'slug' => 'repairs.material',
                'http_method' => 'GET,POST',
                'http_path' => '/repairs/{id}/material
/repairs/material',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:18:35',
            ),
            48 =>
            array (
                'id' => 94,
                'name' => '维修删除',
                'slug' => 'repairs.destroy',
                'http_method' => 'DELETE',
                'http_path' => '/repairs/{id}',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:18:57',
            ),
            49 =>
            array (
                'id' => 95,
                'name' => '费用首页',
                'slug' => 'bills.index',
                'http_method' => 'GET',
                'http_path' => '/bills',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:19:09',
            ),
            50 =>
            array (
                'id' => 96,
                'name' => '费用修改',
                'slug' => 'bills.edit',
                'http_method' => 'GET,PUT',
                'http_path' => '/bills/{id}/edit
/bills/{id}',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:23:14',
            ),
            51 =>
            array (
                'id' => 97,
                'name' => '费用明细',
                'slug' => 'bills.show',
                'http_method' => 'GET',
                'http_path' => '/bills/{id}/show',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 13:50:36',
            ),
            52 =>
            array (
                'id' => 98,
                'name' => '费用新增',
                'slug' => 'bills.create',
                'http_method' => 'GET,POST',
                'http_path' => '/bills/create
/bills',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:22:55',
            ),
            53 =>
            array (
                'id' => 101,
                'name' => '费用删除',
                'slug' => 'bills.destroy',
                'http_method' => 'DELETE',
                'http_path' => '/bills/{id}',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 13:50:36',
            ),
            54 =>
            array (
                'id' => 102,
                'name' => '费用导入',
                'slug' => 'bills.import',
                'http_method' => 'GET,POST',
                'http_path' => '/bills/import*',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:21:26',
            ),
            55 =>
            array (
                'id' => 105,
                'name' => '费用缴费',
                'slug' => 'bills.charge',
                'http_method' => 'GET,PUT',
                'http_path' => '/bills/charge',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:22:24',
            ),
            56 =>
            array (
                'id' => 107,
                'name' => '费用统计',
                'slug' => 'bills.statistics',
                'http_method' => 'GET',
                'http_path' => '/bills/statistics',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 13:50:36',
            ),
            57 =>
            array (
                'id' => 108,
                'name' => '费用类型首页',
                'slug' => 'bill_types.index',
                'http_method' => 'GET',
                'http_path' => '/bill_types',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 13:50:36',
            ),
            58 =>
            array (
                'id' => 109,
                'name' => '费用类型创建',
                'slug' => 'bill_types.create',
                'http_method' => 'GET,POST',
                'http_path' => '/bill_types/create
/bill_types',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:23:46',
            ),
            59 =>
            array (
                'id' => 111,
                'name' => '费用类型修改',
                'slug' => 'bill_types.edit',
                'http_method' => 'GET,PUT',
                'http_path' => '/bill_types/{bill_type}/edit
/bill_types/{bill_type}',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:24:09',
            ),
            60 =>
            array (
                'id' => 112,
                'name' => '费用类型禁用',
                'slug' => 'bill_types.disable',
                'http_method' => 'PATCH',
                'http_path' => '/bill_types/disable',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:24:09',
            ),
            61 =>
            array (
                'id' => 113,
                'name' => '费用类型启用',
                'slug' => 'bill_types.enable',
                'http_method' => 'PATCH',
                'http_path' => '/bill_types/enable',
                'created_at' => '2018-10-10 13:50:36',
                'updated_at' => '2018-10-10 16:24:09',
            ),
            62 =>
            array (
                'id' => 114,
                'name' => '备份',
                'slug' => 'backup.index',
                'http_method' => 'GET,POST,DELETE',
                'http_path' => '/backup*',
                'created_at' => '2018-10-13 17:39:49',
                'updated_at' => '2018-10-13 17:40:03',
            ),
        ));
        
        
    }
}