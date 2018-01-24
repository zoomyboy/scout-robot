<template>
	<div v-if="subscription" class="cp-wrap cp-subscription-edit">
		<vf-form redirect="subscription.index" method="patch" :action="'/api/subscription/'+subscription.id"  msg="Beitrag erfolgreich bearbeitet" :getmodel="subscription">
			<grid><article>
				<panel :title="'Beitrag '+ subscription.title+' bearbeiten'">
					<vf-text name="title" label="Name"></vf-text>
					<vf-text name="amount" label="Beitrag" :mask="{mask: '9{1,},99'}" ref="amountinput"></vf-text>
					<vf-select url="/api/fee" name="fee" label="NaMi-Beitrag" nullable></vf-select>

					<vf-submit>Absenden</vf-submit>
				</panel>
			</article></grid>
		</vf-form>
	</div>
</template>

<style type="less">
	.cp-subscription-edit form, .cp-subscription-edit .form-container {
		height: 100%;
	}
	.form-container form:last-child {
		display: none;
	}
</style>

<script>
	import {mapState} from 'vuex';
	import accounting from 'accounting';

	export default {
		data: function() {
			return {
				subscription: false
			}
		},
		components: {
			panel: require('z-ui/panel/panel.vue'),
			grid: require('z-ui/grid/grid.vue')
		},
		mounted: function() {
			var vm = this;

			axios.get('/api/subscription/'+this.$route.params.id).then(function(data) {
				vm.subscription = data.data;

				vm.$nextTick(function() {
					vm.$refs.amountinput.setValue(accounting.formatMoney(vm.subscription.amount / 100, "", 2, '.', ','));
				});
			});
		}
	}
</script>
