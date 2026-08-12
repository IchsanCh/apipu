<?php

namespace Database\Seeders;

use App\Models\ApiCredential;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApiCredentialSeeder extends Seeder
{
    public function run(): void
    {
        $credential = ApiCredential::create([
            'uuid' => (string) Str::uuid(),
            'bearer_token' => Str::random(40),
            'apikey' => Str::random(40),
            'salt_key' => Str::random(32),
            'label' => 'Testing - Notifikasa',
        ]);

        $this->command->info('=== API Credential Testing ===');
        $this->command->info('UUID          : ' . $credential->uuid);
        $this->command->info('Bearer Token  : ' . $credential->bearer_token);
        $this->command->info('API Key       : ' . $credential->apikey);
        $this->command->info('Salt Key      : ' . $credential->salt_key);
        $this->command->info('===============================');
    }
}