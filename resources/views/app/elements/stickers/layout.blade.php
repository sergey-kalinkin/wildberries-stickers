@php
    [$width, $height] = $sticker_size ?? ['66.7', '46'];
    $padding = 3; //mm

    $width -= 2*$padding;
    $height -= 2*$padding;

    $is_certified = $is_certified ?? false;
    $has_eac = $has_eac ?? false;

    $font_size = $font_size ?? 12;
    $is_dashed = $is_dashed ?? false;
@endphp

<!DOCTYPE html>
<html>

<head>
    <style>
        /*reset css styles*/
        html, body, div, span, applet, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        a, abbr, acronym, address, big, cite, code,
        del, dfn, em, img, ins, kbd, q, s, samp,
        small, strike, strong, sub, sup, tt, var,
        b, u, i, center,
        dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend,
        table, caption, tbody, tfoot, thead, tr, th, td,
        article, aside, canvas, details, embed,
        figure, figcaption, footer, header, hgroup,
        menu, nav, output, ruby, section, summary,
        time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
        }
        /* HTML5 display-role reset for older browsers */
        article, aside, details, figcaption, figure,
        footer, header, hgroup, menu, nav, section {
            display: block;
        }
        body {
            line-height: 1;
        }
        ol, ul {
            list-style: none;
        }
        blockquote, q {
            quotes: none;
        }
        blockquote:before, blockquote:after,
        q:before, q:after {
            content: '';
            content: none;
        }
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }


        /**/
        .main {

        }

        .container {

            border-left: 0.1mm {{$is_dashed ? 'dashed' : 'none'}};
            border-top: 0.1mm {{$is_dashed ? 'dashed' : 'none'}};
            border-bottom: 0.1mm {{$is_dashed ? 'dashed' : 'none'}};

            margin-top: -0.1mm;
            margin-left: -0.1mm;
        }

        .sticker {
            border-right: 0.1mm {{$is_dashed ? 'dashed' : 'none'}};

            float: left;
            width: {{$width}}mm;
            height: {{$height}}mm;
            overflow: hidden;
            padding: {{$padding}}mm;
        }

        .sticker__field--main {
            text-align: center;
        }

        .sticker__field--additional {

        }

        .field {
            line-height: 1;
            font-size: {{$font_size}}pt;
            display: block;
            font-family: Calibr, serif;
        }

        .barcode {
            padding-bottom: 1mm;
        }

        @if($has_eac)
            .fields_block {
                width: 80%;
                float: left;
            }

            .eac_block {
                width: 19%;
                float: left;
                text-align: center;
                padding-top: {{$height/20}}mm;
            }
        @else
            .eac_block {
                display: none;
            }
        @endif

        @if(!$is_certified)
            .field--sert {
                display: none;
            }
        @endif

    </style>
    <title></title>
</head>
<body>

    <div class="main">
        @foreach($rows as $row)
            {!! $row !!}
        @endforeach
    </div>

</body>
</html>
