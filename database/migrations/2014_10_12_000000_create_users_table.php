<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique()->nullable();
            $table->string('user_code')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->enum('role', ['admin', 'operator', 'borrower'])->nullable();
            $table->enum('gender', ['laki-laki', 'perempuan'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('fcm_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER tr_user_insert
            BEFORE INSERT ON users
            FOR EACH ROW
            BEGIN
                DECLARE role_prefix VARCHAR(3);
                IF NEW.role = "admin" THEN
                    SET role_prefix = "ADM";
                ELSEIF NEW.role = "operator" THEN
                    SET role_prefix = "OPT";
                ELSEIF NEW.role = "borrower" THEN
                    SET role_prefix = "BWR";
                END IF;

                SET @random_string = LEFT(UUID(), 9);
                SET NEW.user_code = CONCAT(role_prefix, @random_string, LPAD((SELECT COUNT(*) + 1 FROM users WHERE role = NEW.role), 9, "0"));
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tr_user_insert');
        Schema::dropIfExists('users');
    }
};
