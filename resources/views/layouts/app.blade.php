@extends('laravel-usp-theme::master')

{{-- Blocos do laravel-usp-theme --}}
{{-- Ative ou desative cada bloco --}}

{{-- Target:card-header; class:card-header-sticky --}}
@include('laravel-usp-theme::blocos.sticky')

{{-- Target: button, a; class: btn-spinner, spinner --}}
@include('laravel-usp-theme::blocos.spinner')

{{-- Target: table; class: datatable-simples --}}
@include('laravel-usp-theme::blocos.datatable-simples')

{{-- Fim de blocos do laravel-usp-theme --}}

@section('title')
  @parent 
@endsection

@section('styles')
  @parent
  <style>
    .foto-gallery {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        padding: 8px 8px 0 0; /* espaço pro botão não ser cortado nem encostar em nada */
    }
    .foto-thumb {
        position: relative;
        width: 100px;
        height: 100px;
    }
    .foto-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        display: block;
    }
    .foto-thumb form {
        display: contents; /* o form não interfere no layout, só o botão dentro dele */
    }
    .foto-thumb-remove {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 24px;
        height: 24px;
        padding: 0;
        line-height: 1;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        z-index: 2;
    }
  </style>
@endsection

@section('javascripts_bottom')
  @parent
  <script>
    // Seu código .js
  </script>
@endsection