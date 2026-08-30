<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        SleepWell Order Confirmation
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
    width="650"
    cellpadding="0"
    cellspacing="0"
    style="
        max-width:650px;
        width:100%;
        background:#ffffff;
        border-radius:8px;
        overflow:hidden;
    "
>

    {{-- HEADER --}}
    <tr>
        <td
            style="
                padding:25px;
                background:#ffffff;
                border-bottom:1px solid #eeeeee;
                text-align:center;
            "
        >

            <h1
                style="
                    margin:0;
                    font-size:28px;
                    color:#212529;
                "
            >
                SleepWell
            </h1>

            <p
                style="
                    margin:8px 0 0;
                    color:#6c757d;
                    font-size:14px;
                "
            >
                Order Confirmation
            </p>

        </td>
    </tr>


    {{-- SUCCESS --}}
    <tr>
        <td style="padding:30px;">

            <h2
                style="
                    margin:0 0 10px;
                    color:#198754;
                    font-size:22px;
                "
            >
                Thank you for your purchase!
            </h2>

            <p
                style="
                    margin:0;
                    color:#555555;
                    line-height:1.6;
                "
            >
                Hi {{ $order->user->name }},
            </p>

            <p
                style="
                    margin:10px 0 0;
                    color:#555555;
                    line-height:1.6;
                "
            >
                Your SleepWell order has been successfully placed
                using Cash on Delivery.
            </p>

        </td>
    </tr>


    {{-- ORDER NUMBER --}}
    <tr>
        <td style="padding:0 30px 25px;">

            <table
                width="100%"
                cellpadding="0"
                cellspacing="0"
                style="
                    background:#f8f9fa;
                    border-radius:6px;
                "
            >

                <tr>

                    <td
                        style="
                            padding:15px;
                            color:#6c757d;
                            font-size:13px;
                        "
                    >
                        Order Number
                    </td>

                    <td
                        align="right"
                        style="
                            padding:15px;
                            font-weight:bold;
                            font-size:15px;
                        "
                    >
                        {{ $order->order_number }}
                    </td>

                </tr>

            </table>

        </td>
    </tr>


    {{-- PRODUCTS --}}
    <tr>
        <td style="padding:0 30px 25px;">

            <h3
                style="
                    margin:0 0 15px;
                    font-size:18px;
                "
            >
                Purchased Products
            </h3>


            @foreach($order->items as $item)

                <table
                    width="100%"
                    cellpadding="0"
                    cellspacing="0"
                    style="
                        border-bottom:1px solid #eeeeee;
                        margin-bottom:10px;
                    "
                >

                    <tr>

                        <td
                            style="
                                padding:12px 0;
                                vertical-align:top;
                            "
                        >

                            <strong>
                                {{ $item->product_name }}
                            </strong>

                            <br>

                            <span
                                style="
                                    color:#6c757d;
                                    font-size:13px;
                                "
                            >
                                Package:
                                {{ $item->package_name }}
                            </span>


                            @if($item->warranty_years)

                                <br>

                                <span
                                    style="
                                        color:#0d6efd;
                                        font-size:13px;
                                    "
                                >
                                    Warranty:
                                    {{ $item->warranty_years }}
                                    Year{{ $item->warranty_years > 1 ? 's' : '' }}
                                </span>

                            @endif


                            <br>

                            <span
                                style="
                                    color:#6c757d;
                                    font-size:13px;
                                "
                            >
                                Quantity:
                                {{ $item->quantity }}
                            </span>

                        </td>


                        <td
                            align="right"
                            style="
                                padding:12px 0;
                                vertical-align:top;
                            "
                        >

                            <strong>
                                ₹{{ number_format($item->line_total, 2) }}
                            </strong>

                            <br>

                            <span
                                style="
                                    color:#6c757d;
                                    font-size:12px;
                                "
                            >
                                ₹{{ number_format($item->unit_price, 2) }}
                                each
                            </span>

                        </td>

                    </tr>

                </table>

            @endforeach

        </td>
    </tr>


    {{-- PRICE SUMMARY --}}
    <tr>
        <td style="padding:0 30px 25px;">

            <h3
                style="
                    margin:0 0 15px;
                    font-size:18px;
                "
            >
                Order Summary
            </h3>


            <table
                width="100%"
                cellpadding="0"
                cellspacing="0"
            >

                <tr>
                    <td style="padding:6px 0;color:#6c757d;">
                        Subtotal
                    </td>

                    <td align="right" style="padding:6px 0;">
                        ₹{{ number_format($order->subtotal, 2) }}
                    </td>
                </tr>


                <tr>
                    <td style="padding:6px 0;color:#6c757d;">
                        GST
                    </td>

                    <td align="right" style="padding:6px 0;">
                        ₹{{ number_format($order->gst_amount, 2) }}
                    </td>
                </tr>


                <tr>
                    <td style="padding:6px 0;color:#6c757d;">
                        Delivery
                    </td>

                    <td align="right" style="padding:6px 0;">
                        @if((float) $order->delivery_charge > 0)
                            ₹{{ number_format($order->delivery_charge, 2) }}
                        @else
                            FREE
                        @endif
                    </td>
                </tr>


                @if(
                    isset($order->installation_charges) &&
                    (float) $order->installation_charges > 0
                )

                    <tr>
                        <td style="padding:6px 0;color:#6c757d;">
                            Installation
                        </td>

                        <td align="right" style="padding:6px 0;">
                            ₹{{ number_format($order->installation_charges, 2) }}
                        </td>
                    </tr>

                @endif


                <tr>
                    <td style="padding:6px 0;color:#6c757d;">
                        Discount
                    </td>

                    <td
                        align="right"
                        style="
                            padding:6px 0;
                            color:#dc3545;
                        "
                    >
                        - ₹{{ number_format($order->discount_amount, 2) }}
                    </td>
                </tr>


                <tr>

                    <td
                        style="
                            padding:15px 0 5px;
                            border-top:1px solid #dddddd;
                            font-size:18px;
                            font-weight:bold;
                        "
                    >
                        Total
                    </td>

                    <td
                        align="right"
                        style="
                            padding:15px 0 5px;
                            border-top:1px solid #dddddd;
                            font-size:20px;
                            font-weight:bold;
                            color:#0d6efd;
                        "
                    >
                        ₹{{ number_format($order->total_amount, 2) }}
                    </td>

                </tr>

            </table>

        </td>
    </tr>


    {{-- PAYMENT --}}
    <tr>
        <td style="padding:0 30px 25px;">

            <div
                style="
                    background:#f8f9fa;
                    padding:15px;
                    border-radius:6px;
                "
            >

                <strong>
                    Payment Method:
                </strong>

                Cash on Delivery

                <br>

                <span
                    style="
                        color:#6c757d;
                        font-size:13px;
                    "
                >
                    Payment Status:
                    Pending
                </span>

            </div>

        </td>
    </tr>


    {{-- ADDRESS --}}
    <tr>
        <td style="padding:0 30px 30px;">

            <h3
                style="
                    margin:0 0 12px;
                    font-size:18px;
                "
            >
                Delivery Address
            </h3>

            @if($order->address)

                <p
                    style="
                        margin:0;
                        line-height:1.7;
                        color:#555555;
                    "
                >

                    <strong>
                        {{ $order->address->name }}
                    </strong>

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

            @endif

        </td>
    </tr>


    {{-- FOOTER --}}
    <tr>
        <td
            style="
                padding:20px 30px;
                background:#f8f9fa;
                text-align:center;
                color:#6c757d;
                font-size:12px;
            "
        >

            You will receive further order updates from SleepWell.

        </td>
    </tr>

</table>

</td>
</tr>

</table>

</body>
</html>