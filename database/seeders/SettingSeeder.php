<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            ['key' => 'store_name', 'value' => 'Studio3D', 'type' => 'string', 'description' => 'Nome da loja'],
            ['key' => 'store_email', 'value' => 'contato@studio3d.com', 'type' => 'string', 'description' => 'Email da loja'],
            ['key' => 'store_phone', 'value' => '(11) 9999-9999', 'type' => 'string', 'description' => 'Telefone da loja'],
            ['key' => 'store_address', 'value' => 'São Paulo, SP', 'type' => 'string', 'description' => 'Endereço da loja'],
            ['key' => 'system_name', 'value' => 'Studio3D', 'type' => 'string', 'description' => 'Nome do sistema'],
            ['key' => 'system_version', 'value' => '1.0.0', 'type' => 'string', 'description' => 'Versão do sistema'],
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'description' => 'Modo de manutenção'],
            ['key' => 'email_notifications', 'value' => '1', 'type' => 'boolean', 'description' => 'Notificações por email'],
        ];

        foreach ($defaults as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'description' => $setting['description']
                ]
            );
        }
    }
}
