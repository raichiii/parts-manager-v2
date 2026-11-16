<!doctype html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style type="text/css">
        @font-face {
            font-family: ipag;
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path("fonts/ipag.ttf") }}') format('truetype');
        }

        @font-face {
            font-family: ipag;
            font-style: bold;
            font-weight: bold;
            src: url('{{ storage_path("fonts/ipag.ttf") }}') format('truetype');
        }

        body {
            font-family: ipag !important;
        }

        * {
            font-size: 20px
        }

        div {
            text-align: center;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border: 1px solid #333;
        }

        table td {
            word-break: break-all;
            word-wrap: break-word;
            font-size: 16px;
        }

        th {
            font-size: 16px;
        }

        .header li {
            list-style: none;
            margin-left: 20px;
            margin-right: 20px;
            margin-bottom: 20px;
            display: inline-block;
        }

        .l {
            width: 22.5%;
        }

        .r {
            width: 22.5%;
        }

        .c {
            width: 40%;
        }

        .large {
            font-size: larger;
        }

        .right {
            text-align: right;
        }

        .left {
            text-align: left;
        }

        .center {
            text-align: center;
        }

        .huge {
            font-size: 50px;
        }

    </style>
</head>

<body>
    <div class="right">No.177<br><br></div>
    <div>
        <ul class="header">
            <li class="l">
                <br>
                <div class="right">
                    <div class="large">
                        {{$supplierName}}　御中<br>
                        @if($customerName)
                        {{$customerName}} 様<br>
                        @endif
                    </div>
                    <div class="left" style="font-size: 16px;">
                        TEL {{$phone}}<br>
                        FAX {{$fax}}
                    </div>
                </div>
            </li>
            <li class="c">
                <div class="center huge">注文書</div><br><br>
                希望納期より遅れる品があれば、ご一報ください。<br>
            </li>
            <li class="r">
                <div class="left">
                    <div class="large left">{{$date}}<br></div>
                    {{ env('COMPANY_NAME', '！！会社名未設定！！') }}<br>
                    豊中市蛍池西町2-14-14<br>
                    TEL {{ env('COMPANY_PHONE_NUMBER', '') }}<br>
                    FAX {{ env('COMPANY_FAX_NUMBER', '') }}<br>
                    担当: {{auth()->user()->name}}<br>
                </div>
            </li>
        </ul>
    </div>
    <div>
        <table border="1" cellspacing="0">
            <thead>
                <tr>
                    <th style="width: 2%;">No</th>
                    <th style="width: 6%;">注文番号</th>
                    <th style="width: 6%;">品名</th>
                    <th style="width: 14%;">型式</th>
                    <th style="width: 10%;">メーカー</th>
                    <th style="width: 8%;">注文数</th>
                    <th style="width: 8%;">希望納期</th>
                    <th style="width: 6%;">商品コード</th>
                    <th style="width: 10%;">備考</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requestedLists as $requestedList)
                <tr>
                    <td class="right">{{ $loop->index + 1 }}</td>
                    <td class="center">{{ $requestedList['order_no'] }}</td>
                    <td class="center" style="font-size: 16px;">{{ $requestedList['name'] }}</td>
                    <td class="center">{{ $requestedList['model_no'] }}</td>
                    <td class="center">{{ $requestedList['maker'] }}</td>
                    <td class="center">{{ $requestedList['number'] }}</td>
                    <td class="center">{{ $requestedList['purchase_at'] }}</td>
                    <td class="center">{{ $requestedList['code'] }}</td>
                    <td class="center">{{ $requestedList['note'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
