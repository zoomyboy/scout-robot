<table width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			Kontoinhaber:
		</td>
		<td>
			{{ $conf['letterKontoName'] }}
		</td>
	</tr>
	<tr>
		<td>
			IBAN:
		</td>
		<td>
			{{ $conf['letterIban'] }}
		</td>
	</tr>
	<tr>
		<td>
			BIC:
		</td>
		<td>
			{{ $conf['letterBic'] }}
		</td>
	</tr>
	<tr>
		<td>
			Zweck:
		</td>
		<td>
			{{ str_replace('[name]', $members->first()->firstname.' '.$members->first()->lastname, $conf['letterZweck']) }}
		</td>
	</tr>
</table>
<br>

Bitte nehmen Sie zur Kenntnis, dass der für jedes Mitglied
obligatorische Versicherungsschutz über die DPSG nur dann für Ihr
Kind / Ihre Kinder gilt, wenn der Mitgliedsbeitrag bezahlt wurde. Wenn
dies nicht geschieht, müssen wir Ihr Kind / Ihre Kinder von allen
Pfadfinderaktionen ausschließen. Dazu gehören sowohl die
Gruppenstunden sowie Tagesaktionen als auch mehrtägige Lager.
Bei Fragen zur Rechnung können Sie mich auch persönlich erreichen
unter:<br><br>

{{ $conf['letterName'] }}<br>
Tel.: {{ $conf['letterTel'] }}<br>
Mail: {{ $conf['letterEmail'] }}<br><br>

Viele Grüße
<br><br><br><br>
{{ $conf['letterName'] }} ({{ $conf['letterSubName'] }})
