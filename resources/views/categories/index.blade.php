@extends('layouts.main')

@section('title', 'Categorias - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Categorias</h1>
                <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Gerenciamento de categorias de produtos</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px;">
                <i class="fas fa-plus"></i> Nova Categoria
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

        <!-- Categorias Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
            @forelse($categories as $category)
            <div style="background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
                <!-- Imagem -->
                @if($category->image)
                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" style="width: 100%; height: 180px; object-fit: cover;">
                @else
                <div style="width: 100%; height: 180px; background-color: #e5e7eb; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-image" style="color: #9ca3af; font-size: 48px;"></i>
                </div>
                @endif

                <!-- Conteúdo -->
                <div style="padding: 20px;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #1f2937; margin: 0 0 8px 0;">{{ $category->name }}</h3>
                    <p style="color: #6b7280; font-size: 13px; margin: 0 0 16px 0; line-height: 1.5;">
                        {{ $category->description ? Str::limit($category->description, 60) : 'Sem descrição' }}
                    </p>
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                        <span style="font-size: 12px; color: #6b7280;">
                            <i class="fas fa-box" style="margin-right: 4px;"></i>
                            {{ $category->products()->count() }} produto(s)
                        </span>
                        @if($category->is_active)
                        <span style="background-color: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;">Ativo</span>
                        @else
                        <span style="background-color: #fee2e2; color: #7f1d1d; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;">Inativo</span>
                        @endif
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('admin.categories.show', $category->id) }}" style="flex: 1; background-color: #e0e7ff; color: #3730a3; padding: 8px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; text-align: center;">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" style="flex: 1; background-color: #fef3c7; color: #92400e; padding: 8px 12px; border-radius: 4px; text-decoration: none; font-size: 12px; font-weight: 600; text-align: center;">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="flex: 1;" onsubmit="return confirm('Tem certeza que deseja deletar esta categoria?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="width: 100%; background-color: #fee2e2; color: #7f1d1d; padding: 8px 12px; border-radius: 4px; border: none; font-size: 12px; font-weight: 600; cursor: pointer;">
                                <i class="fas fa-trash"></i> Deletar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1;">
                <div style="background: white; border-radius: 8px; padding: 60px 20px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                    <i class="fas fa-layer-group" style="font-size: 48px; color: #9ca3af; margin-bottom: 16px; display: block;"></i>
                    <p style="color: #6b7280; margin: 0;">Nenhuma categoria cadastrada</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
        <div style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
