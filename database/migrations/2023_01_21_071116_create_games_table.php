<?php

use App\Services\Game\Enum\StatusGameEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->integer('first_user_id')->nullable();
            $table->integer('second_user_id')->nullable();
            $table->integer('third_user_id')->nullable();
            $table->integer('fourth_user_id')->nullable();
            $table->integer('fifth_user_id')->nullable();
            $table->integer('sixth_user_id')->nullable();

            $table->jsonb('first_user_info')->nullable();
            $table->jsonb('second_user_info')->nullable();
            $table->jsonb('third_user_info')->nullable();
            $table->jsonb('fourth_user_info')->nullable();
            $table->jsonb('fifth_user_info')->nullable();
            $table->jsonb('sixth_user_info')->nullable();

            $table->enum('status', array_column(StatusGameEnum::cases(), 'value'));
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
        Schema::dropIfExists('games');
    }
};
