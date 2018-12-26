<template>
    <div>
        <confirm-dialog v-for="(dialog, index) in dialogs" :key="index" :dialog="dialog">

        </confirm-dialog>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import ConfirmDialog from './Dialog';

    export default {
        components: { ConfirmDialog },
        computed: {
            ...mapGetters(['dialogs'])
        }
    }
</script>
