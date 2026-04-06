<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id('Assign_id');
            $table->foreignId('emp_id')->constrained('employees', 'emp_id');
            $table->foreignId('proj_id')->constrained('projects', 'proj_id');
            $table->integer('hours');
            $table->string('role');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};