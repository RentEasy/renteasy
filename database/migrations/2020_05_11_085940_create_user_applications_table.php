<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->default('');
            $table->string('middle_name')->default('');
            $table->string('last_name')->default('');
            $table->string('suffix')->nullable();
            $table->string('phone')->nullable();
        });

        foreach(\App\User::all() as $user) {
            $names = $this->split_name($user->name);

            $user->first_name = $names['first_name'] ?? '';
            $user->middle_name = $names['middle_name'] ?? '';
            $user->last_name = $names['last_name'] ?? '';

            $user->save();
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        Schema::create('rental_application_pets', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rental_application_id');
            $table->foreign('rental_application_id')->references('id')->on('rental_applications');

            $table->string('type');
            $table->string('breed');
            $table->string('weight');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('rental_application_vehicles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rental_application_id');
            $table->foreign('rental_application_id')->references('id')->on('rental_applications');

            $table->string('year');
            $table->string('make');
            $table->string('model');
            $table->string('plate');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('rental_application_identifications', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rental_application_id');
            $table->foreign('rental_application_id')->references('id')->on('rental_applications');

            $table->string('type');
            $table->string('state');
            $table->string('number');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('rental_application_employments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rental_application_id');
            $table->foreign('rental_application_id')->references('id')->on('rental_applications');

            $table->string('status');
            $table->string('name');
            $table->string('position');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('city');
            $table->string('state');
            $table->string('supervisor');
            $table->string('supervisor_phone');
            $table->string('income_annual');
            $table->string('income_comments');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('rental_application_references', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rental_application_id');
            $table->foreign('rental_application_id')->references('id')->on('rental_applications');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('relation');
            $table->string('phone');

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('rental_application_rental_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rental_application_id');
            $table->foreign('rental_application_id')->references('id')->on('rental_applications');

            $table->string('street_address');
            $table->string('unit_apt');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->string('landlord_name');
            $table->string('landlord_phone');
            $table->string('rent_monthly');
            $table->string('rent_own_other');
            $table->date('start_date');
            $table->date('end_date');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->default('');
        });

        foreach(\App\User::all() as $user) {
            $user->name = "$user->first_name $user->middle_name $user->last_name";

            $user->save();
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'suffix',
                'phone',
            ]);
        });
        Schema::dropIfExists('rental_application_pets');
        Schema::dropIfExists('rental_application_vehicles');
        Schema::dropIfExists('rental_application_identifications');
        Schema::dropIfExists('rental_application_employment');
        Schema::dropIfExists('rental_application_reference');
        Schema::dropIfExists('rental_application_rental_history');
    }

    private function split_name($name)
    {
        $parts = array();

        while (strlen(trim($name)) > 0) {
            $name = trim($name);
            $string = preg_replace('#.*\s([\w-]*)$#', '$1', $name);
            $parts[] = $string;
            $name = trim(preg_replace('#' . $string . '#', '', $name));
        }

        if (empty($parts)) {
            return false;
        }

        $parts = array_reverse($parts);
        $name = array();
        $name['first_name'] = $parts[0];
        $name['middle_name'] = (isset($parts[2])) ? $parts[1] : '';
        $name['last_name'] = (isset($parts[2])) ? $parts[2] : (isset($parts[1]) ? $parts[1] : '');

        return $name;
    }
}
