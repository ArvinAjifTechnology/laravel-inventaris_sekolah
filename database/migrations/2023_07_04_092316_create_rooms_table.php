<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_code', 20)->unique();
            $table->string('room_name', 50)->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // Add the trigger for room_code generation
        DB::unprepared('
            CREATE TRIGGER tr_room_insert BEFORE INSERT ON rooms FOR EACH ROW
            BEGIN
                SET @random_string = LEFT(UUID(), 9);
                SET NEW.room_code = CONCAT("RM", @random_string, LPAD((SELECT COUNT(*) + 1 FROM rooms), 9, "0"));
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tr_room_insert');
        Schema::dropIfExists('rooms');
    }
};
