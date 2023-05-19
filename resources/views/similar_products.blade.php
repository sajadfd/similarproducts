<!DOCTYPE html>
<html>

<head>
    <title>Product Details</title>
    <style>
        /* Center content */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        /* Product details section */
        .product-details {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .product-image img {
            max-width: 200px;
            max-height: 200px;
            margin-right: 20px;
        }

        .product-info h2 {
            margin: 0;
            margin-bottom: 10px;
        }

        /* Similar products section */
        .similar-products {
            margin-bottom: 20px;
        }

        .similar-products h3 {
            margin: 0;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Product details section -->
        <div class="product-details">
            <div class="product-image">
            </div>
            <div class="product-info">
                <h1>original product is :"{{ $product->name }}"</h1>
                <p>Frequency: {{ $product->frequency }}</p>
                <p>request time: {{ $request_time }}</p>

            </div>
        </div>
        <!-- Similar products section -->
        <div class="similar-products">
            @if ($similarProducts->count() > 0)
                <h3>Similar Products:</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Frequency</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($similarProducts as $product)
                            <tr data-href="{{ route('products', ['productId' => $product->id]) }}">
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->frequency }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No similar products found.</p>
            @endif
        </div>
        <script>
            // Add click event listener to table rows with a data-href attribute
            document.querySelectorAll('tr[data-href]').forEach(row => {
                row.addEventListener('click', () => {
                    window.location.href = row.getAttribute('data-href');
                });
            });
        </script>
</body>

</html>
