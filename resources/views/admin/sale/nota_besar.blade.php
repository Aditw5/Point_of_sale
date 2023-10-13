<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota PDF</title>

    <style>
        table td {
            /* font-family: Arial, Helvetica, sans-serif; */
            font-size: 14px;
        }
        table.data td,
        table.data th {
            border: 1px solid #ccc;
            padding: 5px;
        }
        table.data {
            border-collapse: collapse;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr>
            <td rowspan="4" width="60%">
                <img src="{{ public_path($setting->path_logo) }}" alt="{{ $setting->path_logo }}" width="120">
                <br>
                {{ $setting->address }}
                <br>
                <br>
            </td>
            <td>Date</td>
            <td>: {{ tanggal_indonesia(date('Y-m-d')) }}</td>
        </tr>
        <tr>
            <td>Member Code</td>
            <td>: {{ $sale->member->member_code ?? '' }}</td>
        </tr>
    </table>

    <table class="data" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Code</th>
                <th>Name</th>
                <th>Price</th>
                <th>Amount</th>
                <th>Discont</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($detail as $key => $item)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td>{{ $item->produk->product_name }}</td>
                    <td>{{ $item->produk->product_code }}</td>
                    <td class="text-right">{{ format_uang($item->sell_price) }}</td>
                    <td class="text-right">{{ format_uang($item->amount) }}</td>
                    <td class="text-right">{{ $item->discont }}</td>
                    <td class="text-right">{{ format_uang($item->subtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-right"><b>Total Price</b></td>
                <td class="text-right"><b>{{ format_uang($sale->total_price) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Discont</b></td>
                <td class="text-right"><b>{{ format_uang($sale->discont) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Total Pay</b></td>
                <td class="text-right"><b>{{ format_uang($sale->pay) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Accepted</b></td>
                <td class="text-right"><b>{{ format_uang($sale->accepted) }}</b></td>
            </tr>
            <tr>
                <td colspan="6" class="text-right"><b>Return</b></td>
                <td class="text-right"><b>{{ format_uang($sale->accepted - $sale->pay) }}</b></td>
            </tr>
        </tfoot>
    </table>

    <table width="100%">
        <tr>
            <td><b>Thank you for shopping and see you soon</b></td>
            <td class="text-center">
                Casier
                <br>
                <br>
                {{ auth()->user()->name }}
            </td>
        </tr>
    </table>
</body>
</html>