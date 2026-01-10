@extends('layouts.main')

@section('title', 'Nova Categoria - Admin')

@section('content')
<div style="background-color: #f3f4f6; padding: 30px 20px;">
    <div style="max-width: 800px; margin: 0 auto;">
        <!-- Header -->
        <div style="margin-bottom: 30px;">
            <a href="{{ route('admin.categories.index') }}" style="color: #3b82f6; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <h1 style="font-size: 32px; font-weight: 700; color: #1f2937; margin: 0;">Nova Categoria</h1>
            <p style="color: #6b7280; font-size: 14px; margin-top: 8px;">Crie uma nova categoria de produtos</p>
        </div>

        <!-- Form -->
        <div style="background: white; border-radius: 8px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nome -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                        Nome da Categoria *
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box;"
                        placeholder="Digite o nome da categoria">
                    @error('name')
                    <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                        Descrição
                    </label>
                    <textarea name="description" rows="4"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; box-sizing: border-box; font-family: inherit;"
                        placeholder="Digite a descrição da categoria">{{ old('description') }}</textarea>
                    @error('description')
                    <p style="color: #dc2626; margin-top: 4px; font-size: 12px;">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Imagem -->
                <div style="margin-bottom: 24px;">
                    <label style="display: block; color: #1f2937; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                        Imagem da Categoria
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
                        <span style="color: #1f2937; font-weight: 600; font-size: 14px;">Categoria Ativa</span>
                    </label>
                    <p style="color: #6b7280; font-size: 12px; margin-top: 8px;">Categorias ativas aparecem na loja</p>
                </div>

                <!-- Botões -->
                <div style="display: flex; gap: 12px;">
                    <button type="submit" style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; border: none; font-weight: 600; cursor: pointer; flex: 1;">
                        <i class="fas fa-save"></i> Criar Categoria
                    </button>
                    <a href="{{ route('admin.categories.index') }}" style="background-color: #e5e7eb; color: #1f2937; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; text-align: center; flex: 1;">
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

dropZone.addEventListener('click', () => imageInput.click());

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
