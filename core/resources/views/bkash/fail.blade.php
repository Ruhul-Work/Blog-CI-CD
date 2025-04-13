
@extends('frontend.layouts.master')
@section('meta')
    <title>Order Failed | {{ get_option('title') }}</title>
@endsection

@section('content')


    <!-- my account section start -->

    <section class="my__account--section section--padding">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-8">
                    <div class="confirmation-page">
                        <div class="icon">
                            <div style="font-size: 48px;">😢</div>
                        </div>
                        <h1>দুঃখিত</h1> 
                        <h2>আপনার পেমেন্ট অসফল হয়েছে</h2>  
                        <h4 class="my-2">{{request()->error}}</h4>
                        
                        
                        <div >
                    </div>

                        <div class="d-flex justify-content-around">
                            @auth
                            <a href="{{route('dashboard.index')}}" class="primary__btn">আমার অ্যাকাউন্ট</a>
                            @else
                              <a href="{{route('home')}}" class="primary__btn">হোম</a>
                              @endauth
                            <a href="{{route('home')}}" class="primary__btn">সামনে এগিয়ে যান </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- my account section end -->





@endsection
@section('scripts')


    <style>
        .confirmation-page {

            text-align: center;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .confirmation-page .icon {
            font-size: 50px;
            color: #4caf50;
            margin-bottom: 20px;
        }
        .confirmation-page h1 {
            font-size: 40px;
            color: red;
            margin-bottom: 20px;
        }
        .confirmation-page h2 {
            font-size: 30px;
            color: #4caf50;
            margin-bottom: 20px;
        }

        .confirmation-page .order-number {
            font-size: 22px;
            margin-bottom: 20px;
        }

        .confirmation-page .order-number span {
            color: red;
        }

        .order-number{
            font-size: 20px;
            font-weight: 600;
        }

        .confirmation-page .email-confirmation {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .confirmation-page .order-total,
        .confirmation-page .payment-method {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }


    </style>




@endsection


