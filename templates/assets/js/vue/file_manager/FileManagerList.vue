<template>
    <div>
        <transition-group name="list" tag="ul">
            <FileManagerItem
                v-for="file in files"
                v-bind:key="file.id"
                v-bind:url="file.url"
                v-bind:original-filename="file.originalFilename"
                v-on:delete-file="onDeleteFile(file)"
            ></FileManagerItem>
        </transition-group>
    </div>
</template>

<script>
    import FileManagerItem from './FileManagerItem';

    export default {
        components: {
            FileManagerItem: FileManagerItem
        },
        props: ['files'],
        methods: {
            onDeleteFile(file) {
                this.$emit('delete-file', file);
            }
        }
    }
</script>

<style scoped lang="scss">
    .list-enter-active, .list-leave-active {
      transition: all 1s;
    }
    .list-enter, .list-leave-to {
      opacity: 0;
      transform: translateY(30px);
    }
    ul {
        list-style: none
    }
</style>
