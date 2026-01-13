@extends('layouts.main')

@section('title', 'Produtos - Admin')

@section('content')
<div style="padding: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Produtos</h1>
            <p style="color: #6b7280;">Gerencie os produtos da sua loja</p>
        </div>
        <a href="{{ route('admin.products.create') }}" 
           style="background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-plus"></i>
            Novo Produto
        </a>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #059669; color: #059669; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #dc2626; color: #dc2626; padding: 16px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-exclamation-triangle"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Filtros -->
    <div style="background: white; border-radius: 8px; padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form method="GET" action="{{ route('admin.products.index') }}" style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 16px; align-items: end;">
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Produto</label>
                <input type="text" name="search" placeholder="Nome ou SKU" value="{{ request('search') }}"
                       style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Categoria</label>
                <select name="category" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Todas as categorias</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if(request('category') == $category->id) selected @endif>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="display: block; font-weight: 600; color: #1f2937; margin-bottom: 8px;">Status</label>
                <select name="status" style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px;">
                    <option value="">Todos</option>
                    <option value="active" @if(request('status') === 'active') selected @endif>Ativo</option>
                    <option value="inactive" @if(request('status') === 'inactive') selected @endif>Inativo</option>
                </select>
            </div>
            <button type="submit" style="background: #059669; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Produtos Table -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Produto</th>
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">SKU</th>
                        <th style="text-align: left; padding: 16px; font-weight: 600; color: #1f2937;">Categoria</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Preço</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Estoque</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Status</th>
                        <th style="text-align: center; padding: 16px; font-weight: 600; color: #1f2937;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 16px;">
                            <div style="display: flex; gap: 12px; align-items: center;">
                                <div style="flex-shrink: 0;">
                                    @php
                                        $imageUrl = $product->image_url ?? null;
                                    @endphp
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" style="width: 48px; height: 48px; border-radius: 6px; object-fit: cover; border: 1px solid #e5e7eb;">
                                    @else
                                        <div style="width: 48px; height: 48px; background-color: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center; border: 1px solid #e5e7eb;">
                                            <i class="fas fa-image" style="color: #9ca3af; font-size: 20px;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #1f2937;">{{ $product->name }}</div>
                                    <div style="font-size: 14px; color: #6b7280;">{{ Str::limit($product->description, 40) }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 16px; color: #6b7280;">{{ $product->sku ?? '-' }}</td>
                        <td style="padding: 16px; color: #6b7280;">{{ $product->category?->name ?? '-' }}</td>
                        <td style="padding: 16px; text-align: center; font-weight: 600; color: #1f2937;">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                        <td style="padding: 16px; text-align: center;">
                            <span style="font-size: 18px; font-weight: 700; 
                                @if($product->stock <= 5) color: #dc2626; @elseif($product->stock <= 10) color: #f59e0b; @else color: #059669; @endif">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            @if($product->is_active)
                                <span style="background: #ecfdf5; color: #059669; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">ATIVO</span>
                            @else
                                <span style="background: #fef2f2; color: #dc2626; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">INATIVO</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.products.show', $product->uuid) }}" 
                                   style="background: #e0e7ff; color: #3730a3; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                    Visualizar
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                   style="background: #fef3c7; color: #92400e; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600;">
                                    Editar
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar este produto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #fee2e2; color: #991b1b; padding: 6px 12px; border: none; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; cursor: pointer;">
                                        Deletar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 48px; text-align: center; color: #6b7280;">
                            <i class="fas fa-box-open" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                            <div>Nenhum produto encontrado</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($products->hasPages())
        <div style="padding: 16px; border-top: 1px solid #e5e7eb;">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
