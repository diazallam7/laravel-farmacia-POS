@extends('template')

@section('title', 'Editar producto')

@push('css')
    <style>
        #descripcion {
            resize: none;
        }
    </style>
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Pagar Interes</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('interes.index') }}">Pago de Interes</a></li>
            <li class="breadcrumb-item active">Pagar Interes</li>
        </ol>
        <div class="contriner w-100 border border-3 border-primary rounded p-4 mt-3">
                <form action="{{ route('productos.update2', $producto->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-3">
                        <div class="col-md-6 mb-2">
                            <label for="monto_interes" class="form-label">Interes a Pagar:</label>
                            <input type="text" name="monto_interes" id="monto_interes" class="form-control"
                                value="{{ old('monto_interes', $producto->monto_interes) }}">
                            @error('monto_interes')
                                <small class="text-danger">{{ '*' . $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="fecha_vencimiento" class="form-label">Fecha de Pago:</label>
                            <input type="date" name="fecha_vencimiento" id="fecha_vencimiento" class="form-control"
                                >
                            @error('fecha_vencimiento')
                                <small class="text-danger">{{ '*' . $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-12 text-center">
                            <button type="sumbit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
                </form>
        </div>
    </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush
