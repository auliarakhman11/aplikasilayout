<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Layout</title>


    <style>
        table,
        th,
        td,
        th {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>

    <style>
        div.scroll_horizontal table {
            display: inline-block;

        }

        /* div.scroll_horizontal {
            overflow-y: scroll;
            overflow-x: auto;
            white-space: nowrap;
            height: 500px;
        } */
    </style>
</head>

<body onload="window.print()">

    <div class="container-fluid">
        {{-- <div class="container">
            <img class="float-start" width="50 px;" src="{{ asset('img') }}/cp.jpeg">
            <center>
                <h4 style="margin-top: -50px;">PT. CHAROEN POKPHAND INDONESIA</h4>
            </center><br>
            <center>
                <h4 style="margin-top: -40px;">PLANT BANDUNG</h4>
            </center>

            <hr style="border-top: 1px;">
        </div> --}}


        <div class="container">
            <div class="scroll_horizontal">

                @foreach ($block as $b)
                    <table border="1px solid" style="font-size: 6px;">
                        <thead>
                            <tr>
                                <th style="background-color: tomato"><b>{{ $b->nm_block }}</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($b->cell as $c)
                                <tr>
                                    <td style="background-color: skyblue"><b>{{ $c->nm_cell }}</b>
                                        {{-- <a target="_black" href="{{ route('generateQr', $c->id) }}"
                                                        class="btn btn-xs btn-primary"><i class='bx bx-qr'></i></a> --}}
                                    </td>
                                </tr>
                                @foreach ($c->rak as $r)
                                    <tr style="background-color: {{ $r->warna ? $r->warna : '#FFFFFF' }}">
                                        <td>{{ $r->nm_rak }}</td>
                                    </tr>
                                @endforeach
                            @endforeach

                        </tbody>
                    </table>
                @endforeach



            </div>

        </div>


    </div>

    {{-- <center></center> --}}

</body>

</html>
