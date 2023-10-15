<table style="width: 100%;">
    <tr>
        <td style="width: 47%; text-align: center">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 100%;text-align: center" >
                        <p style="font-size: 22px; font-weight: 600; text-align: center">CỬA HÀNG TÂN ĐẠI THÀNH (TÂN)</p>
                        <p style="font-size: 13px; font-weight: 500; text-align: center">ĐC: 265-267 Đường Nguyễn Huệ - K1 - P1 - TX Vĩnh Châu</p>
                        <p style="font-size: 13px; font-weight: 500; text-align: center">ĐT: 02993.622.777 - DĐ: 0983.862.777</p>
                        <p style="font-size: 22px; font-weight: 800; text-align: center">HÓA ĐƠN BÁN LẺ</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%;text-align: left">
                        <p style="text-align: left !important;">Khách hàng: {{$recipient_name}} - {{$recipient_phone}}</p>
                        <p style="text-align: left !important;">Địa chỉ: {{$recipient_address}}</p>
                        <p style="text-align: left !important;">Mã đơn hàng: {{$order_code}}</p>
                    </td>
                </tr>
            </table>
            <div style="margin-top: 20px">
                <TABLE border="1" width="100%" cellpadding="4" cellspacing="0">
                    <TR style="text-transform: uppercase; text-align: center">
                        <td style="text-align: center">STT</td>
                        <td style="text-align: left">TÊN HÀNG</td>
                        <td style="text-align: center">ĐVT</td>
                        <td style="text-align: center">SL</td>
                        <td style="text-align: center">ĐƠN GIÁ</td>
                        <td style="text-align: center">THÀNH TIỀN</td>
                    <TR>
                    <?php
                        $i = 0;
                        ?>
                    @foreach($products as $item)
                    <TR style="text-transform: uppercase; ">
                        <td style="font-size: 12px;">{{$i=$i+1}}</td>
                        <td style="text-align: left;font-size: 12px;">{{$item->name}}</td>
                        <td style="text-align: center;font-size: 12px;">{{$item->unit}}</td>
                        <td style="text-align: center;font-size: 12px;">{{$item->quantity}}</td>
                        <td style="text-align: right;font-size: 12px;">{{number_format($item->price_product) }}</td>
                        <td style="text-align: right;font-size: 12px;">{{number_format($item->quantity*$item->price_product,0)}}</td>
                    <TR>
                    @endforeach
                    <TR>
                        <td colspan="5" style="text-align: left">TỔNG TIỀN</td>
                        <td style="text-align: right">{{number_format($total_price)}}</td>
                    </TR>
                    <TR>
                        <td colspan="5" style="text-align: left"><br></td>
                        <td style="text-align: right"><br></td>
                    </TR>
                    <TR>
                        <td colspan="5" style="text-align: left">Tổng Tiền Thanh Toán</td>
                        <td style="text-align: right">{{number_format($total_price)}}</td>
                    </TR>
                </TABLE>
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 47%;text-align: center" >
                            <p style="text-align: center">KHÁCH HÀNG</p>
                            <p style="text-align: center">(Ký, ghi rõ họ tên)</p>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                        </td>
                        <td style="width: 50%;text-align: center">
                            <p style="text-align: center">Ngày {{date("d")}} tháng {{date("m")}} năm {{date("Y")}}</p>
                            <p style="text-align: center; font-weight: 800">Người lập phiếu</p>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <p style="text-align: center; font-weight: 800">QUÁCH Ủ TÂN</p>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
        <td style="width: 1%"></td>
        <td style="width: 47%; text-align: center">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 100%;text-align: center" >
                        <p style="font-size: 22px; font-weight: 600; text-align: center">CỬA HÀNG TÂN ĐẠI THÀNH (TÂN)</p>
                        <p style="font-size: 13px; font-weight: 500; text-align: center">ĐC: 265-267 Đường Nguyễn Huệ - K1 - P1 - TX Vĩnh Châu</p>
                        <p style="font-size: 13px; font-weight: 500; text-align: center">ĐT: 02993.622.777 - DĐ: 0983.862.777</p>
                        <p style="font-size: 22px; font-weight: 800; text-align: center">HÓA ĐƠN BÁN LẺ</p>
                    </td>
                </tr>
                <tr>
                    <td style="width: 100%;text-align: left">
                        <p style="text-align: left !important;">Khách hàng: {{$recipient_name}} - {{$recipient_phone}}</p>
                        <p style="text-align: left !important;">Địa chỉ: {{$recipient_address}}</p>
                        <p style="text-align: left !important;">Mã đơn hàng: {{$order_code}}</p>
                    </td>
                </tr>
            </table>
            <div style="margin-top: 20px">
                <TABLE border="1" width="100%" cellpadding="4" cellspacing="0">
                    <TR style="text-transform: uppercase; text-align: center">
                        <td style="text-align: center">STT</td>
                        <td style="text-align: left">TÊN HÀNG</td>
                        <td style="text-align: center">ĐVT</td>
                        <td style="text-align: center">SL</td>
                        <td style="text-align: center">ĐƠN GIÁ</td>
                        <td style="text-align: center">THÀNH TIỀN</td>
                    <TR>
                    <?php
                    $i = 0;
                    ?>
                    @foreach($products as $item)
                        <TR style="text-transform: uppercase; ">
                            <td style="font-size: 12px;">{{$i=$i+1}}</td>
                            <td style="text-align: left;font-size: 12px;">{{$item->name}}</td>
                            <td style="text-align: center;font-size: 12px;">{{$item->unit}}</td>
                            <td style="text-align: center;font-size: 12px;">{{$item->quantity}}</td>
                            <td style="text-align: right;font-size: 12px;">{{number_format($item->price_product) }}</td>
                            <td style="text-align: right;font-size: 12px;">{{number_format($item->quantity*$item->price_product,0)}}</td>
                        <TR>
                    @endforeach
                    <TR>
                        <td colspan="5" style="text-align: left">TỔNG TIỀN</td>
                        <td style="text-align: right">{{number_format($total_price)}}</td>
                    </TR>
                    <TR>
                        <td colspan="5" style="text-align: left"><br></td>
                        <td style="text-align: right"></td>
                    </TR>
                    <TR>
                        <td colspan="5" style="text-align: left">Tổng Tiền Thanh Toán</td>
                        <td style="text-align: right">{{number_format($total_price)}}</td>
                    </TR>
                </TABLE>
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 47%;text-align: center" >
                            <p style="text-align: center">KHÁCH HÀNG</p>
                            <p style="text-align: center">(Ký, ghi rõ họ tên)</p>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                        </td>
                        <td style="width: 50%;text-align: center">
                            <p style="text-align: center">Ngày {{date("d")}} tháng {{date("m")}} năm {{date("Y")}}</p>
                            <p style="text-align: center; font-weight: 800">Người lập phiếu</p>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <p style="text-align: center; font-weight: 800">QUÁCH Ủ TÂN</p>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
