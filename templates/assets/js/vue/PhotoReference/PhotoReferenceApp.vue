<template>
    <div class="photo-manager__list">
        <PhotoGalleryList
            :items="images"
            @active-image="onActiveImage"
            @delete-image="onDeleteImage"
            @put-description="setDescription"
        ></PhotoGalleryList> 
    </div>
</template>

<script>
    import $ from 'jquery';
    import axios from 'axios';
    import qs from "qs";
    import debounce from 'lodash.debounce';
    import PhotoGalleryList from '../PhotoGallery/PhotoGalleryList';
    // import PhotoManagerApp from '../file_manager/PhotoManagerApp';
    
    export default {
        components: {
            PhotoGalleryList,
        },
        tamplate: '#photo_reference_template',
        props: ['path'],
        data() {
            return {
                images: [],
                search: '',
                loader: false,
            }
        },
        methods: {
            onUploadedFile(image) {
                // bail if already found - solves a race condition
                // when upload and polling finish at similar time
                if (this.images.find(oneImage => oneImage.id === image.id)) {
                    return;
                }

                this.images.unshift(image);
            },
            onDeleteImage(image) {
                const path = `/admin/${this.form.attr('data-name')}/${this.form.attr('data-post-id')}/references/${image.id}`;
                axios
                    .delete(path)
                    .then(() => {
                        this.$delete(this.images, this.images.indexOf(image));
                    })
                    .catch(err => console.log(err));
            },

            fetchImagesData() {
                axios
                    .get(this.path)
                    .then(res => {
                        this.images = res.data;
                        this.loader = true;
                    })
                    .catch(err => console.log(err));
            },
            onActiveImage(image, modal) {
                let data = [];
                data.isPublished = true;

                axios({
                        method: 'PUT',
                        data: qs.stringify(data),
                        url: image['@id'],
                    })
                    .then(res => {
                        if(!modal) {
                            this.images = this.fetchImagesData();
                        }
                    })
                    .catch(err => console.log(err));
            },
            setDescription(item, description, manager) {
                let data = [];
                data.description = description;

                axios({
                        method: 'PUT',
                        data: qs.stringify(data),
                        url: item['@id'],
                    })
                    .then( (res) => {
                        if(manager) {
                            this.images = this.fetchImagesData();
                        }
                    })
                    .catch(err => console.log(err));
            },
        },
        computed: {

        },
        created() {
            this.fetchImagesData();
        },
    }
</script>