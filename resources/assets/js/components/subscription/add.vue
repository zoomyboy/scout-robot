<template>
	<div class="cp-wrap cp-subscription-add">
		<vf-form redirect="subscription.index" method="post" action="/api/subscription"  msg="Beitrag erfolgreich hinzugefügt">
			<grid><article>
				<panel>
					<vf-text name="title" label="Name"></vf-text>
					<vf-text name="amount" label="Beitrag" :mask="{mask: '9{1,},99'}"></vf-text>
					<vf-select url="/api/fee" name="fee" label="NaMi-Beitrag" nullable></vf-select>

					<vf-submit>Absenden</vf-submit>
				</panel>
			</article></grid>
		</vf-form>
	</div>
</template>

<style type="less">
	.cp-subscription-add form, .cp-subscription-add .form-container {
		height: 100%;
	}
	.form-container form:last-child {
		display: none;
	}
</style>

<script>
	import {mapState} from 'vuex';

	export default {
		data: function() {
			return {
			}
		},
		components: {
			panel: require('z-ui/panel/panel.vue'),
			grid: require('z-ui/grid/grid.vue'),
			vfDate: require('z-ui/form/fields/date.vue'),
			panelcontent: require('z-ui/panel/content.vue'),
		},
		methods: {
			loadgroup: function(v) {
				this.loadedActivityId = v;
			}
		},
		mounted: function() {
			var vm = this;

			axios.get('/api/activity').then((ret) => {
				vm.activities = ret.data;
			});
		}
	}
</script>
