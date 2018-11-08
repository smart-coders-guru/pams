<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apps', function (Blueprint $table) {
            $table->increments('apps_id');
            $table->string('apps_key')->unique();
            $table->string('apps_name')->unique();
            $table->string('apps_login')->unique();
            $table->string('apps_password');
            $table->text('apps_desc')->nullable()->default(null);
            $table->timestamp('apps_creation_date');
            $table->integer('apps_state');
        });

        Schema::create('report_template', function (Blueprint $table) {
            $table->increments('report_template_id');
            $table->unsignedInteger('apps_id');
            $table->string('report_template_name');
            $table->text('report_template_content');
            $table->text('report_template_desc')->nullable()->default(null);
            $table->index('apps_id', 'i_fk_report_template_apps');
            $table->integer('report_template_state');
            $table->foreign('apps_id')->references('apps_id')->on('apps')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('report', function (Blueprint $table) {
            $table->increments('report_id');
            $table->unsignedInteger('apps_id');
            $table->unsignedInteger('report_template_id');
            $table->string('report_title');
            $table->string('report_content');
            $table->text('report_link');
            $table->text('report_desc')->nullable()->default(null);
            $table->timestamp('apps_creation_date');
            $table->text('report_type');
            $table->integer('report_state');
            $table->index('apps_id', 'i_fk_report_apps');
            $table->index('report_template_id', 'i_fk_report_report_template');
            $table->foreign('apps_id')->references('apps_id')->on('apps')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('report_template_id')->references('report_template_id')->on('report_template')->onDelete('cascade')->onUpdate('cascade');
        });

       Schema::create('email_template', function (Blueprint $table) {
            $table->increments('email_template_id');
            $table->unsignedInteger('apps_id');
            $table->string('email_template_name');
            $table->text('email_template_content')->nullable()->default(null);
            $table->integer('email_template_state');
            $table->index('apps_id', 'fk_email_template_apps');
            $table->foreign('apps_id')->references('apps_id')->on('apps')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('programmed_email', function (Blueprint $table) {
            $table->increments('programmed_email_id');
            $table->unsignedInteger('apps_id');
            $table->unsignedInteger('email_template_id');
            $table->string('programmed_email_title');
            $table->string('programmed_email_subject');
            $table->text('programmed_email_content')->nullable()->default(null);
            $table->string('programmed_email_attachments');
            $table->timestamp('programmed_email_send_time');
            $table->string('programmed_email_is_send_only_to_user');
            $table->integer('programmed_email_state');
            $table->index('apps_id', 'fk_programmed_email_apps');
            $table->index('email_template_id', 'fk_programmed_email_template');
            $table->foreign('apps_id')->references('apps_id')->on('apps')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('email_template_id')->references('email_template_id')->on('email_template')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('mailing_list', function (Blueprint $table) {
            $table->increments('mailing_list_id');
            $table->string('mailing_list_name');
            $table->text('mailing_list_additional_email')->nullable()->default(null);
            $table->text('mailing_list_desc');
            $table->integer('mailing_list_state');
        });


        Schema::create('programmed_email_receiver', function (Blueprint $table) {
            $table->unsignedInteger('programmed_email_id');
            $table->unsignedInteger('mailing_list_id');
            $table->primary('programmed_email_id', 'mailing_list_id');
            $table->index('programmed_email_id', 'fk_programmed_email_receiver_prog_email');
            $table->index('mailing_list_id', 'fk_programmed_email_receiver_mailing_list');
            $table->foreign('programmed_email_id')->references('programmed_email_id')->on('programmed_email')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mailing_list_id')->references('mailing_list_id')->on('mailing_list')->onDelete('cascade')->onUpdate('cascade');
        });
         
         Schema::create('mailing_list_apps', function (Blueprint $table) {
            $table->unsignedInteger('mailing_list_id');
            $table->unsignedInteger('apps_id');
            $table->primary('mailing_list_id', 'apps_id');
            $table->index('apps_id', 'fk_mailing_list_apps_apps');
            $table->index('mailing_list_id', 'fk_mailing_list_apps_mailing_list');
            $table->foreign('apps_id')->references('apps_id')->on('apps')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mailing_list_id')->references('mailing_list_id')->on('mailing_list')->onDelete('cascade')->onUpdate('cascade');
        });

       Schema::create('email_account', function (Blueprint $table) {
            $table->increments('email_account_id');
            $table->unsignedInteger('apps_id');
            $table->string('email_account_name');
            $table->string('email_account_email')->unique();
            $table->text('email_account_desc')->nullable()->default(null);
            $table->integer('email_account_state');
            $table->index('apps_id', 'fk_email_account_apps');
            $table->foreign('apps_id')->references('apps_id')->on('apps')->onDelete('cascade')->onUpdate('cascade');
        });
        
        Schema::create('email_trace', function (Blueprint $table) {
            $table->increments('email_trace_id');
            $table->unsignedInteger('apps_id');
            $table->unsignedInteger('email_account_id');
            $table->unsignedInteger('email_template_id');
            $table->unsignedInteger('mailing_list_id');
            $table->integer('email_trace_state');
            $table->index('apps_id', 'fk_email_trace_apps');
            $table->index('email_account_id', 'fk_email_trace_account');
            $table->index('email_template_id', 'fk_email_trace_template');
            $table->index('mailing_list_id', 'fk_email_trace_mailing_list');
            $table->foreign('apps_id')->references('apps_id')->on('apps')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('email_account_id')->references('email_account_id')->on('email_account')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('email_template_id')->references('email_template_id')->on('email_template')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mailing_list_id')->references('mailing_list_id')->on('mailing_list')->onDelete('cascade')->onUpdate('cascade');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_trace');
        Schema::dropIfExists('programmed_email_receiver');
        Schema::dropIfExists('mailing_list_apps');
        Schema::dropIfExists('mailing_list');
        Schema::dropIfExists('email_account');
        Schema::dropIfExists('programmed_email');
        Schema::dropIfExists('email_template');
        Schema::dropIfExists('report');
        Schema::dropIfExists('apps');
        Schema::dropIfExists('email_bug_report');
        Schema::dropIfExists('report_template');
        
    }
}
