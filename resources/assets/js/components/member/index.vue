<template>
	<div class="cp-member cp-wrap">
		<grid>
			<article>
				<panel title="Übersicht">
					<div slot="action">
						<v-link route="member.add" right="member.manage" add size="sm"></v-link>
					</div>
					<v-table
						:border="false"
						v-on:info="openInfo"
						id="memberTable"
						controller="member"
						ref="table"
						:actions="actions"
						:headings="headings"
						url="/api/member/table"
						delete-msg="Mitglied erfolgreich gelöscht"
	  					scrolling
					>
				
					</v-table>
				</panel>
			</article>
			<aside class="rows-3" v-if="member">
				<panel ref="memberpanel" :title="'Zahlungen für '+member.firstname+' '+member.lastname" closeable v-on:close="member = false" smalltitle>
					<payment ref="payment" :member="member"></payment>
				</panel>
			</aside>
		</grid>
	</div>
</template>

<script>
	export default {
		data: function() {
			return {
				actions: [
					{icon: 'info', event: 'info'}
				],
				member: false,
				payments: false
			};
		},
		computed: {
			headings: function() {
				var h = [
					{title: 'Nachname', data: 'lastname'},
					{title: 'Vorname', data: 'firstname'},
				];
				if (!this.member) {
					h.push({title: 'Adresse', data: 'address'});
					h.push({title: 'Eintritt', data: 'joined_at', type: 'date'});
					h.push({title: 'Stadt', data: 'city'});
					h.push({title: 'PLZ', data: 'zip'});
					h.push({title: 'Ausstände', type: 'callback', data: 'strikes'});
				}
				return h;
			}
		},
		components: {
			panel: function(resolve) {
				require(['z-ui/panel/panel.vue'], resolve);
			},
			grid: function(resolve) {
				require(['z-ui/grid.vue'], resolve);
			},
			vTable: function(resolve) {
				require(['z-ui/table.vue'], resolve);
			},
			payment: function(resolve) {
				require(['./payment.vue'], resolve);
			}
		},
		methods: {
			getStrikesAttribute(value) {
				if (value == 0) {
					return '---';
				}

				return (value / 100).toFixed(2).replace('.', ',')+' €';
			},
			openInfo: function(row, action) {
				this.member = row;
			}
		},
		mounted: function() {
			var vm = this;
		}
	}
</script>

