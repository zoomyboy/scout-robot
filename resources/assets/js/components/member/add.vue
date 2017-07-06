<template>
	<div class="cp-wrap cp-member-add">
		<heading title="Mitglied hinzufügen"></heading>

		<vf-form method="post" action="/api/member"  msg="Mitglied erfolgreich hinzugefügt" ajax>
			<tabs>
				<tab title="Stamdaten" active>
					<div class="row">
						<div class="col-md-6"><vf-text name="firstname" label="Vorname"></vf-text></div>
						<div class="col-md-6"><vf-text name="lastname" label="Nachname"></vf-text></div>
					</div>
					<div class="row">
						<div class="col-md-6"><vf-date label="Geburtsdatum" name="birthday"></vf-date></div>
						<div class="col-md-6"><vf-date name="joined_at" label="Eintrittsdatum"></vf-date></div>
					</div>
					<div class="row">
						<div class="col-md-6"><vf-text name="address" label="Adresse"></vf-text></div>
						<div class="col-md-6"><vf-text name="further_address" label="Addresszusatz"></vf-text></div>
					</div>
					<div class="row">
						<div class="col-md-6"><vf-text name="zip" label="PLZ"></vf-text></div>
						<div class="col-md-6"><vf-text name="city" label="Stadt"></vf-text></div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<vf-select name="country" url="/api/country" label="Staatsangehörigkeit" valueurl="/api/country/default"></vf-select>
						</div>
						<div class="col-md-6">
							<vf-select name="region" url="/api/region" label="Bundesland" valueurl="/api/region/default"></vf-select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6"><vf-text name="phone" label="Telefonnummer" :mask="{mask: '+99 999 9{1,}'}" value="+49"></vf-text></div>
						<div class="col-md-6"><vf-text name="mobile" label="Handynummer" :mask="{mask: '+99 999 9{1,}'}" value="+49"></vf-text></div>
					</div>
					<vf-text name="email" label="E-Mail-Adresse"></vf-text>
				</tab>
				<tab title="Sonstiges">
					<vf-text name="nickname" label="Spitzname"></vf-text>
					<vf-text name="other_country" label="Andere Staatsangehörigkeit"></vf-text>
					<vf-text name="business_phone" label="Geschäftliche Nummer" :mask="{mask: '+99 999 9{1,}'}" value="+49"></vf-text>
					<vf-text name="fax" label="Fax"></vf-text>
					<vf-text name="email_parents" label="E-Mail-Adresse Erziehungsberechtigter"></vf-text>
					<vf-select name="confession" label="Konfession" url="/api/confession"></vf-select>
				</tab>
			</tabs>
			
			<vf-submit></vf-submit>
		</vf-form>
	</div>
</template>


