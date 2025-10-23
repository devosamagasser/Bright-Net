<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\AccessControl\Domain\Models\CompanyUser;
use App\Modules\Companies\Domain\Models\Company;
use App\Modules\Companies\Domain\ValueObjects\CompanyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use function collect;

class AccessControlSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $platformPermissions = collect([
            'platform.manage.companies',
            'platform.manage.admins',
        ])->map(fn (string $name) => Permission::firstOrCreate([
            'name' => $name,
            'guard_name' => 'platform',
        ]));

        $platformOwnerRole = Role::firstOrCreate([
            'name' => 'platform-owner',
            'guard_name' => 'platform',
        ]);
        $platformOwnerRole->syncPermissions($platformPermissions);

        $platformAdminRole = Role::firstOrCreate([
            'name' => 'platform-admin',
            'guard_name' => 'platform',
        ]);
        $platformAdminRole->syncPermissions($platformPermissions->where('name', 'platform.manage.admins'));

        $platformOwner = User::query()->firstOrCreate(
            ['email' => 'owner@brightnet.test'],
            [
                'name' => 'Platform Owner',
                'password' => Hash::make('password'),
                'is_owner' => true,
            ]
        );
        $platformOwner->syncRoles($platformOwnerRole);

        $supplierCompany = Company::query()->firstOrCreate(
            ['name' => 'Core Supplier'],
            [
                'description' => 'Default supplier company for development seeds.',
                'type' => CompanyType::SUPPLIER,
            ]
        );

        $supplierPermissions = collect([
            'supplier.manage.profile',
            'supplier.manage.staff',
        ])->map(fn (string $name) => Permission::firstOrCreate([
            'name' => $name,
            'guard_name' => 'company',
        ]));

        $supplierOwnerRole = Role::firstOrCreate([
            'name' => 'supplier-owner',
            'guard_name' => 'company',
        ]);
        $supplierOwnerRole->syncPermissions($supplierPermissions);

        $supplierAdminRole = Role::firstOrCreate([
            'name' => 'supplier-admin',
            'guard_name' => 'company',
        ]);
        $supplierAdminRole->syncPermissions($supplierPermissions->where('name', 'supplier.manage.profile'));

        $supplierOwner = CompanyUser::query()->firstOrCreate(
            ['email' => 'supplier.owner@brightnet.test'],
            [
                'company_id' => $supplierCompany->getKey(),
                'name' => 'Supplier Owner',
                'password' => Hash::make('password'),
                'is_owner' => true,
            ]
        );
        $supplierOwner->syncRoles($supplierOwnerRole);
    }
}
