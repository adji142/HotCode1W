<htmlpageheader name="MyHeader1">
    <div style="padding-top: 1pt;">
        <br>
        <table width="100%" cellspacing="0" cellpadding="2">
            @if (is_object($info['heads']) && ($info['heads'] instanceof \Closure))
                {!! $v($info, $args, 'pdf', @$datas[0]) !!}
            @elseif (gettype($info['heads']) == 'array')
                @foreach ($info['heads'] as $k => $v)
                    <tr>
                    <td><strong>
                        @if (is_object($v) && ($v instanceof \Closure))
                            {!! $v($info, $args, 'pdf', @$datas[0]) !!}
                        @else
                            {!! $v !!}
                        @endif
                    </strong></td>
                    </tr>
                @endforeach
            @else
                {!! $info['heads'] !!}
            @endif
        </table>
    </div>
</htmlpageheader>
<sethtmlpageheader name="MyHeader1" value="on" show-this-page="1" />

<htmlpagefooter name="MyFooter">
    <i>{{ $username . ' ' . date('d/m/Y H:i:s') }}</i>
</htmlpagefooter>
<sethtmlpagefooter name="MyFooter" value="on" />

<table width="100%" cellspacing="0" cellpadding="2" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
    <thead>
        <tr>
            @foreach ($cols['h'] as $k2 => $v)
                @if ($cols['h'][0] !== $v) <tr> @endif
                @foreach ($v as $k2 => $v2)
                    @if (gettype($v2) == 'array')
                        <th
                            style="border-bottom: 1px solid #000;"
                            @if (isset($v2['cols'])) colspan="{{ $v2['cols'] }}" @endif
                            @if (isset($v2['rows'])) rowspan="{{ $v2['rows'] }}" @endif

                        >{{ $v2['text'] }}</th>
                    @elseif ($v2 !== false)
                        <th style="border-bottom: 1px solid #000;">{{ $v2 }}</th>
                    @endif
                @endforeach
                @if (end($cols['h']) !== $v) </tr> @endif
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($datas as $i => $dat)
        <tr>
            @foreach ($cols['r'] as $k => $v)
                @if (gettype($v) == 'array')
                    @if (isset($v['fn']))
                        @if (is_object($v['fn']) && ($v['fn'] instanceof \Closure))
                            <td {{ isset($v['style']) ? 'style="' . $v['style'] . '"' : ''}}>{{ $v['fn']($i + 1, $k, $dat) }}</td>
                        @else
                            <td {{ isset($v['style']) ? 'style="' . $v['style'] . '"' : ''}}>{{ (isset($v['val']) ? $v['val'] : @$dat[$k]) }}</td>
                        @endif

                    @elseif (isset($v['type']))
                        @if ($v['type'] == 'int')
                            <td {{ isset($v['style']) ? 'style="' . $v['style'] . '"' : ''}}>{{ number_format((isset($v['val']) ? $v['val'] : @$dat[$k]), 0, ',', '.') }}</td>
                        @elseif ($v['type'] == 'money')
                            <td {{ isset($v['style']) ? 'style="' . $v['style'] . '"' : ''}}>{{ number_format((isset($v['val']) ? $v['val'] : @$dat[$k]), 2, ',', '.') }}</td>
                        @elseif ($v['type'] == 'date')
                            <td {{ isset($v['style']) ? 'style="' . $v['style'] . '"' : ''}}>{{ (new \DateTime((isset($v['val']) ? $v['val'] : @$dat[$k])))->format('d-m-Y') }}</td>
                        @elseif ($v['type'] == 'datetime')
                            <td {{ isset($v['style']) ? 'style="' . $v['style'] . '"' : ''}}>{{ (new \DateTime((isset($v['val']) ? $v['val'] : @$dat[$k])))->format('d-m-Y H:i:s') }}</td>
                        @else
                            <td {{ isset($v['style']) ? 'style="' . $v['style'] . '"' : ''}}>{{ (isset($v['val']) ? $v['val'] : @$dat[$k]) }}</td>
                        @endif

                    @else
                        <td {{ isset($v['style']) ? 'style="' . $v['style'] . '"' : ''}}>{{ (isset($v['val']) ? $v['val'] : @$dat[$k]) }}</td>
                    @endif

                @else
                    <td>{{ (isset($v['val']) ? $v['val'] : @$dat[$k]) }}</td>
                @endif
            @endforeach
        </tr>
        @endforeach

        @if (isset($info['foots']))
            @foreach ($info['foots'] as $d)
                <tr>
                @foreach ($d as $k => $d)
                    @if (isset($d['type']))
                        @if ($d['type'] == 'money')
                            <td
                                @if (isset($d['cols'])) colspan="{{ $d['cols'] }}" @endif
                                @if (isset($d['rows'])) rowspan="{{ $d['rows'] }}" @endif

                            >{{ number_format($d['text'], 2, ',', '.') }}</td>
                        @elseif ($d['type'] == 'int')
                            <td
                                @if (isset($d['cols'])) colspan="{{ $d['cols'] }}" @endif
                                @if (isset($d['rows'])) rowspan="{{ $d['rows'] }}" @endif

                            >{{ number_format($d['text'], 0, ',', '.') }}</td>
                        @else
                            <td
                                @if (isset($d['cols'])) colspan="{{ $d['cols'] }}" @endif
                                @if (isset($d['rows'])) rowspan="{{ $d['rows'] }}" @endif

                            >{{ $d['text'] }}</td>
                        @endif
                    @else
                        <td
                            @if (isset($d['cols'])) colspan="{{ $d['cols'] }}" @endif
                                @if (isset($d['rows'])) rowspan="{{ $d['rows'] }}" @endif

                        >{{ $d['text'] }}</td>
                    @endif
                @endforeach
                </tr>
            @endforeach
        @endif
    </tbody>
</table>