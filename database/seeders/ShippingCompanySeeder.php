<?php

namespace Database\Seeders;

use App\Models\ShippingCompany;
use Illuminate\Database\Seeder;

class ShippingCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            [
                'name' => 'Sedex',
                'slug' => 'sedex',
                'code' => '04162',
                'description' => 'Serviço de entrega express dos Correios',
                'website' => 'https://www.correios.com.br',
                'tracking_url_template' => 'https://www.correios.com.br/rastreamento?codigo={code}',
            ],
            [
                'name' => 'PAC',
                'slug' => 'pac',
                'code' => '04669',
                'description' => 'Serviço de entrega econômico dos Correios',
                'website' => 'https://www.correios.com.br',
                'tracking_url_template' => 'https://www.correios.com.br/rastreamento?codigo={code}',
            ],
            [
                'name' => 'Jadlog',
                'slug' => 'jadlog',
                'code' => 'JADLOG',
                'description' => 'Serviço de entrega Jadlog',
                'website' => 'https://www.jadlog.com.br',
                'tracking_url_template' => 'https://www.jadlog.com.br/rastreamento?codigo={code}',
            ],
            [
                'name' => 'Loggi',
                'slug' => 'loggi',
                'code' => 'LOGGI',
                'description' => 'Serviço de entrega Loggi',
                'website' => 'https://www.loggi.com',
                'tracking_url_template' => 'https://www.loggi.com/rastrear?codigo={code}',
            ],
            [
                'name' => 'Shipeazy',
                'slug' => 'shipeazy',
                'code' => 'SHIPEAZY',
                'description' => 'Serviço de entrega Shipeazy',
                'website' => 'https://www.shipeazy.com.br',
                'tracking_url_template' => 'https://www.shipeazy.com.br/rastreamento?codigo={code}',
            ],
            [
                'name' => 'Melhor Envio',
                'slug' => 'melhor-envio',
                'code' => 'MELHOR_ENVIO',
                'description' => 'Plataforma de envio integrada',
                'website' => 'https://www.melhorenvio.com.br',
                'tracking_url_template' => 'https://www.melhorenvio.com.br/rastreamento/{code}',
            ],
        ];

        foreach ($companies as $company) {
            ShippingCompany::firstOrCreate(
                ['slug' => $company['slug']],
                $company
            );
        }
    }
}
