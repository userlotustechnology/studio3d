@extends('layouts.main')

@section('title', 'Editar Frete - ' . $shippingRate->state_name)

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 800px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; align-items: center; margin-bottom: 30px; gap: 15px;">
            <a href="{{ route('admin.shipping-rates.index') }}" style="color: #3b82f6; text-decoration: none; font-size: 20px; padding: 8px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin: 0;">Editar Frete</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 4px;">{{ $shippingRate->state_name }}</p>
            </div>
        </div>

        <!-- Form Card -->
        <div style="background: white; border-radius: 8px; padding: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <form method="POST" action="{{ route('admin.shipping-rates.update', $shippingRate) }}">
                @csrf
                @method('PUT')

                <!-- State Display -->
                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1f2937; font-size: 14px;">
                        <i class="fas fa-map-pin" style="margin-right: 6px;"></i>Estado
                    </label>
                    <div style="padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; background-color: #f9fafb; color: #6b7280; font-weight: 500;">
                        {{ $shippingRate->state_code }} - {{ $shippingRate->state_name }}
                    </div>
                </div>

                <!-- Rate Input -->
                <div style="margin-bottom: 25px;">
                    <label for="rate" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1f2937; font-size: 14px;">
                        <i class="fas fa-money-bill-wave" style="margin-right: 6px;"></i>Valor do Frete (R$) <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="number" id="rate" name="rate" value="{{ old('rate', $shippingRate->rate) }}" step="0.01" min="0" max="999.99" required style="width: 100%; padding: 12px; border: 1px solid #e5e7eb; border-radius: 6px; font-family: inherit; font-size: 16px; box-sizing: border-box;">
                    @error('rate')
                        <span style="color: #ef4444; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Active Checkbox -->
                <div style="margin-bottom: 30px; display: flex; align-items: center; gap: 10px;">
                    <input type="checkbox" id="is_active" name="is_active" value="on" {{ old('is_active', $shippingRate->is_active) ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer; accent-color: #3b82f6;">
                    <label for="is_active" style="font-weight: 600; color: #1f2937; cursor: pointer; margin: 0; font-size: 14px;">
                        <i class="fas fa-check-circle" style="margin-right: 6px;"></i>Ativo
                    </label>
                </div>

                <!-- Buttons -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 30px;">
                    <a href="{{ route('admin.shipping-rates.index') }}" style="padding: 12px 20px; text-align: center; border: 1px solid #d1d5db; color: #374151; background: white; border-radius: 6px; text-decoration: none; font-weight: 600; transition: all 0.2s; font-size: 14px;">
                        <i class="fas fa-times" style="margin-right: 6px;"></i>Cancelar
                    </a>
                    <button type="submit" style="padding: 12px 20px; background-color: #3b82f6; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s; font-size: 14px;">
                        <i class="fas fa-save" style="margin-right: 6px;"></i>Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    input[type="number"]:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    input[type="checkbox"]:focus {
        outline: 2px solid #3b82f6;
        outline-offset: 2px;
    }
</style>
@endsection
