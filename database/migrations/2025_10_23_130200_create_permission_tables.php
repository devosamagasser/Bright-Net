<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\PermissionRegistrar;

if (! class_exists('CreatePermissionTables')) {
    return new class extends Migration
    {
        public function up(): void
        {
            $tableNames = config('permission.table_names');
            $columnNames = config('permission.column_names');
            $teams = config('permission.teams');

            if (
                empty($tableNames['roles']) ||
                empty($tableNames['permissions']) ||
                empty($tableNames['model_has_permissions']) ||
                empty($tableNames['model_has_roles']) ||
                empty($tableNames['role_has_permissions'])
            ) {
                throw new \RuntimeException('You must publish the permission configuration file before running this migration.');
            }

            Schema::create($tableNames['permissions'], function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();
                $table->unique(['name', 'guard_name']);
            });

            Schema::create($tableNames['roles'], function (Blueprint $table) use ($teams, $columnNames): void {
                $table->id();

                if ($teams && array_key_exists('team_foreign_key', $columnNames)) {
                    $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                    $table->index($columnNames['team_foreign_key'], 'roles_team_foreign_key_index');
                }

                $table->string('name');
                $table->string('guard_name');
                $table->timestamps();
                $table->unique(['name', 'guard_name']);
            });

            Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $teams): void {
                $table->unsignedBigInteger(PermissionRegistrar::$pivotPermission);
                $table->string('model_type');
                $table->unsignedBigInteger($columnNames['model_morph_key']);
                $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

                if ($teams && array_key_exists('team_foreign_key', $columnNames)) {
                    $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                    $table->index($columnNames['team_foreign_key'], 'model_has_permissions_team_foreign_key_index');

                    $table->primary(
                        [
                            $columnNames['team_foreign_key'],
                            PermissionRegistrar::$pivotPermission,
                            $columnNames['model_morph_key'],
                            'model_type',
                        ],
                        'model_has_permissions_permission_model_type_primary'
                    );
                } else {
                    $table->primary(
                        [PermissionRegistrar::$pivotPermission, $columnNames['model_morph_key'], 'model_type'],
                        'model_has_permissions_permission_model_type_primary'
                    );
                }

                $table->foreign(PermissionRegistrar::$pivotPermission)
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->cascadeOnDelete();
            });

            Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames, $teams): void {
                $table->unsignedBigInteger(PermissionRegistrar::$pivotRole);
                $table->string('model_type');
                $table->unsignedBigInteger($columnNames['model_morph_key']);
                $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

                if ($teams && array_key_exists('team_foreign_key', $columnNames)) {
                    $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                    $table->index($columnNames['team_foreign_key'], 'model_has_roles_team_foreign_key_index');

                    $table->primary(
                        [
                            $columnNames['team_foreign_key'],
                            PermissionRegistrar::$pivotRole,
                            $columnNames['model_morph_key'],
                            'model_type',
                        ],
                        'model_has_roles_role_model_type_primary'
                    );
                } else {
                    $table->primary(
                        [PermissionRegistrar::$pivotRole, $columnNames['model_morph_key'], 'model_type'],
                        'model_has_roles_role_model_type_primary'
                    );
                }

                $table->foreign(PermissionRegistrar::$pivotRole)
                    ->references('id')
                    ->on($tableNames['roles'])
                    ->cascadeOnDelete();
            });

            Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames, $teams): void {
                $table->unsignedBigInteger(PermissionRegistrar::$pivotPermission);
                $table->unsignedBigInteger(PermissionRegistrar::$pivotRole);

                if ($teams && array_key_exists('team_foreign_key', $columnNames)) {
                    $table->unsignedBigInteger($columnNames['team_foreign_key'])->nullable();
                    $table->index($columnNames['team_foreign_key'], 'role_has_permissions_team_foreign_key_index');

                    $table->primary(
                        [
                            $columnNames['team_foreign_key'],
                            PermissionRegistrar::$pivotPermission,
                            PermissionRegistrar::$pivotRole,
                        ],
                        'role_has_permissions_permission_id_role_id_primary'
                    );
                } else {
                    $table->primary(
                        [PermissionRegistrar::$pivotPermission, PermissionRegistrar::$pivotRole],
                        'role_has_permissions_permission_id_role_id_primary'
                    );
                }

                $table->foreign(PermissionRegistrar::$pivotPermission)
                    ->references('id')
                    ->on($tableNames['permissions'])
                    ->cascadeOnDelete();

                $table->foreign(PermissionRegistrar::$pivotRole)
                    ->references('id')
                    ->on($tableNames['roles'])
                    ->cascadeOnDelete();
            });

            app('cache')
                ->store(config('permission.cache.store') !== 'default' ? config('permission.cache.store') : null)
                ->forget(PermissionRegistrar::CACHE_KEY);
        }

        public function down(): void
        {
            $tableNames = config('permission.table_names');

            Schema::dropIfExists($tableNames['role_has_permissions']);
            Schema::dropIfExists($tableNames['model_has_roles']);
            Schema::dropIfExists($tableNames['model_has_permissions']);
            Schema::dropIfExists($tableNames['roles']);
            Schema::dropIfExists($tableNames['permissions']);
        }
    };
}
