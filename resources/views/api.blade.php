@extends('layout.index')

@section('titulo', 'Envio para API')

@section('conteudo')
    <p class="lead text-muted mb-3">Foram enviados {{$ok}} interações de Chat com sucesso.</p>
    <p class="lead text-muted mb-3">Tivemos {{$erro}} erros no envio para API da CallMiner</p>
    
    <a href="/home" class="btn btn-mex">Voltar</a>
    
@endsection