
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
            <div class="sticker__field--additional">

                <div class="fields_block">

                    @foreach($item->get('required_non_special') as $key=>$val)
                        <p class="field">{{$item->get('keys')->get($key).': '.$val}}</p>
                    @endforeach

                    @foreach($item->get('regular') as $key=>$val)
                        <p class="field">{{$key.': '.$val}}</p>
                    @endforeach

                    <p class="field field--sert">Товар не подлежит обязательной сертификации</p>
                </div>
                <div class="eac_block">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="5%"
                         height="5%"
                         viewBox="0 0 450 450"
                    >
                        <path fill="#fff" d="M-1.179-.59h201.084v186.145H-1.179z"/>
                        <path d="M50 13H10v155h40v-20H30V98h20V83H30V33h20z"/>
                        <path d="M90 98v70H70V13h60v155h-20V98z" stroke="#000"/>
                        <path d="M90 83h20V28H90z" fill="#fff" stroke="#000"/>
                        <path d="M190 13h-40v155h40v-20h-20V33h20z" stroke="#000"/>
                    </svg>
                </div>
            </div>
        </div>
        @endforeach

    </div>
    {{-- /row --}}
