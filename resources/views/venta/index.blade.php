@extends('template')

@section('title', 'Ver Ventas')

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
        <h1 class="mt-4 text-center">Compras</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active"><a href="{{ route('panel') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Compras</li>
        </ol>
        <div class="mb-4">
            <a href="{{route('ventas.create')}}"><button type="button" class="btn btn-primary">Añadir Nueva
                    Compra</button></a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-4"></i>
                Tabla Compras
            </div>
            <div class="card-body">
                <table id="datatablesSimple", class="table table-striped">
                    <thead>
                        <tr>
                            <th>Codigo:</th>
                            <th>Nombre del Producto:</th>
                            <th>Fecha:</th>
                            <th>Precio de Compra:</th>
                            <th>Acciones:</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas as $item)
                            <tr>
                                <td>
                                    <p class="text-muted mb-0">{{ $item->codigo }}</p>
                                </td>
                                <td>
                                    <p class="text-muted mb-0">{{ $item->nombre_producto }}</p>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->fecha_hora)->format('d-m-Y') }}
                                        
                                </td>
                                <td>
                                    {{$item->precio_compra}}
                                </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                    @if ($item->estado == 1)
                                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                            data-bs-target="#confirmModal-{{ $item->id }}">En venta</button>
                                    @else
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#confirmModal-{{ $item->id }}">Vendido</button>
                                    @endif
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
                                            {{ $item->estado == 1 ? 'Vender este Producto?' : 'Restaurar esta Venta?' }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Cerrar</button>
                                            <form action="{{ route('ventas.destroy', ['venta' => $item->id]) }}"
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
