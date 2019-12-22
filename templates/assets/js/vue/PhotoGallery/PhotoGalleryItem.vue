<template> 
    <div class="photo-gallery photo-gallery__item" >
        <div :class="{ deleting: isDeleting }" class="text-white pt-3">
            <img 
                @click="modelOpen(item, index)"
                :src="item.urlSmall" 
                :alt="item.originalFilename"
            />
            

            <div class="photo-gallery__item--panel photo-gallery__item--panel-top">
                <span id="photo_gallery_checkbox" @click="isCheck(item)">
                    <input v-show="isOpenReferance" type="checkbox" v-model="isChecked">
                </span>

                <span>
                    <span 
                        @click="viewDescription(item)" 
                        data-toggle="modal" 
                        data-target="#descriptionModal"
                    >
                        <i  class="fas fa-info-circle icon-link" title="info"></i>
                    </span>

                    <span  @click="isActive(item)">
                        <i  v-show="isPublished" class="far fa-eye icon-link"  title="ukryj"></i>
                        <i v-show="!isPublished" class="far fa-eye-slash icon-link" title="uaktywnij"></i>
                    </span>

                    <span @click="onDeleteImage(item)" title="usuń">
                        <i class="fas fa-trash-alt icon-link"></i>
                    </span>
                </span>
            </div>
            <div class="photo-gallery__item--panel photo-gallery__item--panel-bottom">
                <h3 class="photo-gallery__item--title">
                    <span>{{ item.originalFilename }}</span>
                </h3>
            </div>
        </div> 
    </div>

</template>

<script>
    export default {
        data() {
            return {
                isDeleting: false,
                isPublished: '',
                isChecked: false,
                isReference: false,
            }
        },
        props: ['item', 'index', 'quantity', 'isOpenReferance'],
        methods: {
            modelOpen(item, index) {
                this.$emit('modal-open', item, index)
            },
             onDeleteImage(item) {
                this.isDeleting = true;
                this.$emit('delete-item', item);
            },
            isActive(item) {
                this.isPublished = !this.isPublished;
                this.$emit('active-item', item);
            },
            viewDescription(item) {
                this.$emit('view-description', item)
            }, 
            isCheck(item) {
                this.isChecked = !this.isChecked;
                this.$emit('checked-item', item);
            }
        },
        computed: {
            numberImage() {
                return '['+ (this.index+1) +'/'+ this.quantity+']';
            },
        },
        mounted() {
            this.isPublished = this.item.isPublished;
            if(this.item.articles.length) {
                this.isChecked = true;
            };
        },
    }
</script>

<style lang="scss" scoped>
    .deleting {
        opacity: .3;
    }
</style>
