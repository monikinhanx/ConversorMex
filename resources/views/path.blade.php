@extends('layout.index')

@section('titulo', 'Caminho dos Arquivos')

@section('conteudo')
    <form action="/metadados" method="POST">
    
        @csrf

        <div class="form-group">
            <label for="path" class="lead text-muted mb-3">Informe o caminho da pasta onde estão as Interações de Chat:</label>
            <input class="form-control mb-3" type="text" name="path" id="path" placeholder="Ex.: C:\Users\user\Desktop" required>
        </div>
        <a href="/home" class="btn btn-mex">Voltar</a>
        <button type="submit" class="btn btn-mex">Próximo</button>
    </form>
@endsection