<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePropertyTableEmailNotifyColumnValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      \DB::table('property')
            ->where('email_notify', 0)
            ->update(['email_notify' => 1, 'agent_checked' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      \DB::table('property')
            ->where('email_notify', 1)
            ->update(['email_notify' => 0, 'agent_checked' => 0]);
    }
}
