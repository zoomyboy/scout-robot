<template>
	<div class="cp-member cp-wrap">
		<grid>
			<article>
				<panel title="Übersicht">
					<div slot="action">
						<buttonbar>
						<v-link route="member.add" right="member.manage" add size="sm"></v-link>
						</buttonbar>
					</div>
					<v-table
						:border="false"
						v-on:info="openInfo"
						id="memberTable"
						v-if="members !== false"
						controller="member"
						ref="table"
						:actions="actions"
						:headings="headings"
						:collection="members"
						delete-msg="Mitglied erfolgreich gelöscht"
	  					scrolling
					>
				
					</v-table>
				</panel>
			</article>
			<aside class="rows-stretch" v-if="member">
				<payment ref="payment" :member="member" @closeinfo="member = false" @changepayment="reloadmember"></payment>
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
				members: false,
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
				require(['z-ui/grid/grid.vue'], resolve);
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
			reloadmember: function(member) {
				var vm = this;
			
				axios.get('/api/member/'+member.id+'/table').then(function(data) {
					vm.members = vm.members.map(function(m) {
						if (m.id == data.data.id) {return data.data;}

						return m;
					});
				});
			},
			openInfo: function(row, action) {
				this.member = row;
			}
		},
		mounted: function() {
			var vm = this;

			axios.get('/api/member/table').then(function(data) {
				vm.members = data.data;
			});
		}
	}
</script>

