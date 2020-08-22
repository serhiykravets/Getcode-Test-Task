<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sku', 100);
            $table->string('name', 100);
            $table->string('brand', 100);
            $table->string('description', 1000);
            $table->string('url', 1000);
            $table->timestamps();
        });

        Schema::create('photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('parent_id', 100);
            $table->string('url', 1000);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods');
        Schema::dropIfExists('photos');
    }
}
