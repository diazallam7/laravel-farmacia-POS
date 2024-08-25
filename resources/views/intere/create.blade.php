@extends('template')

@section('title', 'Crear productos')

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
    <h1 class="mt-4 text-center">Pago de Interes</h1>
    <form action="{{ route('productos.store') }}" method="post">
        @csrf
        <div class="container mt-4 d-flex justify-content-center">
            <div class="card p-4 shadow-sm" style="width: 50%;">
                <div class="text-white bg-primary p-2 text-center rounded">
                    <h4>Detalles de Pago</h4>
                </div>
                <div class="mb-3">
                    <label for="fecha_pago" class="form-label">Fecha de Entrega:</label>
                    <input type="date" name="fecha_pago" id="fecha_pago" class="form-control" value="{{ old('fecha_pago') }}">
                    @error('fecha_pago')
                        <small class="text-danger">{{ '*' . $message }}</small>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="fecha_un_mes_despues" class="form-label">Fecha Vencimiento:</label>
                    <input type="date" id="fecha_un_mes_despues" class="form-control" disabled>
                </div>

                <div class="mb-3">
                    <label for="precio_compra" class="form-label">Interés a Pagar:</label>
                    <input type="text" class="form-control" name="precio_compra" value="" >
                </div>
                <button type="submit" class="btn btn-primary">Pagar</button>
                
            </div>
        </div>
    </form>
</div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#fecha_pago').on('change', function() {
                var fechaSeleccionada = new Date($(this).val());
                if (!isNaN(fechaSeleccionada.getTime())) {
                    // Sumar un mes a la fecha seleccionada
                    fechaSeleccionada.setMonth(fechaSeleccionada.getMonth() + 1);

                    // Formatear la fecha para el input de tipo date
                    var year = fechaSeleccionada.getFullYear();
                    var month = ("0" + (fechaSeleccionada.getMonth() + 1)).slice(-2);
                    var day = ("0" + fechaSeleccionada.getDate()).slice(-2);

                    var fechaFormateada = year + '-' + month + '-' + day;

                    // Mostrar la fecha en el campo correspondiente
                    $('#fecha_un_mes_despues').val(fechaFormateada);
                }
            });
        });
    </script>
@endpush
