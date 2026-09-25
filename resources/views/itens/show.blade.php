@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Registro</h3>
        <a href="/itens" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>
    <div class="card-body">
        <p><strong>Origem:</strong> {{ $item->origem }}</p>
        <p><strong>Destino:</strong> {{ $item->destino }}</p>
        <p><strong>NºUSP:</strong> {{ $item->codpes }}</p>
        <p><strong>Nome:</strong> {{ $item->nome }}</p>
        <p><strong>Tipo do Documento:</strong> {{ $item->tipo_documento }}</p>
        <p><strong>Documento:</strong> {{ $item->documento }}</p>
        <p><strong>Patrimônio:</strong> {{ $item->patrimonio }}</p>
        <p><strong>Número de Série:</strong> {{ $item->numero_serie }}</p>
        <p><strong>Observação:</strong> {{ $item->observacao }}</p>

        {{-- Fotos existentes --}}
        @if($item->fotos->isNotEmpty())
        <div class="mt-4">
            <h5>Fotos</h5>
            <div class="foto-gallery" id="galeria">
                @foreach($item->fotos as $foto)
                <div class="foto-thumb">
                    <img src="{{ $foto->base64 }}"
                        class="foto-thumb-img"
                        data-index="{{ $loop->index }}"
                        style="cursor: pointer;"
                        alt="Foto">
                    @can('admin')
                    <form action="/fotos/{{ $foto->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-danger foto-thumb-remove"
                                onclick="return confirm('Remover foto?')"
                                title="Remover">✕</button>
                    </form>
                    @endcan
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="mt-3 d-flex gap-2">
            @can('admin')
            <a href="/itens/{{ $item->id }}/edit" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <form action="/itens/{{ $item->id }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza?')">
                    <i class="fas fa-trash"></i> Apagar
                </button>
            </form>
            @endcan
        </div>
    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox" class="d-none"
     style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.85); z-index: 9999;
            display: flex; align-items: center; justify-content: center;">
    <button onclick="fecharLightbox()" class="btn btn-light"
            style="position: absolute; top: 0; right: 0; margin: 1rem;">✕</button>
    <button onclick="navLightbox(-1)" class="btn btn-light"
            style="position: absolute; left: 0; margin-left: 1rem;">‹</button>
    <img id="lightbox-img" src="" style="max-height: 85vh; max-width: 85vw; object-fit: contain;">
    <button onclick="navLightbox(1)" class="btn btn-light"
            style="position: absolute; right: 0; margin-right: 1rem;">›</button>
</div>
@endsection

@section('javascripts_bottom')
  @parent
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fotos = @json($item->fotos->map(fn($f) => $f->base64)->values());
    let fotoAtual = 0;

    document.querySelectorAll('.foto-thumb-img').forEach(img => {
        img.addEventListener('click', function() {
            fotoAtual = parseInt(this.dataset.index);
            abrirLightbox(fotoAtual);
        });
    });

    function abrirLightbox(index) {
        document.getElementById('lightbox-img').src = fotos[index];
        document.getElementById('lightbox').classList.remove('d-none');
        document.getElementById('lightbox').style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Evita scroll da página
    }

    function fecharLightbox() {
        document.getElementById('lightbox').classList.add('d-none');
        document.getElementById('lightbox').style.display = 'none';
        document.body.style.overflow = ''; // Restaura scroll
    }

    function navLightbox(dir) {
        fotoAtual = (fotoAtual + dir + fotos.length) % fotos.length;
        document.getElementById('lightbox-img').src = fotos[fotoAtual];
    }

    document.getElementById('lightbox').addEventListener('click', function(e) {
        if (e.target === this) fecharLightbox();
    });

    // Torna as funções globais para os onclick
    window.abrirLightbox = abrirLightbox;
    window.fecharLightbox = fecharLightbox;
    window.navLightbox = navLightbox;
});
</script>
@endsection