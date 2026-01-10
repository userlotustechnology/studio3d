@extends('layouts.main')

@section('title', $category->name . ' - Detalhes')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header -->
        <div style="margin-bottom: 30px;">
            <a href="{{ route('admin.categories.index') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">{{ $category->name }}</h1>
                    <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Visualize os detalhes da categoria</p>
                </div>
                <div style="display: flex; gap: 8px;">
                    <a href="{{ route('admin.categories.edit', $category->id) }}" style="background-color: #3b82f6; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; font-size: 14px;">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Deseja remover esta categoria? Os produtos não serão deletados.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background-color: #ef4444; color: white; padding: 10px 16px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-size: 14px;">
                            <i class="fas fa-trash"></i> Deletar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <!-- Left Column - Imagem e Info -->
            <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <!-- Imagem -->
                <div style="margin-bottom: 24px;">
                    @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" style="width: 100%; border-radius: 6px; object-fit: cover;">
                    @else
                    <div style="width: 100%; aspect-ratio: 1; background-color: #e5e7eb; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-image" style="font-size: 64px; color: #9ca3af;"></i>
                    </div>
                    @endif
                </div>

                <!-- Informações Básicas -->
                <div style="border-top: 1px solid #e5e7eb; padding-top: 24px;">
                    <h3 style="font-size: 14px; font-weight: 700; color: #1f2937; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.05em;">Informações</h3>

                    <div style="margin-bottom: 16px;">
                        <p style="color: #6b7280; font-size: 12px; margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.05em;">Status</p>
                        <p style="color: #1f2937; font-weight: 600; margin: 0;">
                            <span style="display: inline-block; padding: 4px 12px; border-radius: 9999px; font-size: 12px; background-color: {{ $category->is_active ? '#d1fae5' : '#fee2e2' }}; color: {{ $category->is_active ? '#065f46' : '#991b1b' }};">
                                {{ $category->is_active ? 'Ativa' : 'Inativa' }}
                            </span>
                        </p>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <p style="color: #6b7280; font-size: 12px; margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.05em;">Criado em</p>
                        <p style="color: #1f2937; font-weight: 500; margin: 0;">{{ $category->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <p style="color: #6b7280; font-size: 12px; margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.05em;">Atualizado em</p>
                        <p style="color: #1f2937; font-weight: 500; margin: 0;">{{ $category->updated_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div style="margin-bottom: 0;">
                        <p style="color: #6b7280; font-size: 12px; margin: 0 0 4px 0; text-transform: uppercase; letter-spacing: 0.05em;">Total de Produtos</p>
                        <p style="color: #1f2937; font-weight: 600; font-size: 18px; margin: 0;">{{ $category->products()->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Right Column - Descrição e Produtos -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <!-- Descrição -->
                <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 14px; font-weight: 700; color: #1f2937; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.05em;">Descrição</h3>
                    <p style="color: #4b5563; line-height: 1.6; margin: 0;">
                        {{ $category->description ?? 'Nenhuma descrição fornecida.' }}
                    </p>
                </div>

                <!-- Produtos -->
                <div style="background: white; border-radius: 8px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 14px; font-weight: 700; color: #1f2937; margin: 0 0 16px 0; text-transform: uppercase; letter-spacing: 0.05em;">Produtos Nesta Categoria</h3>
                    
                    @if($category->products()->exists())
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px;">
                            @foreach($category->products() as $product)
                            <a href="{{ route('admin.products.show', $product->id) }}" style="display: flex; flex-direction: column; gap: 8px; text-decoration: none; cursor: pointer; transition: all 0.3s;">
                                <div style="aspect-ratio: 1; background-color: #f3f4f6; border-radius: 6px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                    @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                    <i class="fas fa-box" style="font-size: 32px; color: #9ca3af;"></i>
                                    @endif
                                </div>
                                <div>
                                    <p style="font-weight: 600; color: #1f2937; margin: 0; font-size: 13px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $product->name }}
                                    </p>
                                    <p style="color: #3b82f6; font-weight: 600; margin: 4px 0 0 0; font-size: 13px;">
                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                    </p>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    @else
                        <div style="text-align: center; padding: 32px 16px;">
                            <i class="fas fa-inbox" style="font-size: 48px; color: #d1d5db; margin-bottom: 16px; display: block;"></i>
                            <p style="color: #6b7280; margin: 0;">Nenhum produto nesta categoria</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
