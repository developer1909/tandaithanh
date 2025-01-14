@extends('admin.layout.master')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('template_admin\files\bower_components\datatables.net-bs4\css\dataTables.bootstrap4.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('template_admin\files\assets\pages\data-table\css\buttons.dataTables.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('template_admin\files\bower_components\datatables.net-responsive-bs4\css\responsive.bootstrap4.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('template_admin\files\bower_components\sweetalert\css\sweetalert.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('template_admin\files\bower_components\select2\css\select2.min.css')}}">

    <style type="text/css">
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding: 4px 30px 4px 20px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 6px !important;
        }
        .form-group{
            margin-bottom: 0 !important;
        }
        .select2-dropdown {
            z-index: 9999999999 !important;
        }
    </style>
@endsection
@section('content')
    <div class="page-wrapper">
        <div class="page-body">
            <div class="row">
                <div class="col-xl-12">
                    <div class="page-body">
                        @include('admin.includes.messages')
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{$model->id ? 'Cập nhật đơn hàng' : 'Tạo mới đơn hàng'}} {{$model->order_code}}</h5>
                                        <div class="card-header-right">
                                            <ul class="list-unstyled card-option">
                                                <li><i class="feather icon-maximize full-card"></i></li>
                                                <li><i class="feather icon-minus minimize-card"></i></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-block">
                                        <form method="POST" action="{{route('orders.save')}}">
                                            <div class="row">
                                                @csrf
                                                <input type="hidden" id="id-order" name="id" value="{{$model->id}}">
                                                <div class="col-md-12">
                                                    <div class="sub-title">Thông tin khách hàng</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="product_name">Tên người nhận: <span class="text-danger">(*)</span></label>
                                                        <br>
                                                        <input type="text" class="form-control" id="recipient_name" required name="recipient_name" value="{{old('recipient_name', $model->recipient_name)}}">
                                                        @if($errors->first('recipient_name'))
                                                            <p class="text-danger">{{ $errors->first('recipient_name') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="product_name">Số điện thoại người nhận: <span class="text-danger">(*)</span></label>
                                                        <br>
                                                        <input type="text" class="form-control" id="recipient_phone" required name="recipient_phone" value="{{old('recipient_phone', $model->recipient_phone)}}">
                                                        @if($errors->first('recipient_phone'))
                                                            <p class="text-danger">{{ $errors->first('recipient_phone') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="product_name">Địa chỉ người nhận: <span class="text-danger">(*)</span></label>
                                                        <br>
                                                        <input type="text" class="form-control" id="recipient_address" required name="recipient_address" value="{{old('recipient_address', $model->recipient_address)}}">
                                                        @if($errors->first('recipient_address'))
                                                            <p class="text-danger">{{ $errors->first('recipient_address') }}</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-12 m-t-20">
                                                    <div class="sub-title">Trạng thái đơn hàng</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="order_status">Tình trạng đơn hàng: <span class="text-danger">(*)</span></label>
                                                        <br>
                                                        <select class="form-control" name="order_status" id="order_status">
                                                            @foreach(\App\Models\OrdersModel::STATUS_ALL as $key => $status)
                                                                <option value="{{ $status['key']}}" {{ (old('order_status', $model->order_status) == $status['key']) ? 'selected' : '' }}>{{$status['status_name']}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if($errors->first('order_status'))
                                                            <p class="text-danger">{{ $errors->first('order_status') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="payment_status">Trạng thái thanh toán: <span class="text-danger">(*)</span></label>
                                                        <br>
                                                        <select class="form-control" name="payment_status" id="payment_status">
                                                            @foreach(\App\Models\OrdersModel::PAYMENT_STATUS as $key => $status)
                                                                <option value="{{ $status['key']}}" {{ (old('payment_status', $model->payment_status) == $status['key']) ? 'selected' : '' }}>{{$status['status_name']}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if($errors->first('payment_status'))
                                                            <p class="text-danger">{{ $errors->first('payment_status') }}</p>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-12  m-t-20">
                                                    <div class="sub-title">Thông tin hàng hóa</div>
                                                </div>
                                                <div id="div-freight" style="width: -webkit-fill-available;"  class="">
                                                    <div class="col-12">
                                                        @if($model->status_export == 0)
                                                        <a href="javascript:void(0)" class="btn btn-success m-b-20" onclick="functionAddProduct()">Thêm mặt hàng</a>
                                                        @endif
                                                        <table id="simpletable" class="table table-striped table-bordered nowrap">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-center">#</th>
                                                                <th>Tên sản phẩm</th>
                                                                {{--                                                                <th>Loại sản phẩm</th>--}}
                                                                <th>Đơn vị tính</th>
                                                                <th>Số lượng</th>
                                                                <th>Đơn giá</th>
                                                                <th>thành tiền</th>
                                                                <th>Tồn kho</th>
                                                                <th>Xuất kho</th>
                                                                <th>Thao tác</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="data-product-table" >
                                                            @if(count($products) == 0)
                                                                <tr class="odd">
                                                                    <td valign="top" colspan="10" class="dataTables_empty text-center">Không có dữ liệu để hiển thị</td>
                                                                </tr>
                                                            @endif
                                                            <?php
                                                                $i = 0;
                                                            ?>
                                                            @foreach($products as $p)
                                                                <tr>
                                                                    <td class="text-center">{{$i = $i+1}}</td>
                                                                    <td>{{$p->name}}</td>
                                                                    <td>{{$p->unit}}</td>
                                                                    <td>{{$p->quantity}}</td>
                                                                    <td>{{number_format($p->price_product) }}</td>
                                                                    <td>{{number_format($p->quantity*$p->price_product,0)}}</td>
                                                                    <td>
                                                                        <p style="margin: 0;">Tông số lượng: <span class="{{$p->total_qty >= $p->quantity ? 'text-success' : 'text-danger'}}" style="font-weight: 800;" >{{$p->total_qty}}</span></p>
{{--                                                                        <div id="div-qty{{$item->id}}">--}}
                                                                            @foreach($p->product_qty as $qty)
                                                                                @if($qty->qty>0)
                                                                                    <p style="margin: 0;">Kho {{$qty->warehouse}}: {{$qty->qty}}</p>
                                                                                @endif
                                                                            @endforeach
{{--                                                                        </div>--}}
                                                                    </td>
                                                                    <td class="text-center" id="xuat-kho{{$p->order_products_id}}">{{$p->warehouse}}</td>
                                                                    <td>
                                                                        @if($model->status_export == 0)
                                                                        <a href="javascript:void(0)" onclick="updateProduct(this)" category="{{$p->category}}" product-id="{{$p->product_id}}" product-name="{{$p->product_name}}"
                                                                        price_product="{{$p->price_product}}" product_quantity="{{$p->quantity}}" into_money="{{$p->into_money}}"
                                                                        id_temp="{{$p->order_products_id}}" id_order_product="{{$p->order_products_id}}" class="btn btn-sm btn-inverse m-r-5">Sửa</a>
                                                                        <a href="javascript:void(0)" onclick="removeProduct({{$p->order_products_id}})" class="btn btn-sm btn-danger">Xóa</a>

                                                                        <div class="dropdown-primary dropdown open">
                                                                            <button class="btn btn-sm btn-primary dropdown-toggle waves-effect waves-light " type="button" id="dropdown-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Chọn kho hàng xuất</button>
                                                                            <div class="dropdown-menu" aria-labelledby="dropdown-2" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut">
                                                                                <a class="dropdown-item waves-light waves-effect" href="javascript:void(0)" onclick="exportWarehouse(1, {{$p->order_products_id}})">Kho 1</a>
                                                                                <a class="dropdown-item waves-light waves-effect" href="javascript:void(0)" onclick="exportWarehouse(2, {{$p->order_products_id}})">Kho 2</a>
                                                                                <a class="dropdown-item waves-light waves-effect" href="javascript:void(0)" onclick="exportWarehouse(3, {{$p->order_products_id}})">Kho 3</a>
                                                                                <a class="dropdown-item waves-light waves-effect" href="javascript:void(0)" onclick="exportWarehouse(4, {{$p->order_products_id}})">Kho 4</a>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-10"></div>
                                                <div class="col-2">
                                                    <div class="form-group text-right">
                                                        <label for="total_price">Tổng tiền: <span class="text-danger">(*)</span></label>
                                                        <br>
                                                        <div class="input-group">
                                                            <input type="number" readonly step=".01" min="0" class="form-control text-right" id="total_price" name="total_price" value="{{old('total_price', $model->total_price) }}">
                                                            <span class="input-group-addon" id="basic-addon1">VNĐ</span>
                                                        </div>
                                                        @if($errors->first('total_price'))
                                                            <p class="text-danger">{{ $errors->first('total_price') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-12 ">
                                                    <div class="form-group text-right">
                                                        @if($model->status_export == 0)
                                                            <a href="javascript:void(0)" onclick="exportOrder({{$model->id}})" class="btn btn-inverse"> Xuất hàng</a>
                                                        @endif

                                                        <a href="javascript:void(0)" onclick="printLabel({{$model->id}})" class="btn btn-inverse"> In hóa đơn</a>
                                                        <button type="submit" class="btn btn-primary m-b-0"><i class="icofont icofont-diskette"></i> Lưu</button>
                                                        {{--                                                        <a href="javascript:void(0)" class="btn btn-secondary m-b-0" onclick="functionCancel(this)"><i class="icofont icofont-refresh"></i> Reset form</a>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.orders.modal_add_product')
@endsection
@section('script')

    <script>
        $(document).ready(function() {
            $(".js-example-data-array").select2();

            $("#category").change(function () {
                let cat = $("#category").val()
                $.ajax({
                    url: '{{ route('products.by.category') }}?category=' + cat,
                    type: 'get',
                    success: function (data) {
                        $("#product_id").empty();
                        $("#product_id").append(
                            '<option value="" data-price="" data-qty="">-- Chọn Sản Phẩm --</option>'
                        );
                        data.forEach(element => {
                            let price = new Intl.NumberFormat().format(element.price);
                            $("#product_id").append(
                                '<option value="'+element.id+'" data-price="'+element.price+'" data-qty="'+element.qty+'">'+element.name +' - '+ price+'</option>'
                            );
                        });
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            });

            $("#product_id").change(function () {
                if ($("#product_id").val() !== '0'){
                    let price = $(this).find('option:selected').attr('data-price');
                    let qty = $(this).find('option:selected').attr('data-qty');
                    $('#price_product').val(price);
                    $('#qty').val(qty);
                    $('#quantity').attr('max', qty);
                    price = parseInt(price) * parseInt($('#quantity').val());
                    $('#into_money').val(price);
                } else {
                    $('#price_product').val(0);
                    $('#qty').val(0);
                    $('#into_money').val(0);
                    // var price = 0;
                    // $('#extra_charges').val(price.toFixed(2));
                }
            });
        });
        $(document).on('hide.bs.modal','#add-product-modal', function () {
            $('[name=product_id]').val(0).change();
            $('[name=category]').val(0).change();
            $('[name=price_product]').val(0);
            $('[name=quantity]').val(1);
            $('[name=into_money]').val(0);
            $('[name=note_modal]').val('');
            $('[name=id_temp]').val('');
            $('[name=id_order_product]').val('');
        });

        function exportWarehouse(w,id) {
            $.ajax({
                url: "{{ route('orders.update.warehouse') }}",
                method: "GET",
                data: {id: id,
                    warehouse: w,
                },
                success: function (data) {
                    if(typeof data === 'string'){
                        data = JSON.parse(data);
                    }
                    if (data.code === 200){
                        let text = '#xuat-kho'+id;
                        $(text).text(data.data.warehouse);
                    } else {
                        alert(data.message)
                    }
                }
            });
        }

        function functionAddProduct(){
            $('#add-product-modal').modal('show');
            $('#title-modal').text('Thêm mặt hàng');
            $('[name=product_type]').val(0).change();
            $('[name=price_product_type]').val(0);
            $('[name=id_temp]').val('');
            $('[name=product_name_modal]').val('');
            $('[name=weight]').val(0.01);
            $('[name=wide]').val(1);
            $('[name=long]').val(1);
            $('[name=height]').val(1);
            $('[name=quantity]').val(1);
            $('[name=extra_charges]').val(0);
            $('[name=note_modal]').val('');
        }

        function printLabel(id) {
            $.ajax({
                url: "{{ route('orders.print') }}",
                method: "GET",
                data: {id: id
                },
                success: function (data) {
                    if(typeof data === 'string'){
                        data = JSON.parse(data);
                    }
                    if (data.code === 200){
                        console.log(data)
                        window.open(data.data);
                        // location.reload();
                    } else {
                        alert(data.message)
                    }
                }
            });
        }
        function exportOrder(id) {
            $.ajax({
                url: "{{ route('orders.export') }}",
                method: "GET",
                data: {id: id
                },
                success: function (data) {
                    if(typeof data === 'string'){
                        data = JSON.parse(data);
                    }
                    if (data.code === 200){
                        // window.open(data.data);
                        location.reload();
                    } else {
                        alert(data.message)
                    }
                }
            });
        }
    </script>
    <script src="{{asset('template_admin\files\bower_components\datatables.net\js\jquery.dataTables.min.js')}} "></script>
    <script src="{{asset('template_admin\files\bower_components\datatables.net-bs4\js\dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('template_admin\files\bower_components\sweetalert\js\sweetalert.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('template_admin\files\bower_components\select2\js\select2.full.min.js')}}"></script>
@endsection
