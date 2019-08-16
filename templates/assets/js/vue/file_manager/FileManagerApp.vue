<template>
    <div class="row no-gutters">
        <div class="col-xs-12 col-md-6 px-5" style="background-color: #659dbd; padding-bottom: 150px;">
            <h2 class="text-center mb-5 pt-5 text-white">First: Upload Photo</h2>
            <FileManagerUploader
                v-on:new-file="onNewUploadedFile"
            ></FileManagerUploader>
        </div>
        <div class="col-xs-12 col-md-6 px-5" style="background-color: #7FB7D7; min-height: 40rem; padding-bottom: 150px;">
            <h2 class="text-center mb-5 pt-5 text-white">Second: Download Improved Photo</h2>
            <FileManagerList
                v-bind:files="files"
                v-on:delete-file="onDeleteFile"
            ></FileManagerList>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import FileManagerList from './FileManagerList';
    import FileManagerUploader from './FileManagerUploader';

    export default {
        components: {
            FileManagerList,
            FileManagerUploader
        },
        methods: {
            onNewUploadedFile(file) {
                // bail if already found - solves a race condition
                // when upload and polling finish at similar time
                if (this.files.find(oneImage => oneImage.id === file.id)) {
                    return;
                }

                this.files.unshift(file);
            },
            onDeleteFile(file) {
                axios
                    .delete(file['@id'])
                    .then(() => {
                        this.$delete(this.files, this.files.indexOf(file));
                    })
            },
            fetchFilesData() {
                axios
                    .get('/api/files')
                    // .then(response => console.log(response.data.items))
                    .then(response => this.files = response.data.items)
            }
        },
        data() {
            return {
                files: []
            }
        },
        mounted() {
            this.fetchFilesData();
        }
    }
</script>