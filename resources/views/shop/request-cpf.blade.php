@extends('shop.layout')

@section('title', 'Informe seu CPF')

@section('content')
<div class="container" style="padding: 40px 0;">
    <div style="max-width: 600px; margin: 0 auto; background: white; padding: 24px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="margin-top: 0;">Informe seu CPF</h2>
        <p>Para continuar, precisamos do seu CPF para criar o pedido provisório.</p>

        <form action="{{ route('cart.submit-cpf') }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label style="display:block; font-weight:600; margin-bottom:8px;">CPF</label>
                <input type="text" name="cpf" value="" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #d1d5db;">
            </div>

            <div style="display:flex; gap:8px;">
                <button type="submit" style="background:#3b82f6; color:white; padding:10px 16px; border-radius:6px; border:none; font-weight:600;">Enviar</button>
                <a href="{{ route('shop.index') }}" style="padding:10px 16px; border-radius:6px; background:#e5e7eb; color:#1f2937; text-decoration:none; display:inline-block;">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection