<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add a default department
        DB::table('departments')->insert([
            'name' => 'Default Department'
        ]);

        // Get the ID of the default department
        $defaultDepartmentId = DB::table('departments')->where('name', 'Default Department')->value('id');

        // Update existing user profiles to have the default department
        DB::table('user_profiles')->update(['department_id' => $defaultDepartmentId]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get the ID of the default department
        $defaultDepartmentId = DB::table('departments')->where('name', 'Default Department')->value('id');

        // Remove the department_id from user profiles
        DB::table('user_profiles')->where('department_id', $defaultDepartmentId)->update(['department_id' => null]);

        // Delete the default department
        DB::table('departments')->where('id', $defaultDepartmentId)->delete();
    }
};
