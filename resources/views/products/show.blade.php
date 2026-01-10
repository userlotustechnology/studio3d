@extends('layouts.main')

@section('title', 'Visualizar Produto - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 900px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <a href="{{ route('admin.products.index') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">{{ $product->name }}</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Produto ID: #{{ $product->id }}</p>
            </div>
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('admin.products.edit', $product->id) }}" style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar este produto?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background-color: #ef4444; color: white; padding: 12px 24px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-trash"></i> Deletar
                    </button>
                </form>
            </div>
        </div>

        <!-- Content -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px;">
            <!-- Main Content -->
            <div>
                <!-- Imagem -->
                <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 24px;">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; border-radius: 6px; object-fit: cover; max-height: 400px;">
                    @else
                    <div style="width: 100%; height: 400px; background-color: #e5e7eb; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-image" style="font-size: 64px; color: #9ca3af;"></i>
                    </div>
                    @endif
                </div>

                <!-- Descrição -->
                <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h2 style="font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 16px;">Descrição</h2>
                    <p style="color: #6b7280; line-height: 1.6; white-space: pre-wrap;">{{ $product->description }}</p>
                </div>
            </div>

            <!-- Sidebar -->
            <div>
                <!-- Preço e Status -->
                <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 24px;">
                    <div style="margin-bottom: 24px;">
                        <p style="color: #6b7280; font-size: 13px; margin-bottom: 8px;">Preço</p>
                        <h3 style="font-size: 28px; font-weight: 700; color: #1f2937; margin: 0;">R$ {{ number_format($product->price, 2, ',', '.') }}</h3>
                    </div>

                    <div style="padding-top: 24px; border-top: 1px solid #e5e7eb; margin-bottom: 24px;">
                        <p style="color: #6b7280; font-size: 13px; margin-bottom: 8px;">Categoria</p>
                        <p style="color: #1f2937; font-weight: 600; margin: 0;">{{ $product->category?->name ?? 'Sem categoria' }}</p>
                    </div>

                    <div style="padding-top: 24px; border-top: 1px solid #e5e7eb;">
                        <p style="color: #6b7280; font-size: 13px; margin-bottom: 8px;">Status</p>
                        @if($product->is_active)
                        <span style="background-color: #d1fae5; color: #065f46; padding: 6px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">✓ Ativo</span>
                        @else
                        <span style="background-color: #fee2e2; color: #7f1d1d; padding: 6px 12px; border-radius: 12px; font-size: 13px; font-weight: 600;">✗ Inativo</span>
                        @endif
                    </div>
                </div>

                <!-- Informações -->
                <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 16px;">Informações</h3>
                    
                    <div style="margin-bottom: 16px;">
                        <p style="color: #6b7280; font-size: 12px; margin-bottom: 4px;">ID</p>
                        <p style="color: #1f2937; font-weight: 600; margin: 0;">#{{ $product->id }}</p>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <p style="color: #6b7280; font-size: 12px; margin-bottom: 4px;">Criado em</p>
                        <p style="color: #1f2937; font-weight: 600; margin: 0;">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div>
                        <p style="color: #6b7280; font-size: 12px; margin-bottom: 4px;">Atualizado em</p>
                        <p style="color: #1f2937; font-weight: 600; margin: 0;">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
