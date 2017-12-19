@extends('layout.pdf')

@section('body')
	@foreach($data as $i => $member)
		@include('pdf.body.letterBody', ['bodyIncludeFile' => 'pdf.letter.rechnung', 'bodyParam' => $member])

		@if ($data->keys()->last() != $i)
		<div style="page-break-after: always;"> </div>
		@endif
	@endforeach
@endsection
