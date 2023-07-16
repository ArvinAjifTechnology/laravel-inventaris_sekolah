<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code', 20)->unique();
            $table->string('item_name', 50);
            $table->unsignedBigInteger('room_id');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade')->onUpdate('cascade');
            $table->text('description')->nullable();
            $table->enum('condition', ['good', 'fair', 'bad']);
            $table->float('rental_price');
            $table->float('late_fee_per_day');
            $table->integer('quantity');
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER tr_item_insert
            BEFORE INSERT ON items
            FOR EACH ROW
            BEGIN
                SET @random_string = LEFT(UUID(), 6);
                SET NEW.item_code = CONCAT("ITM", @random_string, LPAD((SELECT COUNT(*) + 1 FROM items), 6, "0"));
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tr_item_insert');
        Schema::dropIfExists('items');
    }
}
