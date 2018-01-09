@component('BetterNotifications::views.message', [
	'level' => $level,
	'heading' => $heading
])
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
# Hello!
@endif

{{-- Intro Lines --}}
@foreach($elements as $element)
<div class="row">
@if($element['type'] == 'line')
{!! $element['content'] !!}
@endif
@if($element['type'] == 'action')
@component('BetterNotifications::views.button', ['element' => $element])
{!! $element['content'] !!}
@endcomponent
@endif
@if($element['type'] == 'row')
<table width="100%" cellpadding="0" cellspacing="0" border="0"> <tr> <td align="center">
@foreach($element['elements'] as $subelement)
@component('BetterNotifications::views.subbutton', ['element' => $subelement])
{!! $subelement['action'] !!}
@endcomponent
@endforeach
</td> </tr> </table>
@endif
</div>
@endforeach



{{-- Salutation --}}
@if(! empty($salutation))
{!! $salutation !!}
@endif

@if ($subcopy)
@component('BetterNotifications::views.subcopy')
{!! $subcopy !!}
@endcomponent
@endif
@endcomponent
