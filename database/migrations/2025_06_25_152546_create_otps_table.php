<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); 
            $table->foreignId('website_id')->nullable()->constrained('websites')->onDelete('cascade');
            
            $table->string('email')->nullable();
            
            $table->string('code'); // Hash OTP code string
            $table->string('type'); // Type (purpose) of OTP (e.g. delete_website, verify_website_email)
            
            $table->unsignedTinyInteger('attempts')->default(0); // Number of attempts
            
            $table->timestamp('expires_at'); // Expiration datetime for the OTP
            $table->timestamp('used_at')->nullable(); // When the OTP has been used, this field stores the usage timestamp

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp');
    }
};
