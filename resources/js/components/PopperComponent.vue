<template>
    <popper
        trigger="hover"
        :options="{
        placement: 'right',
        modifiers: { offset: { offset: '0,10px' } }
    }">
        <div class="popper btn-outline-secondary">
            {{ content }}
        </div>

        <div slot="reference">
            Reference Element
        </div>
    </popper>
</template>

<script>
    import Popper from 'vue-popperjs/dist/vue-popper.min.js';
    import 'vue-popperjs/dist/vue-popper.css';

    export default {
        components: {
            'popper': Popper
        },
        props: {
            propsContent: String,
        },
        created() {
            this.content = this.propsContent;
        },
    }
</script>
