@extends('template')

@section('title', 'Productos')

@push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@section('content')

    @if (session('success'))
        <script>
            // script para que slaga la alerta 
            let message = "{{ session('success') }}";
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: message
            });
        </script>
    @endif

    <div class="container-fluid px-4">
        <h1 class="mt-4 text-center">Pago de Interes</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Pago de Interes</li>
        </ol>
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-4"></i>
                Tabla de Interes
            </div>
            <div class="card-body">
                <table id="datatablesSimple", class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nro Boleta</th>
                            <th>Producto</th>
                            <th>Ultima Fecha de Pago</th>
                            <th>Proxima Fecha a Pagar</th>
                            <th>Interes</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($producto as $item)
                            <tr>
                                <td>
                                    {{ $item->codigo }}

                                </td>
                                <td>
                                    {{ $item->nombre_del_producto }}
                                </td>
                                <td>
                                    {{ $item->fecha_vencimiento }}
                                </td>
                                <td>
                                    <p> {{ $item->fecha_vencimiento == ''? 'No tiene': \Carbon\Carbon::parse($item->fecha_vencimiento)->addMonths(1)->format('Y-m-d') }}
                                    </p>
                                </td>
                                <td>
                                    {{ $item->precio_compra * 0.25 }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                        <form action="{{ route('interes.edit', ['intere' => $item]) }}">
                                            <button type="submit" class="btn btn-primary">Pagar</button>
                                        </form>
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#confirmModal1-{{ $item->id }}"
                                            onclick="loadModalData({{ $item->id }})">
                                            Retirar
                                        </button>


                                    </div>
                                </td>
                            </tr>
                            <!-- Botón para abrir el modal -->

                            <!-- Modal -->
                            <!-- Modal Structure -->
                            <div class="modal fade" id="confirmModal1-{{ $item->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Retirar el Articulo?</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                                <form action="{{ route('productos.showModal', $item->id) }}" method="post">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">Confirmar</button>
                                                </form>
                                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
    <script>
        function loadModalData(itemId) {
            $.ajax({
                url: '/productos/' + itemId + '/showModal',
                type: 'post',
                success: function(response) {
                    $('#confirmModal1-' + itemId).find('.modal-body').html(response);
                }
            });
        }
    </script>
@endpush
