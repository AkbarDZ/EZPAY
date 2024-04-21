<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUpdateProductStockTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
            CREATE TRIGGER update_product_stock_trigger AFTER INSERT ON sales_details
            FOR EACH ROW
            BEGIN
                UPDATE products
                SET stock = stock - NEW.quantity
                WHERE id = NEW.product_id;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_product_stock_trigger');
    }
}
