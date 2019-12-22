<template>
    <div class="photo-manager__content row" >
        <div class="photo-manager__search-field">
            <div  class="form-group form-other">
                <input 
                    type="text"
                    id="query"
                    name="query" 
                    class="form-control" 
                    v-model="search"
                    required
                />
                <label for="query"><i class="fas fa-search"></i>  Szukaj</label>
            </div>
        </div>
        <div class="photo-manager__uploader" id="file_uplaoder">
            <PhotoManagerUploader @new-file="onUploadedFile"></PhotoManagerUploader> 
        </div>

        <div class="photo-manager__list">
            <PhotoGalleryList
                :items="filteredItems"
                :isOpenReferance="isOpenReferance"
                @active-image="onActiveImage"
                @delete-image="onDeleteImage"
                @checked-image="onCheckedImage"
                @put-description="setDescription"
            ></PhotoGalleryList> 
        </div>  
        
        <div class="loader" v-if="loader == false"><div></div><div></div></div>    
    </div>
</template>

<script>
    import axios from 'axios';
    import qs from "qs";
    import PhotoGalleryList from '../PhotoGallery/PhotoGalleryList';
    // import PhotoReferenceApp from '../PhotoReference/PhotoReferenceApp';
    import PhotoManagerUploader from './PhotoManagerUploader';
    import PhotoManagerSearch from './PhotoManagerSearch';
    import debounce from 'lodash.debounce';

    const path = '/api/files';
    const referenceModle = $('#file_feference_modal');

    // console.log(referenceModle)
    export default {
        components: {
            PhotoGalleryList,
            PhotoManagerUploader, 
            PhotoManagerSearch
        },
        props: ['path'],
        data() {
            return {
                images: [],
                search: '',
                loader: false,
                isOpenReferance: false
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
                axios
                    .delete(image['@id'])
                    .then(() => {
                        this.$delete(this.images, this.images.indexOf(image))
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
            fetchImagesData() {
                axios
                    .get(this.path)
                    .then(res => {
                        this.images = res.data.items;
                        this.loader = true;
                        if(referenceModle.length) {
                            this.isOpenReferance = true;
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
            onCheckedImage(item) {
                const form = $('#tinymce_editor');
                let namePost  = form.attr('data-name');
                let idPost = form.attr('data-post-id');
                let data = [];
                data.item = item;

                axios({
                        method: 'PUT',
                        data: qs.stringify(data),
                        url: `${item['@id']}/${namePost}/${idPost}`,
                    })
                    .then( (res) => {
                        console.log(res.data)
                    })
                    .catch(err => console.log(err));
            }
        },
        computed: {
            filteredItems() {
                return this.images.filter((item)=>{
                    return item.originalFilename.match(this.search);
                });
            }
        },
        created() {
            this.fetchImagesData();
        },
    }
</script>

<style lang="scss" scoped>
.photo-manager__content {
    display: flex;
    justify-items: center;
    align-items: center;
    flex-direction: column; 
}
 
.loader {
    width: 64px;
    height: 64px;
    position: absolute;
    // align-items: center;
    margin-top: 300px;
}
.loader div {
  position: absolute;
  border: 4px solid rgb(204, 235, 255);
  opacity: 1;
  border-radius: 50%;
  animation: lds-ripple 1s cubic-bezier(0, 0.2, 0.8, 1) infinite;
}
.loader div:nth-child(2) {
  animation-delay: -0.5s;
}
@keyframes lds-ripple {
  0% {
    top: 28px;
    left: 28px;
    width: 0;
    height: 0;
    opacity: 1;
  }
  100% {
    top: -1px;
    left: -1px;
    width: 58px;
    height: 58px;
    opacity: 0;
  }
}

</style>