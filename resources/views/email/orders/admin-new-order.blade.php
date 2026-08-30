<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        New SleepWell Order
    </title>
</head>

<body
    style="
        margin:0;
        padding:0;
        background:#f5f7fa;
        font-family:Arial,Helvetica,sans-serif;
        color:#212529;
    "
>

<table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    style="background:#f5f7fa;padding:30px 15px;"
>

<tr>
<td align="center">

<table
    width="700"
    cellpadding="0"
    cellspacing="0"
    style="
        max-width:700px;
        width:100%;
        background:#ffffff;
        border-radius:8px;
        overflow:hidden;
    "
>

<tr>
<td
    style="
        padding:25px;
        background:#212529;
        color:#ffffff;
    "
>

    <h1 style="margin:0;font-size:24px;">
        New SleepWell Order
    </h1>

    <p style="margin:8px 0 0;color:#dddddd;">
        Cash on Delivery Order
    </p>

</td>
</tr>


<tr>
<td style="padding:25px;">

    <h2 style="margin:0 0 15px;">
        Order {{ $order->order_number }}
    </h2>

    <p style="margin:0;color:#555555;line-height:1.7;">

        <strong>Customer:</strong>
        {{ $order->user->name }}

        <br>

        <strong>Email:</strong>
        {{ $order->user->email }}

        <br>

        <strong>Phone:</strong>
        {{ $order->address->phone ?? $order->user->phone }}

        <br>

        <strong>Status:</strong>
        {{ ucfirst($order->status) }}

        <br>

        <strong>Payment:</strong>
        Cash on Delivery

    </p>

</td>
</tr>


<tr>
<td style="padding:0 25px 25px;">

    <h3 style="margin:0 0 15px;">
        Ordered Products
    </h3>

    <table
        width="100%"
        cellpadding="8"
        cellspacing="0"
        style="border-collapse:collapse;"
    >

        <thead>

            <tr style="background:#f8f9fa;">

                <th align="left">
                    Product
                </th>

                <th align="left">
                    Package
                </th>

                <th align="left">
                    Warranty
                </th>

                <th align="center">
                    Qty
                </th>

                <th align="right">
                    Total
                </th>

            </tr>

        </thead>


        <tbody>

        @foreach($order->items as $item)

            <tr>

                <td style="border-bottom:1px solid #eeeeee;">
                    {{ $item->product_name }}
                </td>

                <td style="border-bottom:1px solid #eeeeee;">
                    {{ $item->package_name }}
                </td>

                <td style="border-bottom:1px solid #eeeeee;">

                    @if($item->warranty_years)
                        {{ $item->warranty_years }} Year{{ $item->warranty_years > 1 ? 's' : '' }}
                    @else
                        —
                    @endif

                </td>

                <td
                    align="center"
                    style="border-bottom:1px solid #eeeeee;"
                >
                    {{ $item->quantity }}
                </td>

                <td
                    align="right"
                    style="border-bottom:1px solid #eeeeee;"
                >
                    ₹{{ number_format($item->line_total, 2) }}
                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

</td>
</tr>


<tr>
<td style="padding:0 25px 25px;">

    <table
        width="100%"
        cellpadding="7"
        cellspacing="0"
    >

        <tr>
            <td>Subtotal</td>
            <td align="right">
                ₹{{ number_format($order->subtotal, 2) }}
            </td>
        </tr>

        <tr>
            <td>GST</td>
            <td align="right">
                ₹{{ number_format($order->gst_amount, 2) }}
            </td>
        </tr>

        <tr>
            <td>Delivery</td>
            <td align="right">
                ₹{{ number_format($order->delivery_charge, 2) }}
            </td>
        </tr>

        @if(
            isset($order->installation_charges) &&
            (float) $order->installation_charges > 0
        )

            <tr>
                <td>Installation</td>
                <td align="right">
                    ₹{{ number_format($order->installation_charges, 2) }}
                </td>
            </tr>

        @endif

        <tr>
            <td>Discount</td>
            <td
                align="right"
                style="color:#dc3545;"
            >
                - ₹{{ number_format($order->discount_amount, 2) }}
            </td>
        </tr>

        <tr>
            <td
                style="
                    border-top:2px solid #212529;
                    padding-top:12px;
                    font-weight:bold;
                    font-size:18px;
                "
            >
                Total
            </td>

            <td
                align="right"
                style="
                    border-top:2px solid #212529;
                    padding-top:12px;
                    font-weight:bold;
                    font-size:18px;
                "
            >
                ₹{{ number_format($order->total_amount, 2) }}
            </td>
        </tr>

    </table>

</td>
</tr>


@if($order->address)

<tr>
<td style="padding:0 25px 30px;">

    <h3 style="margin:0 0 10px;">
        Delivery Address
    </h3>

    <p
        style="
            margin:0;
            line-height:1.7;
            color:#555555;
        "
    >

        {{ $order->address->name }}

        <br>

        {{ $order->address->address }}

        <br>

        {{ $order->address->city }},
        {{ $order->address->state }}
        -
        {{ $order->address->pincode }}

        <br>

        Phone:
        {{ $order->address->phone }}

    </p>

</td>
</tr>

@endif


<tr>
<td
    style="
        padding:18px 25px;
        background:#f8f9fa;
        color:#6c757d;
        font-size:12px;
        text-align:center;
    "
>

    SleepWell Admin Notification

</td>
</tr>

</table>

</td>
</tr>

</table>

</body>
</html>