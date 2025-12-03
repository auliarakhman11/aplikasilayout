<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        table, th, td {
            border: 3px solid;
            border-collapse: collapse;
            font-size: 14px;
            }

            td {
                padding: 10px;
            }
    </style>
</head>
<body>
    <table>
        <tbody>            
        @foreach ($cell->rak as $r)
            @foreach ($r->pallet as $p)
            <tr>
                <td style="font-size: 40px;"><b>{{ $cell->block->nm_block }}</b></td>
                <td style="font-size: 40px;"><b>{{ $cell->nm_cell }}</b></td>
                <td style="font-size: 40px;"><b>{{ $r->nm_rak }}</b></td>
                <td style="font-size: 40px;"><b>{{ $p->nm_pallet }}</b></td>
                <td>{!! DNS2D::getBarcodeHTML("$p->id", 'QRCODE', '3','3') !!}</td>
                {{-- <td>{{ $dt_id }}</td> --}}
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
        @endforeach            
        </tbody>
    </table>
</body>
</html>