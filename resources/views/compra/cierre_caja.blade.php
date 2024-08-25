<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cierre de Caja</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            padding: 20px;
        }
        .card-title {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .total-ventas, .total-compras, .total-con-extra {
            font-size: 1.25rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <h2 class="card-title">Cierre de Caja</h2>
                    <p class="total-ventas">Entrada: <strong>{{ number_format($totalVentas, 0) }} Gs</strong></p>
                    <p class="total-compras">Salida: <strong>{{ number_format($totalCompras, 0) }} Gs</strong></p>

                    <form method="GET" action="{{ route('compras.cierre_caja') }}">
                        <div class="form-group">
                            <label for="monto_extra">Monto Extra:</label>
                            <input type="number" step="0.01" name="monto_extra" id="monto_extra" class="form-control" value="{{ $montoExtra }}">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Calcular con Monto Extra</button>
                    </form>
                    
                    @if($montoExtra > 0)
                        <p class="total-con-extra mt-4">Total Compras con Monto Extra: <strong>{{ number_format($totalComprasConExtra, 2, ',', '.') }} $</strong></p>
                    @endif
                    

                    <a href="{{'/panel'}}" class="btn btn-secondary mt-3">Volver al Inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
