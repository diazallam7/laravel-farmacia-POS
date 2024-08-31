@extends('template')

@section('title', 'Ventas')

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
        <h1 class="mt-4 text-center">Ventas</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Ventas</li>
        </ol>
        @can('crear-compra')
            <div class="mb-4">
                <a href="{{ route('compras.create') }}"><button type="button" class="btn btn-primary">Añadir Nueva
                        Venta</button></a>
            </div>
        @endcan


        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-4"></i>
                Tabla Ventas
            </div>
            <div class="card-body">
                <table id="datatablesSimple", class="table table-striped">
                    <thead>
                        <tr>
                            <th>Comprobante:</th>
                            <th>Nombre del Producto</th>
                            <th>Fecha y Hora:</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compras as $item)
                            <tr>
                                <td>
                                    <p class="fw-semibold mb-1">{{ $item->tipo_comprobante }}</p>
                                    <p class="text-muted mb-0">{{ $item->numero_comprobante }}</p>
                                </td>
                                <td>
                                    @if ($item->productos->isNotEmpty())
                                        <!-- Mostrar los nombres de los productos relacionados -->
                                        @foreach ($item->productos as $producto)
                                            <p >{{ $producto->nombre_del_producto }}</p>
                                        @endforeach
                                    @elseif ($item->ventas->isNotEmpty())
                                        <!-- Mostrar los nombres de los productos relacionados a las ventas -->
                                        @foreach ($item->ventas as $venta)
                                            @php
                                                $producto = \App\Models\Venta::find($venta->nombre_producto); // Suponiendo que el modelo Venta tiene un campo producto_id
                                            @endphp
                                            <p >{{ $venta->nombre_producto }}</p>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->fecha_hora)->format('d-m-Y') .
                                        ' ' .
                                        \Carbon\Carbon::parse($item->fecha_hora)->format('H:i') }}
                                </td>
                                <td>
                                    {{ $item->total }}
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                        @can('mostrar-compra')
                                            <form action="{{ route('compras.show', ['compra' => $item]) }}">
                                                <button type="submit" class="btn btn-success">Ver</button>
                                            </form>
                                        @endcan
                                        @can('eliminar-compra')
                                            <button type="button" class="btn btn-secondary" right; data-bs-toggle="modal"
                                                data-bs-target="#confirmModal-{{ $item->id }}">Eliminar</button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="confirmModal-{{ $item->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de Confirmacion
                                            </h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Quieres eliminar este registro?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                            <form action="{{ route('compras.destroy', ['compra' => $item->id]) }}"
                                                method="post">
                                                @method('DELETE')
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
@endpush
