@if ($members->count() == 0)
	Keine Daten!
@else
	<div class="normalFont">
		<br>
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td>
					<h2 style="margin:0; padding: 0;">
						Rechnung
					</h2>
				</td>
				<td align="right">
					<small style="font-size: 10px;">Solingen, den {{ date ('d.m.Y') }}</small>
				</td>
			</tr>
		</table>

		Liebe Familie {{ $members->first()->lastname }},<br><br>
		
		Hiermit stellen wir Ihnen den aktuellen Mitgliedsbeitrag von
		{{ $members->totalAmount([1, 2]) }} € für das/die Jahr(e)
		{{ $members->enumNrs() }} für die DPSG und den Stamm Silva für
		{{ $members->enumNames() }} in Rechnung.
		Dieser setzt sich wie folgt zusammen:<br><br>
		
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			@foreach($members as $u)
			@foreach ($u->payments()->whereHas('status', function($q) {
				return $q->whereIn('status_id', [1,2]);
			})->get() as $z)
					<tr>
						<td>
							Beitrag für {{ $u->firstname }} {{ $u->lastname }} für {{ $z->nr }}
						</td>
						<td>
							{{ number_format($z->amount / 100, 2, ',', '.') }} €
						</td>
					</tr>
				@endforeach
			@endforeach
			<tr>
				<td style="border-top: 1px #000000 solid;">
					<strong>Gesamt</strong>
				</td>
				<td style="border-top:1px #000000 solid;">
					<strong>{{ $members->totalAmount([1, 2]) }}</strong>
				</td>
			</tr>
		</table>
		
		<br><br>
		Somit bitten wir Sie, den Betrag von<br>
		<strong>{{ $members->totalAmount([1, 2]) }}</strong><br>
		@if ($deadline != '')bis zum <strong>{{ d($deadline) }}</strong>@endif
		auf folgendes Konto zu überweisen:<br><br>
		
		@include('partials.pdf.letterSubBlock')	
	</div>
@endif
