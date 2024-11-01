<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSymptomsToPatientsTable extends Migration
{
    public function up()
{
    Schema::table('patients', function (Blueprint $table) {
        $table->text('symptoms')->nullable()->after('disease_history');
        $table->text('family_history')->nullable()->after('symptoms');
        $table->string('risk_level')->nullable()->after('family_history');
    });
}

public function down()
{
    Schema::table('patients', function (Blueprint $table) {
        $table->dropColumn(['symptoms', 'family_history', 'risk_level']);
    });
    }
}
