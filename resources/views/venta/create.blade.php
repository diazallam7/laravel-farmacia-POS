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
        <h1 class="mt-4 text-center">Realizar Venta</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Ventas</a></li>
            <li class="breadcrumb-item active">Realizar Venta</li>
        </ol>
    </div>

    <form action="{{ route('ventas.store') }}" method="post">
        @csrf
        <div class="container mt-4">
            <div class="row gy-4">

                <!--Venta Producto -->
                <div class="col-md-8">
                    <div class="text-white bg-primary p-1 text-center">
                        Detalles de la Venta
                    </div>
                    <!--Producto-->
                    <div class="p-3 border border-3 border-primary">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <select name="producto_id" id="producto_id" class="form-control selectpicker"
                                    data-live-search="true" data-size="1" title="Buscar Producto">
                                    @foreach ($productos as $item)
                                        <option value="{{ $item->id }}-{{ $item->stock }}-{{ $item->precio_venta }}">
                                            {{ $item->codigo . ' ' . $item->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('producto_id')
                                    <small class="text-danger">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end mb-4">
                                <div class="col-md-6 mb-2">
                                    <div class="row">
                                        <label for="stock" class="form-label col-sm-4">En Stock:</label>
                                        <div class="col-sm-8">
                                            <input disabled id="stock" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!--Cantidad-->

                            <div class="col-md-4 mb-2">
                                <label for="cantidad" class="form-control">Cantidad:</label>
                                <input type="number" name="cantidad" id="cantidad" class="form-control">
                            </div>

                            <!--Precio de Venta-->

                            <div class="col-md-4 mb-2">
                                <label for="precio_venta" class="form-control">Precio de Venta:</label>
                                <input disabled type="number" name="precio_venta" id="precio_venta" class="form-control"
                                    step="0.1">
                            </div>

                            <!--Descuento-->

                            <div class="col-md-4 mb-2">
                                <label for="descuento" class="form-control">Descuento:</label>
                                <input type="number" name="descuento" id="descuento" class="form-control">
                            </div>

                            <div class="col-md-12 mb-2 mt-2 text-center">
                                <button id="btn_agregar" class="btn btn-primary" type="button">Agregar</button>
                            </div>

                            <!--Tabla detalle venta-->
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="tabla_detalle" class="table table-hover">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Producto</th>
                                                <th>Cantidad</th>
                                                <th>Precio Venta</th>
                                                <th>Descuento</th>
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
                                                <th colspan="2"><input type="hidden" name="total" value="0"
                                                        id="inputTotal"><span id="total">0</span></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12 mb-2">
                                <button id="cancelar" type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal">
                                    Cancelar Venta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Venta-->
                <div class="col-md-4">
                    <div class="text-white bg-success p-1 text-center">
                        Datos Generales
                    </div>
                    <div class="p-3 border border-3 border-success">
                        <div class="row">
                            <!--cliente-->
                            <div class="col-md-12 mb-2">
                                <label for="cliente_id" class="form-label">Cliente:</label>
                                <select name="cliente_id" id="cliente_id" class="form-control selectpicker show-tick"
                                    data-live-search="true" title="Selecciona" data-size="2">
                                    @foreach ($clientes as $item)
                                        <option value="{{ $item->id }}">{{ $item->persona->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                    <small class="text-danger"></small>
                                @enderror
                            </div>
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
                                    <small class="text-danger">{{ '*' . $message }}</small>
                                @enderror
                            </div>
                            <!--Tipo Comprobante-->
                            <div class="col-md-12 mb-2">
                                <label for="numero_comprobante" class="form-label">Ingrese el Nro de
                                    Comprobante:</label>
                                <input required type="text" name="numero_comprobante" id="numero_comprobante"
                                    class="form-control">
                                @error('tipo_comprobante')
                                    <small class="text-danger">{{ '*' . $message }}</small>
                                @enderror
                            </div>

                            <!--Fecha-->
                            <div class="col-md-6 mb-2">
                                <label for="fecha" class="form-label">Fecha:</label>
                                <input readonly type="date" name="fecha" id="fecha"
                                    class="form-control border-success" value="<?php echo date('Y-m-d'); ?>">

                                <?php
                                use Carbon\Carbon;
                                
                                $fecha_hora = Carbon::now()->toDateTimeString();
                                ?>

                                <input type="hidden" name="fecha_hora" value="{{ $fecha_hora }}">
                            </div>
                            <input type="hidden" name="user_id" value="{{auth()->user()->id}}">
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
                        Seguros que quieres cancelar la venta?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button id="btnCancelarVenta" type="button" class="btn btn-primary"
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