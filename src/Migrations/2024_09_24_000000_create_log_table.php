<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Konnec\Helpers\Actions\TableName;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(TableName::handle(), function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->text('context');
            $table->string('level', 30)->index();
            $table->string('level_name', 255);
            $table->string('channel', 60)->index();
            $table->timestamp('record_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('extra');
            $table->text('formatted');
            $table->ipAddress('remote_addr')->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(TableName::handle());
    }
};
