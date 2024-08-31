@extends('template')

@section('title', 'Crear Venta')\

@push('css')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Realizar Compra</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Compras</a></li>
            <li class="breadcrumb-item active">Realizar Compra</li>
        </ol>
    </div>

        <div class="contriner w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{ route('ventas.store') }}" method="post">
                @csrf
                <div class="row g-3">

                    <div class="col-md-6 mb-2">
                        <label for="codigo" class="form-label">Codigo:</label>
                        <input type="text" name="codigo" id="codigo" class="form-control"
                            value="{{ old('codigo', 'C') }}">
                        @error('codigo')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                    

                    <div class="col-md-6 mb-2">
                        <label for="nombre_producto" class="form-label">Nombre del Producto:</label>
                        <input type="text" name="nombre_producto" id="nombre_producto" class="form-control"
                        value="{{ old('nombre_producto') }}">
                        @error('nombre_producto')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="fecha_hora" class="form-label">Fecha:</label>
                        <input type="date" name="fecha_hora" id="fecha_hora" class="form-control"
                            value="{{ old('fecha_hora') }}">
                        @error('fecha_hora')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-2">
                        <label for="precio_compra" class="form-label">Precio de la Compra:</label>
                        <input type="text" name="precio_compra" id="precio_compra" class="form-control"
                            value="{{ old('precio_compra') }}">
                        @error('precio_compra')
                            <small class="text-danger">{{ '*' . $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-12 text-center">
                        <button type="sumbit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>

@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#producto_id').change(mostrarValores);


            $('#btn_agregar').click(function() {
                agregarProducto();
            })

            $('#btnCancelarVenta').click(function() {
                 cancelarCompra();
             });

             desabilitarBotones();


        });

        let cont = 0;
        let subtotal = [];
        let sumas = 0;
        let total = 0;

        function mostrarValores() {
            let $dataProducto = document.getElementById('producto_id').value.split('-');
            $('#stock').val($dataProducto[1]);
            $('#precio_venta').val($dataProducto[2]);
        }

        function agregarProducto() {
            let $dataProducto = document.getElementById('producto_id').value.split('-');
            let idProducto =$dataProducto[0];
            let nameProducto = $('#producto_id option:selected').text();
            let cantidad = $('#cantidad').val();
            let precioVenta = $('#precio_venta').val();
            let descuento = $('#descuento').val();
            let stock = $('#stock').val();

            if (descuento == ''){
                descuento = 0;
            }

            if (idProducto != '' && cantidad != '' ) {


                if (parseInt(cantidad) > 0 && (cantidad % 1 == 0) && parseFloat(descuento)>=0) {
                    
                    if (parseFloat(cantidad) <= parseFloat(stock)) {
                        if ( parseInt(descuento) < parseInt(cantidad) * parseFloat(precioVenta) ) {
                        //calculo de los valores
                        subtotal[cont] = round(cantidad * precioVenta - descuento);
                        sumas += subtotal[cont];
                        total = round(sumas);

                        fila = '<tr id="fila' + cont + '">' +
                            '<th>' + (cont + 1) + '</th>' +
                            '<td><input type="hidden" name="arrayidProducto[]" value="' + idProducto + '">' + nameProducto +
                            '</td>' +
                            '<td><input type="hidden" name="arrayCantidad[]" value="' + cantidad + '">' + cantidad +
                            '</td>' +
                            '<td><input type="hidden" name="arrayprecioVenta[]" value="' + precioVenta + '">' +
                            precioVenta + '</td>' +
                            '<td><input type="hidden" name="arrayDescuento[]" value="' + descuento + '">' +
                            descuento + '</td>' +
                            '<td>' + subtotal + '</td>' +
                            '<td><button class="btn btn-secondary" type="button" onClick="eliminarProducto(' + cont +
                            ')"><i class="fa-solid fa-trash"></i></button></td>' +
                            '</tr>';

                        $('#tabla_detalle').append(fila);
                        limpiarCampos();
                        cont++;
                        desabilitarBotones();

                        $('#sumas').html(sumas);
                        $('#total').html(total);
                        $('#inputTotal').val(total);

                    } else {
                        showModal('Descuento Incorrecto.');
                    }

                    } else {
                        showModal('Cantidad Inconrrecta.');
                    }

                } else {
                    showModal('Valores Inconrrectos');
                }

            } else {
                showModal('Faltan campos por llenar');
            }
        }

        function cancelarCompra() {
            $('#tabla_detalle  tbody').empty();
            fila = '<tr>' +
                '<th></th>' +
                '<th></th>' +
                '<th></th>' +
                '<th></th>' +
                '<th></th>' +
                '<th></th>' +
                '<th></th>' +
                '</tr>';
            $('#tabla_detalle').append(fila);

            cont = 0;
            subtotal = [];
            sumas = 0;
            total = 0;

            $('#sumas').html(sumas);
            $('#total').html(total);
            $('#inputTotal').val(total);

            limpiarCampos();
            desabilitarBotones();
        }

        function eliminarProducto(indice) {
            sumas -= round(subtotal[indice]);
            total = round(sumas);

            $('#sumas').html(sumas);
            $('#total').html(total);

            $('#fila' + indice).remove();
            desabilitarBotones();
            $('#inputTotal').val(total);
        }

        function round(num, decimales = 2) {
            var signo = (num >= 0 ? 1 : -1);
            num = num * signo;
            if (decimales === 0) //con 0 decimales
                return signo * Math.round(num);
            // round(x * 10 ^ decimales)
            num = num.toString().split('e');
            num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
            // x * 10 ^ (-decimales)
            num = num.toString().split('e');
            return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
        }

        function limpiarCampos() {
            select = $('#producto_id');
            select.selectpicker('val', '');
            $('#cantidad').val('');
            $('#precio_venta').val('');
            $('#descuento').val('');
            $('#stock').val('');

        }

        
        function desabilitarBotones(){
            if (total == 0) {
                $('#guardar').hide();
                $('#cancelar').hide();
            } else {
                $('#guardar').show();
                $('#cancelar').show();
            }
        }

        function showModal(message, icon = 'error') {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "error",
                title: message
            });
        }
    </script>
@endpush