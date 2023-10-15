@extends('admin.layout.master')

@section('content')
    <div class="page-wrapper">

        <div class="page-body">
            <div class="row">
                <!-- task, page, download counter  start -->
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-c-yellow update-card">
                        <div class="card-block">
                            <div class="row align-items-end">
                                <div class="col-8">
                                    <h4 class="text-white">{{\App\Models\OrdersModel::get()->count()}}</h4>
                                    <h6 class="text-white m-b-0">Tổng đơn hàng</h6>
                                </div>
                                <div class="col-4 text-right">
                                    <canvas id="update-chart-1" height="50"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href=""><p style="color: white;" class="m-b-0">Xem chi tiết</p></a>
                        </div>
                    </div>
                </div>
                <!-- task, page, download counter  end -->
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-c-green update-card">
                        <div class="card-block">
                            <div class="row align-items-end">
                                <div class="col-8">
                                    <h4 class="text-white">{{\App\Models\OrdersModel::whereDate('created_at', \Carbon\Carbon::now())->get()->count()}}</h4>
                                    <h6 class="text-white m-b-0">Đơn hàng hôm nay</h6>
                                </div>
                                <div class="col-4 text-right">
                                    <canvas id="update-chart-1" height="50"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href=""><p style="color: white;" class="m-b-0">Xem chi tiết</p></a>
                        </div>
                    </div>
                </div>
                <!-- task, page, download counter  end --><!-- task, page, download counter  end -->
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-c-green update-card">
                        <div class="card-block">
                            <div class="row align-items-end">
                                <div class="col-8">
                                    <h4 class="text-white">{{number_format(\App\Models\OrdersModel::whereDate('created_at', \Carbon\Carbon::now())->get()->sum('total_price'))}}</h4>
                                    <h6 class="text-white m-b-0">Doanh thu hôm nay</h6>
                                </div>
                                <div class="col-4 text-right">
                                    <canvas id="update-chart-1" height="50"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href=""><p style="color: white;" class="m-b-0">Xem chi tiết</p></a>
                        </div>
                    </div>
                </div>
                <!-- task, page, download counter  end -->
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection
