@extends('shop.layouts.main')
@section('content')
    <section class="latest-products spad">
        <div class="product-filter">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <h2>Kết Quả Tìm Kiếm </h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="product-list">
            @foreach($product as $product)
                <div class="col-lg-3 col-sm-6 mix all">
                    <div class="single-product-item">
                        <figure>
                            <a href="{{ route('shop.productdetail', ['slug' => $product->slug , 'id' => $product->id]) }}"><img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style=""></a>
                            <div class="p-status">new</div>
                        </figure>
                        <div class="product-text">
                            <h6>
                                @if(strlen($product->name) < 28)
                                    {{ $product->name }}
                                @else
                                    {{ substr($product->name, 0, 20) . "..." }}
                                @endif
                            </h6>
                            <p>${{ $product->sale }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
