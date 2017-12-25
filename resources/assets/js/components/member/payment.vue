<template>
	<panel ref="memberpanel" closeable v-on:close="$emit('closeinfo')" smalltitle>
		<div slot="tabs" class="tabs">
			<tab index="1" title="Zahlungen" active></tab>
			<tab index="2" title="Rechnung erstellen" v-if="hasPayments([1])"></tab>
			<tab index="3" title="Erinnerung erstellen" v-if="hasPayments([2])"></tab>
		</div>
		<panelcontent index="1" active>
			<buttonbar>
				<v-link @click="isadd = true" size="sm" add></v-link>
			</buttonbar>
			<legend v-if="label" v-text="label"></legend>
			<vf-form v-if="isadd || isedit" :action="action" :getmodel="isedit" :method="method" @afterpersist="loadmember" :msg="msg">
				<vf-number label="Jahr" size="sm" name="nr" ref="nrinput"></vf-number>
				<vf-select size="sm" name="status" label="Status" ref="statusinput" url="/api/status"></vf-select>
				<vf-text size="sm" name="amount" label="Betrag" :mask="{mask: '9{1,},99'}" ref="amountinput"></vf-text>
				<vf-hidden name="member" :value="member.id"></vf-hidden>
				<buttonbar>
					<vf-submit></vf-submit>
					<v-link @click="isadd=false;isedit=false" icon="close"></v-link>
				</buttonbar>
				<hr>
			</vf-form>
			<v-table
				v-if="payments.length > 0"
				:collection="payments"
				:actions="[{event: 'editpayment', icon: 'pencil'}]"
				:border="false"
				controller="payment"
				:headings="[{title: 'Jahr', data: 'nr'}, {title: 'Status', 'data': 'status[title]'}, {title: 'Betrag', data: 'amount', type: 'money'}]"
				:editaction="false"
				ref="table"
				@editpayment="editpayment"
				@deleted="deletepayment"
			>
			</v-table>
		</panelcontent>
		<panelcontent index="2" v-if="hasPayments([1])">
			<vf-form :action="'/pdf/'+this.member.id+'/bill'" method="post" :ajax="false" target="_blank">
				<vf-checkbox name="includeFamilies" :value="config.includeFamilies" label="Familien zusammenführen"></vf-checkbox>

				<vf-date name="deadline" :value="deadline" label="Deadline"></vf-date>

				<vf-submit>Rechnung anzeigen</vf-submit>
			</vf-form>
		</panelcontent>
		<panelcontent index="3" v-if="hasPayments([2])">
			<vf-form :action="'/pdf/'+this.member.id+'/remember'" method="post" :ajax="false" target="_blank">
				<vf-checkbox name="includeFamilies" :value="config.includeFamilies" label="Familien zusammenführen"></vf-checkbox>

				<vf-date name="deadline" :value="deadline" label="Deadline"></vf-date>

				<vf-submit>Zahlungserinnerung anzeigen</vf-submit>
			</vf-form>
		</panelcontent>
	</panel>
</template>

<style lang="less">
	.cp-wrap.cp-member-payment {

	}
</style>

<script>
	import {mapState} from 'vuex';
	import accounting from 'accounting';
	import moment from 'moment';

	export default {
		data: function() {
			return {
				isadd: false,
				isedit: false,
				innermember: false,
				payments: []
			};
		},
		props: {
			member: {
				required: true,
				default: false
			}
		},
		watch: {
			member: function(newVal, oldVal) {
				this.loadmember();
			},
			isadd: function(v) {
				if (v) {
					this.isedit = false;
					this.$nextTick(function() {
						this.$refs.amountinput.setValue('');
						this.$refs.nrinput.setValue('');
						this.$refs.statusinput.setValue('');
						this.getmodel = false;
					});
				}
			},
			payments: function(val) {
				this.$emit('changepayment', this.member);
			}
		},
		components: {
			vTable: require('z-ui/table.vue'),
			vfNumber: require('z-ui/form/fields/number.vue'),
			vfSelect: require('z-ui/form/fields/select.vue'),
			vfDate: require('z-ui/form/fields/date.vue'),
			vfHidden: require('z-ui/form/fields/hidden.vue'),
			panel: require('z-ui/panel/panel.vue'),
			tab: require('z-ui/panel/tab.vue'),
			panelcontent: require('z-ui/panel/content.vue'),
		},
		computed: {
			deadline: function() {
				var now = moment();

				if (!this.config.deadlineunit) {
					return '';
				}

				var units = ['', 'days', 'weeks', 'months', 'years'];

				if (this.config.deadlineunit.id >= 1 && this.config.deadlineunit.id <= 4) {
					return now.add(this.config.deadlinenr, units[this.config.deadlineunit.id]).format('DD.MM.YYYY');
				}

				return '';
			},
			method: function() {
				if (this.isadd) {
					return 'post';
				}
				if (this.isedit) {
					return 'patch';
				}
			},
			action: function() {
				if (this.isadd) {
					return '/api/member/'+this.member.id+'/payments';
				}
				if (this.isedit) {
					return '/api/member/'+this.member.id+'/payments/'+this.isedit.id;
				}
			},
			msg: function() {
				if (this.isadd) {
					return 'Hinzufügen erfolgreich';
				}
				if (this.isedit) {
					return 'Bearbeiten erfolgreich';
				}
			},
			label: function() {
				if (this.isadd) {
					return 'Zahlung hinzufügen';
				}
				if (this.isedit) {
					return 'Zahlung '+this.isedit.nr+' bearbeiten';
				}

				return false;
			},
			...mapState({
				tableStore: state => state.tables.payment,
				config: state => state.config
			})
		},
		methods: {
			loadmember: function() {
				var vm = this;

				vm.innermember = vm.member;

				axios.get('/api/member/'+this.innermember.id+'/payments').then(function(ret) {
					vm.payments = ret.data;
					vm.isedit = false;
					vm.isadd = false;
				});
			},
			editpayment: function(payment) {
				this.isedit = payment;
				this.isadd = false;

				this.$nextTick(function() {
					this.$refs.amountinput.setValue(accounting.formatMoney(payment.amount / 100, "", 2, '.', ','));
				});
			},
			deletepayment: function(payment) {
				this.payments = this.payments.filter(function(p) {
					return p.id != payment.id;
				});
			},
			hasPayments: function(ids) {
				if (!this.member) {return false;}
				if (!this.payments) {return false;}

				return this.payments.filter(function(p) {
					return ids.indexOf(p.status_id) !== -1;
				}).length > 0;
			}
		},
		mounted: function() {
			this.loadmember();
		}
	}
</script>
