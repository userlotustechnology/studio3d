<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display a listing of the settings.
     */
    public function index(): View
    {
        $settings = [
            'store_name' => Setting::get('store_name', 'Studio3D'),
            'store_email' => Setting::get('store_email', ''),
            'store_phone' => Setting::get('store_phone', ''),
            'store_address' => Setting::get('store_address', ''),
            'system_name' => Setting::get('system_name', 'Studio3D'),
            'system_version' => Setting::get('system_version', '1.0.0'),
            'maintenance_mode' => Setting::get('maintenance_mode', false),
            'email_notifications' => Setting::get('email_notifications', true),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Store store settings.
     */
    public function storeSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_email' => 'required|email',
            'store_phone' => 'required|string|max:20',
            'store_address' => 'required|string',
        ]);

        Setting::set('store_name', $validated['store_name'], 'string', 'Nome da loja');
        Setting::set('store_email', $validated['store_email'], 'string', 'Email da loja');
        Setting::set('store_phone', $validated['store_phone'], 'string', 'Telefone da loja');
        Setting::set('store_address', $validated['store_address'], 'string', 'Endereço da loja');

        return redirect()->route('admin.settings.index')->with('success', 'Informações da loja atualizadas com sucesso!');
    }

    /**
     * Store system settings.
     */
    public function systemSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'maintenance_mode' => 'boolean',
            'email_notifications' => 'boolean',
        ]);

        Setting::set('maintenance_mode', $request->has('maintenance_mode'), 'boolean', 'Modo de manutenção');
        Setting::set('email_notifications', $request->has('email_notifications'), 'boolean', 'Notificações por email');

        return redirect()->route('admin.settings.index')->with('success', 'Configurações do sistema atualizadas com sucesso!');
    }
}
