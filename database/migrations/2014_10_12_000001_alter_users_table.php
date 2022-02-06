<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = Schema::connection($this->getConnection())->getConnection()->getDriverName();

        Schema::table('users', function (Blueprint $table) use ($driver) {
            $table->uuid('id')->change();
            if ($driver === 'sqlite') {
                $table->string('forename', 50)->nullable()->after('name');
                $table->string('surname', 50)->nullable()->after('forename');
            } else {
                $table->string('forename', 50)->after('name');
                $table->string('surname', 50)->after('forename');
            }
            $table->string('mobile', 30)->nullable()->after('password');
            $table->string('phone', 30)->nullable()->after('mobile');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
            $table->string('name')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('forename');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('surname');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mobile');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
}
