@extends('layouts.main')

@section('title', 'Editar Categoria - Admin')

@section('content')
<div style="padding: 24px;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 8px;">Editar Categoria</h1>
            <p style="color: #6b7280;">Atualize os dados da categoria</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" style="background: #e5e7eb; color: #1f2937; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-arrow-left"></i>
            Voltar
        </a>
    </div>

    <!-- Form Card -->
    <div style="background: white; border-radius: 8px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Grid Layout para Nome e Descrição -->
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px; margin-bottom: 24px;">
                <!-- Nome -->
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Nome da Categoria *</label>
                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box;"
                        placeholder="Digite o nome da categoria">
                    @error('name')
                    <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div>
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Descrição</label>
                    <textarea name="description" rows="4"
                        style="width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; box-sizing: border-box; font-family: inherit;"
                        placeholder="Digite a descrição da categoria">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                    <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Imagem -->
            <div style="margin-bottom: 24px;">
                <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px;">Imagem da Categoria</label>
                
                @if($category->image_path)
                <div style="margin-bottom: 16px;">
                    <p style="color: #1f2937; font-weight: 600; font-size: 13px; margin-bottom: 8px;">Imagem Atual:</p>
                    <img src="{{ asset('storage/' . $category->image_path) }}" alt="Imagem atual" 
                         style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                </div>
                @endif
                
                <div style="border: 2px dashed #d1d5db; border-radius: 8px; padding: 24px; text-align: center; cursor: pointer; transition: all 0.3s;"
                    id="drop-zone">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: #9ca3af; margin-bottom: 12px; display: block;"></i>
                    <p style="color: #6b7280; margin: 0 0 8px 0;">Arraste a nova imagem aqui ou clique para selecionar</p>
                    <p style="color: #9ca3af; font-size: 12px; margin: 0;">PNG, JPG, GIF até 2MB</p>
                    <input type="file" name="image" id="image-input" accept="image/*"
                        style="display: none;">
                </div>
                <div id="image-preview" style="margin-top: 16px; display: none;">
                    <img id="preview-img" src="" alt="Preview" style="max-width: 200px; border-radius: 8px;">
                </div>
                @error('image')
                <p style="color: #dc2626; margin-top: 6px; font-size: 13px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div style="margin-bottom: 32px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}
                        style="width: 18px; height: 18px; cursor: pointer; accent-color: #3b82f6;">
                    <span style="color: #1f2937; font-weight: 600;">Categoria Ativa</span>
                </label>
                <p style="color: #6b7280; font-size: 12px; margin-top: 8px;">Categorias ativas aparecem na loja</p>
            </div>

            <!-- Botões -->
            <div style="display: flex; justify-content: flex-start; gap: 12px;">
                <button type="submit" style="background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer; min-width: 150px;">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
                <a href="{{ route('admin.categories.index') }}" style="background: #e5e7eb; color: #1f2937; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; text-align: center; min-width: 120px;">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
// Upload de imagem com preview
const dropZone = document.getElementById('drop-zone');
const imageInput = document.getElementById('image-input');
const imagePreview = document.getElementById('image-preview');
const previewImg = document.getElementById('preview-img');

dropZone.addEventListener('click', () => imageInput.click());

// Drag and drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.style.borderColor = '#3b82f6';
    dropZone.style.backgroundColor = '#f8faff';
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
        showPreview(files[0]);
    }
});

imageInput.addEventListener('change', (e) => {
    if (e.target.files.length > 0) {
        showPreview(e.target.files[0]);
    }
});

function showPreview(file) {
    const reader = new FileReader();
    reader.onload = (e) => {
        previewImg.src = e.target.result;
        imagePreview.style.display = 'block';
    };
    reader.readAsDataURL(file);
}
</script>
@endsection