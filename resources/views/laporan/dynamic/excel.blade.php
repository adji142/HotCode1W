<html>
	<table>
		@if (is_object($info['heads']) && ($info['heads'] instanceof \Closure))
			{{ $v($info, $args, 'excel', $datas) }}
		@elseif (gettype($info['heads']) == 'array')
			@foreach ($info['heads'] as $k => $v)
				<tr>
         		<td><strong>
            		@if (is_object($v) && ($v instanceof \Closure))
               			{{ $v($info, $args, 'excel', $datas) }}
               		@else
                 		{{ $v }}
               		@endif
            	</strong></td>
        		</tr>
      		@endforeach
      	@else
      		{{ $info['heads'] }}
		@endif
	</table>
	<table style="border: 1px solid #000;">
		<tr>
			@foreach ($cols['h'] as $k2 => $v)
				@if ($cols['h'][0] !== $v) <tr> @endif
				@foreach ($v as $k2 => $v2)
					@if ($v2 === false)
						<td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;"></td>

					@elseif (gettype($v) == 'array')
						<td
							style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;"
							@if (isset($v2['cols'])) colspan="{{ $v2['cols'] }}" @endif
						>{{ $v2['text'] }}</td>

					@else
						<td style="font-weight: bold; background-color: #dbe5f1; border: 1px solid #000;">{{ $v2 }}</td>
					@endif
				@endforeach
				@if (end($cols['h']) !== $v) </tr> @endif
			@endforeach
		</tr>

		@foreach ($datas as $dat)
		<tr>
			@foreach ($cols['r'] as $k => $v)
				@if (gettype($v) == 'array')
					@if (isset($v['fn']))
						@if (is_object($v['fn']) && ($v['fn'] instanceof \Closure))
							<td style="border: 1px solid #000;">{{ $v['fn']($dat) }}</td>
						@else
							<td style="border: 1px solid #000;">{{ (isset($v['val']) ? $v['val'] : @$dat[$k]) }}</td>
						@endif

					@elseif (isset($v['type']))
						@if ($v['type'] == 'date')
							<td style="border: 1px solid #000;">{{ (new \DateTime((isset($v['val']) ? $v['val'] : @$dat[$k])))->format('d/m/Y') }}</td>
						@elseif ($v['type'] == 'datetime')
							<td style="border: 1px solid #000;">{{ (new \DateTime((isset($v['val']) ? $v['val'] : @$dat[$k])))->format('d/m/Y H:i:s') }}</td>
						@elseif ($v['type'] == 'int' || $v['type'] == 'money')
							<td style="border: 1px solid #000;">{{ intval((isset($v['val']) ? $v['val'] : @$dat[$k])) }}</td>
						@else
							<td style="border: 1px solid #000;">{{ (isset($v['val']) ? $v['val'] : @$dat[$k]) }}</td>
						@endif

					@else
						<td style="border: 1px solid #000;">{{ (isset($v['val']) ? $v['val'] : @$dat[$k]) }}</td>
					@endif

				@else
					<td style="border: 1px solid #000;">{{ (isset($v['val']) ? $v['val'] : @$dat[$k]) }}</td>
				@endif
			@endforeach
		</tr>
		@endforeach

        @if (isset($info['foots']))
        	@foreach ($info['foots'] as $d)
        		<tr>
        		@foreach ($d as $k => $d)
        			<td
        				style="border:1px solid #000; border-top:1px double #000;"
        				@if (isset($d['cols'])) colspan="{{ $d['cols'] }}" @endif
        				@if (isset($d['rows'])) colspan="{{ $d['rows'] }}" @endif

        			>{{ $d['text'] }}</td>
        		@endforeach
	        	</tr>
        	@endforeach
        @endif
	</table>
	<table>
		<tr>
			<td style="font-style: italic;">{{ $username }} {{ Carbon\Carbon::now()->format('d/m/Y h:i:s') }}</td>
		</tr>
	</table>
</html>