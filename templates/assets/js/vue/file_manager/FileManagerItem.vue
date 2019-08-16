<template>
    <li v-bind:class="{ deleting: isDeleting }" class="text-white pt-3">
        <div class="d-flex flex-row">
            <div>
                <a v-bind:href="url" target="_blank">
                    <img
                        v-bind:src="url"
                        v-bind:alt="originalFilename"
                    >
                </a>
            </div>
            <div class="pl-2">

            </div>
            <div class="pl-2">
                <button title="clear image" v-on:click="onDeleteClick" class="btn btn-warning font-weight-bold">X</button>
            </div>
        </div>
    </li>
</template>

<script>
    import moment from 'moment';
    import $ from 'jquery';

    export default {
        data() {
            return {
                isDeleting: false,
            }
        },
        props: ['url', 'originalFilename'],
        watch: {

        },
        methods: {
            onDeleteClick() {
                this.$emit('delete-file');
                this.isDeleting = true;
            },
        },
        created() {

            // this.timer = setInterval(this.updatedPonkaAddedAtAgo, 60000);
        },
        beforeDestroy() {
            // clearInterval(this.timer);
        },
        mounted() {
            $(this.$el).find('button').tooltip();
        }
    }
</script>

<style scoped lang="scss">
    img {
        width: 100px;
        border-radius: 5px;
    }
    .deleting {
        opacity: .3;
    }
    button {
        cursor: pointer;
    }

</style>
