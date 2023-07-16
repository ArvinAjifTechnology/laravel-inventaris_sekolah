<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateBorrowsTable extends Migration
{
    public function up()
    {
        Schema::create('borrows', function (Blueprint $table) {
            $table->id();
            $table->string('verification_code_for_borrow_request', 255)->nullable();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('user_id');
            $table->string('borrow_code', 255)->unique();
            $table->date('borrow_date');
            $table->date('return_date');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('borrow_status', ['pending', 'completed', 'borrowed', 'rejected']);
            $table->integer('borrow_quantity');
            $table->float('late_fee');
            $table->float('total_rental_price');
            $table->float('sub_total');
            $table->timestamps();
        });

        DB::unprepared('
            CREATE TRIGGER tr_borrow_insert
            BEFORE INSERT ON borrows
            FOR EACH ROW
            BEGIN
                SET @random_string = LEFT(UUID(), 6);
                SET NEW.borrow_code = CONCAT("BRW", @random_string, LPAD((SELECT COUNT(*) + 1 FROM borrows), 6, "0"));
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tr_borrow_insert');
        Schema::dropIfExists('borrows');
    }
}
