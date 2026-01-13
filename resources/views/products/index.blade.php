@extends('layouts.main')

@section('title', 'Produtos - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Produtos</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Gerenciamento de produtos da loja</p>
            </div>
            <a href="{{ route('admin.products.create') }}" style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i> Novo Produto
            </a>
        </div>

        @if(session('success'))
        <div style="background-color: #d1fae5; border-left: 4px solid #10b981; padding: 16px; border-radius: 6px; margin-bottom: 20px;">
            <p style="color: #065f46; margin: 0;">✓ {{ session('success') }}</p>
        </div>
        @endif

        <!-- Produtos Table -->
        <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f9fafb; border-bottom: 1px solid #e5e7eb;">
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">ID</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Imagem</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Nome</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Categoria</th>
                        <th style="padding: 16px; text-align: left; color: #6b7280; font-weight: 600; font-size: 14px;">Preço</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Status</th>
                        <th style="padding: 16px; text-align: center; color: #6b7280; font-weight: 600; font-size: 14px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 16px; color: #6b7280; font-size: 14px;">#{{ $product->id }}</td>
                        <td style="padding: 16px;">
                            @php
                                $imageUrl = $product->image_url ?? null;
                            @endphp
                            @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $product->name }}" style="width: 60px; height: 60px; border-radius: 6px; object-fit: cover; border: 1px solid #e5e7eb;">
                            @else
                            <div style="width: 60px; height: 60px; background-color: #f3f4f6; border-radius: 6px; display: flex; align-items: center; justify-content: center; border: 1px solid #e5e7eb;">
                                <i class="fas fa-image" style="color: #9ca3af; font-size: 24px;"></i>
                            </div>
                            @endif
                        </td>
                        <td style="padding: 16px;">
                            <p style="color: #1f2937; font-weight: 600; margin: 0; font-size: 14px;">{{ $product->name }}</p>
                            <p style="color: #6b7280; margin: 4px 0 0 0; font-size: 12px;">{{ Str::limit($product->description, 50) }}</p>
                        </td>
                        <td style="padding: 16px; color: #6b7280; font-size: 14px;">{{ $product->category?->name ?? 'Sem categoria' }}</td>
                        <td style="padding: 16px; color: #1f2937; font-weight: 600; font-size: 14px;">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                        <td style="padding: 16px; text-align: center;">
                            @if($product->is_active)
                            <span style="background-color: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">Ativo</span>
                            @else
                            <span style="background-color: #fee2e2; color: #7f1d1d; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;">Inativo</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.products.show', $product->uuid) }}" style="background-color: #3b82f6; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; transition: background-color 0.3s;" title="Visualizar produto">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="{{ route('admin.products.edit', $product->id) }}" style="background-color: #f59e0b; color: white; padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; transition: background-color 0.3s;" title="Editar produto">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Tem certeza que deseja deletar este produto?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background-color: #ef4444; color: white; padding: 8px 16px; border-radius: 4px; border: none; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; transition: background-color 0.3s;" title="Deletar produto">
                                        <i class="fas fa-trash"></i> Deletar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 40px; text-align: center; color: #6b7280;">
                            <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 16px; display: block; opacity: 0.5;"></i>
                            <p style="margin: 0;">Nenhum produto cadastrado</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
