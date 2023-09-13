<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $driver = Schema::getConnection()->getDriverName();

            Schema::table('users', function (Blueprint $table) use ($driver) {
                $table->string('guid')->nullable();
                $table->string('domain')->nullable();

                if ($driver !== 'sqlsrv') {
                    $table->unique('guid');
                }
            });

            if ($driver === 'sqlsrv') {
                DB::statement(
                    $this->compileUniqueSqlServerIndexStatement('users', 'guid')
                );
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
