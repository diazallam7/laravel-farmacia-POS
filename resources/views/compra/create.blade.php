@extends('template')

@section('title', 'Crear Compra')

@push('css')
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Crear Venta</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Ventas</a></li>
            <li class="breadcrumb-item active">Crear Venta</li>
        </ol>
    </div>
    <form action="{{route('compras.store')}}" method="post">
        @csrf
        <div class="container mt-4">
            <div class="row gy-4">

                <!--Compra Producto -->
                <div class="col-md-8">
                    <div class="text-white bg-primary p-1 text-center">
                        Detalles de la Venta
                    </div>
                    <!--Producto-->
                    <div class="p-3 border border-3 border-primary">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <select name="producto_id" id="producto_id" class="form-control selectpicker"
                                    data-live-search="true" data-size="1" title="Buscar Producto" onchange="mostrarValores()">
                                    @foreach ($productos as $item)
                                        <option value="{{ $item->id }}" data-precio="{{$item->precio_compra}}">{{ $item->codigo.' '.$item->nombre_del_producto }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('producto_id')
                                    <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>

                            <!--Cantidad-->

                            <div class="col-md-4 mb-2">
                                <label for="cantidad" class="form-control">Cantidad:</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control">
                            </div>

                            <!--Precio de Compra-->

                            <div class="col-md-4 mb-2">
                                <label for="precio_compra" class="form-control">Precio de Compra:</label>
                                <input disabled type="number" name="precio_compra" id="precio_compra" class="form-control"
                                    step="0.1" value="" readonly>
                            </div>

                            <!--Precio de Venta-->

                            <div class="col-md-4 mb-2">
                                <label for="precio_venta" class="form-control">Precio de Venta:</label>
                                <input type="number" name="precio_venta" id="precio_venta" class="form-control"
                                    step="0.1">
                            </div>

                            <div class="col-md-12 mb-2 mt-2 text-center">
                                <button id="btn_agregar" class="btn btn-primary" type="button">Agregar</button>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tabla_detalle" class="table table-hover">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio Compra</th>
                                                <th>Precio Venta</th>
                                                <th>Subtotal</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">Sumas</th>
                                                <th colspan="2"><span id="sumas">0</span></th>
                                            </tr>
                                            <tr>
                                                <th></th>
                                                <th colspan="4">Total</th>
                                                <th colspan="2"><input type="hidden" name="total" value="0" id="inputTotal"><span id="total">0</span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <button id="cancelar" type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Cancelar Compra
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Detalles de los Producto-->
                <div class="col-md-4">
                    <div class="text-white bg-success p-1 text-center">
                        Datos Generales
                    </div>
                    <div class="p-3 border border-3 border-success">
                        <div class="row">
                            <!--Tipo Comprobante-->
                            <div class="col-md-12 mb-2">
                                <label for="comprobante_id" class="form-label">Comprobante:</label>
                                <select name="comprobante_id" id="comprobante_id"
                                    class="form-control selectpicker show-tick" title="Selecciona">
                                    @foreach ($comprobantes as $item)
                                    
                                        <option value="{{ $item->id }}">{{ $item->tipo_comprobante }}</option>
                                    @endforeach
                                </select>
                                @error('comprobante_id')
                                <small class="text-danger">{{'*'.$message}}</small>
                            @enderror
                            </div>
                            <!--Tipo Comprobante-->
                            <div class="col-md-12 mb-2">
                                <label for="numero_comprobante" class="form-label">Ingrese el Nro de
                                    Comprobante:</label>
                                <input required type="text" name="numero_comprobante" id="numero_comprobante"
                                    class="form-control">
                                @error('numero_comprobante')
                                    <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>
                            <!--Fecha-->
                            <div class="col-md-12 mb-2">
                                <label for="fecha" class="form-label">Fecha:</label>
                                <input readonly type="date" name="fecha" id="fecha"
                                    class="form-control border-success" value="<?php echo date('Y-m-d'); ?>">

                                    <?php
                                    use Carbon\Carbon;

                                    $fecha_hora = Carbon::now()->toDateTimeString();
                                    ?>

                                    <input type="hidden" name="fecha_hora" value="{{$fecha_hora}}">
                            </div>

                            <!--Guardar-->
                            <div class="col-md-12 mb-2 text-center">
                                <button id="guardar" type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Modal cancelar compra -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal de Confirmacion</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Seguros que quieres cancelar la compra?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btnCancelarcompra" type="button" class="btn btn-primary"
                            data-bs-dismiss="modal">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#btn_agregar').click(function() {
                agregarProducto();
            });

            $('#btnCancelarcompra').click(function() {
                cancelarCompra();
            });

            desabilitarBotones();

        });

        let cont = 0;
        let subtotal = [];
        let sumas = 0;
        let total = 0;

        //Valores de los campos

        function cancelarCompra() {
            $('#tabla_detalle > tbody').empty();
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

        function desabilitarBotones(){
            if (total == 0) {
                $('#guardar').hide();
                $('#cancelar').hide();
            } else {
                $('#guardar').show();
                $('#cancelar').show();
            }
        }

        function mostrarValores() {
    let precio = $('#producto_id').find(':selected').data('precio');
    $('#precio_compra').val(precio);
}

        function agregarProducto() {
            idProducto = $('#producto_id').val();
            nameProducto = ($('#producto_id option:selected').text()).split(' ')[1];
            cantidad = $('#cantidad').val();
            precioCompra = $('#precio_compra').val();
            precioVenta = $('#precio_venta').val();

            if (nameProducto != '' && cantidad != '' && precioCompra != '' && precioVenta != '') {


                if (parseInt(cantidad) > 0 && (cantidad % 1 == 0) && parseFloat(precioCompra) > 0 && parseFloat(
                        precioVenta)) {
                    if (parseFloat(precioVenta) > parseFloat(precioCompra)) {
                        //calculo de los valores
                        subtotal[cont] = round(cantidad * precioVenta);
                        sumas += subtotal[cont];
                        total = round(sumas);

                        fila = '<tr id="fila' + cont + '">' +
                            '<th>' + (cont + 1) + '</th>' +
                            '<td><input type="hidden" name="arrayidProducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                            '<td><input type="hidden" name="arrayCantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                            '<td><input type="hidden" name="arrayprecioCompra[]" value="' + precioCompra + '">' + precioCompra + '</td>' +
                            '<td><input type="hidden" name="arrayprecioVenta[]" value="' + precioVenta + '">' + precioVenta + '</td>' +
                            '<td>' + subtotal + '</td>' +
                            '<td><button class="btn btn-secondary" type="button" onClick="eliminarProducto(' + cont +
                            ')"><i class="fa    -solid fa-trash"></i></button></td>' +
                            '</tr>';

                        $('#tabla_detalle').append(fila);
                        limpiarCampos();
                        cont++;
                        desabilitarBotones();

                        $('#sumas').html(sumas);
                        $('#total').html(total);
                        $('#inputTotal').val(total);
                    } else {
                        showModal('Precio de compra/venta incorrecto');
                    }

                } else {
                    showModal('Valores Inconrrectos');
                }

            } else {
                showModal('Faltan campos por llenar');
            }
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


        function limpiarCampos() {
            select = $('#producto_id');
            select.selectpicker();
            select.selectpicker('val', '');
            $('#cantidad').val('');
            $('#precio_compra').val('');
            $('#precio_venta').val('');

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
