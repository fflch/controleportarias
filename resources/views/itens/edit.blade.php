@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Editar Registro</h3>
        <a href="/itens/{{ $item->id }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="/itens/{{ $item->id }}">
            @csrf
            @method('PATCH')
            <div class="mb-3">
                <label class="form-label">Origem</label>
                <input type="text" class="form-control" name="origem" id="origem" value="{{ old('origem', $item->origem) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Destino</label>
                <input type="text" class="form-control" name="destino" id="destino" value="{{ old('destino', $item->destino) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">NºUSP</label>
                <input type="text" class="form-control" name="codpes" id="codpes" value="{{ old('codpes', $item->codpes) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" class="form-control" name="nome" id="nome" value="{{ old('nome', $item->nome) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Tipo do Documento</label>
                <input type="text" class="form-control" name="tipo_documento" id="tipo_documento" value="{{ old('tipo_documento', $item->tipo_documento) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Documento</label>
                <input type="text" class="form-control" name="documento" id="documento" value="{{ old('documento', $item->documento) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Patrimônio</label>
                <input type="text" class="form-control" name="patrimonio" id="patrimonio" value="{{ old('patrimonio', $item->patrimonio) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Número de Série</label>
                <input type="text" class="form-control" name="numero_serie" id="numero_serie" value="{{ old('numero_serie', $item->numero_serie) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Observação</label>
                <textarea class="form-control" name="observacao" id="observacao" rows="3">{{ old('observacao', $item->observacao) }}</textarea>
            </div>

            {{-- Upload de novas fotos --}}
            <div class="mb-3">
                <label class="form-label">Adicionar fotos</label>
                <div class="mb-2">
                    <video id="video" autoplay class="img-thumbnail w-100 mb-2 d-none" style="max-height: 200px;"></video>
                    <div class="d-flex gap-2 mb-2">
                        <button type="button" class="btn btn-secondary btn-sm" onclick="ligarWebcam()">
                            <i class="fas fa-camera"></i> Ligar Câmera
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="pararWebcam()">
                            Desligar
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="tirarFoto()" id="btnTirar" disabled>
                            Tirar Foto
                        </button>
                    </div>
                </div>
                <input type="file" class="form-control mb-2" id="arquivo" accept="image/*" multiple onchange="adicionarArquivos()">
                <div id="preview-fotos" class="d-flex flex-wrap gap-2 mt-2"></div>
                <div id="fotos-hidden"></div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Salvar
            </button>
        </form>

        {{-- Fotos existentes --}}
        @if($item->fotos->isNotEmpty())
        <div class="mt-4">
            <label class="form-label"><strong>Fotos cadastradas</strong></label>
            <div class="d-flex flex-wrap gap-2">
                @foreach($item->fotos as $foto)
                <div class="position-relative">
                    <img src="/fotos/{{ $foto->id }}"
                         class="img-thumbnail"
                         style="height: 100px; width: 100px; object-fit: cover;">
                    <form action="/fotos/{{ $foto->id }}" method="POST" class="position-absolute top-0 end-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Remover foto?')"
                                title="Remover">✕</button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<script>
let stream = null;
let fotosParaEnviar = [];

function atualizarPreviews() {
    const container = document.getElementById('preview-fotos');
    const hidden = document.getElementById('fotos-hidden');
    container.innerHTML = '';
    hidden.innerHTML = '';

    fotosParaEnviar.forEach((src, i) => {
        const div = document.createElement('div');
        div.className = 'position-relative';
        div.innerHTML = `
            <img src="${src}" class="img-thumbnail" style="height:100px;width:100px;object-fit:cover;">
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                onclick="removerFoto(${i})">✕</button>`;
        container.appendChild(div);

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'fotos[]';
        input.value = src;
        hidden.appendChild(input);
    });
}

function removerFoto(index) {
    fotosParaEnviar.splice(index, 1);
    atualizarPreviews();
}

function adicionarArquivos() {
    const input = document.getElementById('arquivo');
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            fotosParaEnviar.push(e.target.result);
            atualizarPreviews();
        };
        reader.readAsDataURL(file);
    });
}

function ligarWebcam() {
    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
        .then(s => {
            stream = s;
            const video = document.getElementById('video');
            video.srcObject = s;
            video.classList.remove('d-none');
            document.getElementById('btnTirar').disabled = false;
        })
        .catch(() => alert('Não foi possível acessar a webcam'));
}

function pararWebcam() {
    if (stream) {
        stream.getTracks().forEach(t => t.stop());
        document.getElementById('video').srcObject = null;
        document.getElementById('video').classList.add('d-none');
        document.getElementById('btnTirar').disabled = true;
    }
}

function tirarFoto() {
    const video = document.getElementById('video');
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    fotosParaEnviar.push(canvas.toDataURL('image/jpeg'));
    atualizarPreviews();
    pararWebcam();
}

window.addEventListener('beforeunload', pararWebcam);
</script>
@endsection