@extends('layouts.main')

@section('title', 'Novo Produto - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 800px; margin: 0 auto;">
        <!-- Header -->
        <div style="margin-bottom: 30px;">
            <a href="{{ route('admin.products.index') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Novo Produto</h1>
            <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Preencha os dados para criar um novo produto</p>
        </div>

        <!-- Form -->
        <div style="background: white; border-radius: 8px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nome -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                        Nome do Produto *
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                        placeholder="Digite o nome do produto">
                    @error('name')
                    <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                        Descrição *
                    </label>
                    <textarea name="description" required rows="6"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box; font-family: inherit;"
                        placeholder="Digite a descrição do produto">{{ old('description') }}</textarea>
                    @error('description')
                    <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Preço e Categoria -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                    <!-- Preço -->
                    <div>
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            Preço (R$) *
                        </label>
                        <input type="number" name="price" value="{{ old('price') }}" required step="0.01" min="0.01"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                            placeholder="0.00">
                        @error('price')
                        <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Categoria -->
                    <div>
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            Categoria *
                        </label>
                        <select name="category_id" required
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $id => $name)
                            <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SKU, Tipo e Estoque -->
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                    <!-- SKU -->
                    <div>
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            SKU
                        </label>
                        <input type="text" name="sku" value="{{ old('sku') }}"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                            placeholder="SKU-001">
                        @error('sku')
                        <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tipo de Produto -->
                    <div>
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            Tipo de Produto *
                        </label>
                        <select name="type" required
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;">
                            <option value="">Selecione o tipo</option>
                            <option value="physical" {{ old('type') == 'physical' ? 'selected' : '' }}>
                                <i class="fas fa-box"></i> Físico
                            </option>
                            <option value="digital" {{ old('type') == 'digital' ? 'selected' : '' }}>
                                <i class="fas fa-download"></i> Digital
                            </option>
                        </select>
                        @error('type')
                        <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estoque -->
                    <div id="stock-field">
                        <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                            <span id="stock-label">Estoque (unidades) *</span>
                        </label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" id="stock-input" min="0"
                            style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                            placeholder="0">
                        <p id="stock-help" style="color: #6b7280; font-size: 12px; margin-top: 4px; display: none;">
                            Produtos digitais não requerem estoque
                        </p>
                        @error('stock')
                        <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Imagem -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                        Imagem do Produto
                    </label>
                    <div style="border: 2px dashed #d1d5db; border-radius: 6px; padding: 24px; text-align: center; cursor: pointer; transition: all 0.3s;"
                        id="drop-zone">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: #9ca3af; margin-bottom: 12px; display: block;"></i>
                        <p style="color: #6b7280; margin: 0 0 8px 0;">Arraste a imagem aqui ou clique para selecionar</p>
                        <p style="color: #9ca3af; font-size: 12px; margin: 0;">PNG, JPG, GIF até 2MB</p>
                        <input type="file" name="image" id="image-input" accept="image/*"
                            style="display: none;">
                    </div>
                    <div id="image-preview" style="margin-top: 16px; display: none;">
                        <img id="preview-img" src="" alt="Preview" style="max-width: 200px; border-radius: 6px;">
                    </div>
                    @error('image')
                    <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div style="margin-bottom: 32px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" checked
                            style="width: 18px; height: 18px; cursor: pointer;">
                        <span style="color: #1f2937; font-weight: 600; font-size: 14px;">Produto Ativo</span>
                    </label>
                    <p style="color: #6b7280; font-size: 12px; margin-top: 8px;">Produtos ativos aparecem na loja</p>
                </div>

                <!-- Botões -->
                <div style="display: flex; gap: 12px;">
                    <button type="submit" style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer; flex: 1;">
                        <i class="fas fa-save"></i> Criar Produto
                    </button>
                    <a href="{{ route('admin.products.index') }}" style="background-color: #e5e7eb; color: #1f2937; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; text-align: center; flex: 1;">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const dropZone = document.getElementById('drop-zone');
const imageInput = document.getElementById('image-input');
const imagePreview = document.getElementById('image-preview');
const previewImg = document.getElementById('preview-img');
const typeSelect = document.querySelector('select[name="type"]');
const stockInput = document.getElementById('stock-input');
const stockField = document.getElementById('stock-field');
const stockLabel = document.getElementById('stock-label');
const stockHelp = document.getElementById('stock-help');

// Função para atualizar visibilidade do campo de estoque
function updateStockField() {
    const selectedType = typeSelect.value;
    
    if (selectedType === 'digital') {
        // Produtos digitais: tornar estoque opcional
        stockInput.removeAttribute('required');
        stockInput.value = 0;
        stockLabel.textContent = 'Estoque (unidades)';
        stockHelp.style.display = 'block';
    } else if (selectedType === 'physical') {
        // Produtos físicos: tornar estoque obrigatório
        stockInput.setAttribute('required', '');
        stockHelp.style.display = 'none';
        stockLabel.textContent = 'Estoque (unidades) *';
    }
}

// Adicionar listener para mudanças no tipo de produto
typeSelect.addEventListener('change', updateStockField);

// Chamar ao carregar para sincronizar com valor anterior
updateStockField();

// Click to select
dropZone.addEventListener('click', () => imageInput.click());

// Drag and drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.style.borderColor = '#3b82f6';
    dropZone.style.backgroundColor = '#eff6ff';
});

dropZone.addEventListener('dragleave', () => {
    dropZone.style.borderColor = '#d1d5db';
    dropZone.style.backgroundColor = 'transparent';
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.style.borderColor = '#d1d5db';
    dropZone.style.backgroundColor = 'transparent';
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        imageInput.files = files;
        handleImageSelect();
    }
});

// File input change
imageInput.addEventListener('change', handleImageSelect);

function handleImageSelect() {
    const file = imageInput.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImg.src = e.target.result;
            imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
