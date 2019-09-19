<template>
    <div class="grid grid-album" >
        <PhotoGalleryItem
            v-for="(item, index) in items"
            :key="item.id"
            :item="item"
            :index="index"
            :quantity="items.length"
            @delete-item="onDeleteImage"
            @active-item="onActiveImage"
            @modal-open="handleModalOpen"
            @view-description="viewDescription($event)"
        />
 
        
        <transition name="open-modal">
            <PhotoGalleryModal 
                v-if="modalOpen"
                :index="index"
                :item="modalDataItem"
                :quantity="items.length"
                :barItems="barModelItems"
                @close-modal="modalOpen = false"
                @left-page="prevPageModal"
                @right-page="nextPageModal"
                @delete-item="onDeleteImage"
                @to-left-bar="goLeftBarModal"
                @to-right-bar="goRightBarModal"
                @open-photo-bar="openPhotoBarInModal"
                @view-item="handleModalOpen"
                @put-description="setDescription"
                @active-item="onActiveImage"
            />
        </transition>

        <transition  name="open-modal">
            <!-- Modal -->
            <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><strong>Tytuł:</strong> {{ item.originalFilename }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-if="write == false && item.description" class="modal-description">
                        <p><strong>Opis:</strong></p> {{ item.description }}
                    </div>

                    <div v-else class="modal-description--change form-group">
                        <label for="description">Opis</label>
                        <textarea v-model="description" name="description" class="form-control" cols="50" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button v-show="write == false" type="button" class="btn btn-outline-primary" @click="changeDescription" >Zmień</button>
                    <button v-show="write" class="btn btn-outline-primary" @click="setDescription(item, description, true)" title="wyślij">zatwierdź zmiane</button>
                    <button v-show="write && item.description" class="btn btn-outline-primary" @click="write = false" title="wróć"><i class="fas fa-reply"></i></button>
                </div>
                </div>
            </div>
            </div>
        </transition>
    </div>
</template>

<script>
    import $ from 'jquery';
    import PhotoGalleryItem from '../PhotoGallery/PhotoGalleryItem.vue';
    import PhotoGalleryModal from '../PhotoGallery/PhotoGalleryModal.vue';

    export default {
        components: {
            PhotoGalleryItem,
            PhotoGalleryModal
  
        },
        data() {
            return {
                modalOpen: false,
                modalDataItem: false,
                barModelItems: [],
                isDeleting: false,
                index: false,
                item: [],
                write: false,
                description: '',
            }
        },
        props: ['items'],
        methods: {
            onDeleteImage(item, index = null) {
                this.isDeleting = true;
                this.$emit('delete-image', item);

                if(index) {
                    this.nextPageModal()
                }
            },
            onActiveImage(item, modal=false) {
                this.$emit('active-image', item, modal);
            },
            handleModalOpen(item, index) {
                this.modalOpen = true;
                this.modalDataItem = item;
                this.index = index;
            },
            prevPageModal() {
                if(this.index == 0) {
                    this.index = this.items.length-1
                } else {
                    this.index = this.index-1;
                }

                this.modalDataItem = this.items[this.index];
            },
            nextPageModal() {
                if(this.index == this.items.length-1) {
                    this.index = 0
                } else {
                    this.index = this.index+1;
                }

                this.modalDataItem = this.items[this.index];
            },
            goRightBarModal(index, nextIndex) {
                this.barModelItems.shift();
                this.barModelItems.push(this.items[index]);

                if(nextIndex) {
                    this.barModelItems.shift();
                    this.barModelItems.push(this.items[nextIndex]);
                }
            },
            goLeftBarModal(index, nextIndex) {
                this.barModelItems.pop();
                this.barModelItems.unshift(this.items[index]);

                if(nextIndex) {
                    this.barModelItems.pop();
                    this.barModelItems.unshift(this.items[nextIndex]);
                }
            },
            openPhotoBarInModal(item, index, volume) {
                this.barModelItems = []; 

                for (let i = 0; i < volume; i++) {
                    if(index > this.items.length-1) {
                        index = 0;
                        this.barModelItems.push(this.items[index]);
                    } else {
                        this.barModelItems.push(this.items[index]);
                    }
                    index++;
                }
            },
            setDescription(item, description, manager = false) {
                this.write = false;
                this.item.description = description;
                this.$emit('put-description', item, description, manager);
                this.description = "";
            },
            viewDescription(item) {
                this.description = "";

                if(item.description) {
                    this.write = false;
                } else {
                    this.write = true;
                }
                this.item = item;
            },
            changeDescription() {
                this.write = !this.write;
                this.description = this.item.description
            }
        },
        computed: {

        },

    }
</script>

<style lang="scss" scoped>
    .modal-body, .modal-header, .modal-footer {

        background-color: rgba($color: #777, $alpha: 0.6);
    }

    .modal-title {
        font-size: 1.2rem;
    }

    .open-modal-enter-active, .open-modal-leave-active {
        transition: opacity 1s;
    }
    .open-modal-enter, .open-modal-leave-to /* .fade-leave-active below version 2.1.8 */ {
        opacity: 0;
    }

</style>

