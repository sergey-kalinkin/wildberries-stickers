
    {{-- row --}}
    <div class="container">

        @foreach($items as $item)
        <div class="sticker">
            <div class="sticker__field--main">

                <barcode code="{{$item->get('required_special')->get('barcode')}}" type="EAN13" class="barcode" height="{{$is_double_barcode_size ? '0.8' : '0.4'}}" />
                @foreach($item->get('required_special')->except('barcode') as $field)
                    <p class="field">{{$field}}</p>
                @endforeach

            </div>
        </div>
        @endforeach

    </div>
    {{-- /row --}}
