<template>
    <div class="gallery-modal gallery-modal__wrapper">
        
         <!-- PANEL CONTROL -->
        <div class="gallery-modal__control-panel">
            <!-- <span  @click="isActive(item)" >
                <i v-show="item.isPublished" class="far fa-eye icon-link" title="ukryj"></i>
                <i v-show="!item.isPublished" class="far fa-eye-slash icon-link" title="uaktywnij"></i>
            </span> -->
            
            <span @click="openDescription(item)" class="gallery-modal__control-icon gallery-modal__control-icon--info" title="Opis">
                <i class="fas fa-info-circle icon-link"></i>
            </span>

            <span @click="clickDelete(item, index)" class="gallery-modal__control-icon gallery-modal__control-icon--delete" title="Usuń">
                <i class="fas fa-trash-alt icon-link"></i>
            </span>

            <span @click="openPhotoBar(item, index, volume)" class="gallery-modal__control-icon gallery-modal__control-icon--" title="Panel zdjęć">
                <i class="fab fa-buffer icon-link"></i>
            </span>

            <span @click.esc="closeModal"  class="gallery-modal__control-icon  gallery-modal__control-icon--close" title="Zamknij">
                <i class="fas fa-window-close"></i>
            </span>
        </div>
        <div class="gallery-modal__main-content">
             <div class="arrow gallery-modal__main_arrow--left" @click="prev">
                <i class="fas fa-arrow-alt-circle-left gallery-modal__main_arrow"></i>
            </div>

            <div class="gallery-modal__main-photo">

                <!-- PHOTO-BAR -->
                <div class="gallery-modal__photo-bar" :class="{ show: photobar }">
                    <div v-if="quantity>4" class="gallery-modal__photo-bar__control">
                        <div class="gallery-modal__photo-bar__control--left" >
                            <div v-if="quantity>6" @click="volumePhotoBar(item, index, 6)" :class="{isActive: volume == 6}" >6</div>
                            <div v-if="quantity>4" @click="volumePhotoBar(item, index, 4)" :class="{isActive: volume == 4}">4</div>
                        </div>
                        <div class="gallery-modal__photo-bar__control--right"  v-if="volume<quantity">
                            <div @click="double = true" :class="{isActive: double}">2x</div>
                            <div @click="double = false" :class="{isActive: double == false}">1x</div>
                        </div>
                    </div>
                 
                    <div v-if="quantity>4" class="arrow gallery-modal__photo-bar__arrow--left" @click="prevBar">
                        <i class="fas fa-arrow-alt-circle-left gallery-modal__photo-bar__arrow" ></i>
                    </div>

                    <div v-if="barItems" class="gallery-modal__photo-bar__images">
                        <template v-for="(barItem, i) in barItems" >
                            <img 
                                @click="viewItem(barItem, i)"
                                :key="barItem.id" 
                                :src="barItem.url" 
                                :alt="barItem.originalFilename" 
                                :class="{isEdit: barItem.url == item.url}"
                            />
                        </template>
                    </div>
                    <div v-else class="gallery-modal__photo-bar--images">
                        <h3> Nie ma żadnych zdjęć </h3>
                    </div>

                    <div v-if="quantity>4" class="arrow gallery-modal__photo-bar__arrow--right" @click="nextBar">
                        <i class="fas fa-arrow-alt-circle-right gallery-modal__photo-bar__arrow"></i>
                    </div>
                </div>
                 <!-- DESCRIPTION BAR -->
                <div class="gallery-modal__description" :class="{ show: showDescription }">
                    <span v-if="write == false && item.description" @dblclick="changeDescription">
                        {{ item.description }}
                    </span>

                    <span v-else>
                        <textarea v-model="description" name="description" class="gallery-modal__description--textarea" cols="50" rows="5"></textarea>
                        <button class="btn btn-outline-primary" @click="setDescription(item)" title="wyślij">zmień</button>
                        <button v-if="item.description" class="btn btn-outline-primary" @click="write = false" title="wróć"><i class="fas fa-reply"></i></button>
                    </span>
                </div>
                <!-- MAIN PHOTO -->
                <img :src="item.url" :alt="item.originalFilename">

                <h3 class="gallery-modal__main-title">{{ item.originalFilename }} {{ numberImage }}</h3>   
            </div> 

             <div class="arrow gallery-modal__main_arrow--right"  @click="next">
                <i class="fas fa-arrow-alt-circle-right  gallery-modal__main_arrow"></i>
            </div>  
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            photobar: false,
            showDescription: false,
            write: false,
            description: '',
            volume: 6,
            indexBar: null,
            double: false,
            volSix: true,
            // isPublished: false
        }
    },
    props: ['item', 'quantity', 'index', 'barItems'],
    methods: {
        closeModal() {
            this.$emit('close-modal');
        },
        prev(index) {
            this.$emit('left-page')
        },
        next(index) {
            this.$emit('right-page');
        },        
        prevBar() {
            let index = null;
            let nextIndex = null;

            if(this.indexBar == 0) {
                this.indexBar = this.quantity-1;

            } else {
                this.indexBar--;
            }
            index = this.indexBar;

            if(this.double) {
                if(this.indexBar == 0) {
                    this.indexBar = this.quantity-1;
                } else {
                    this.indexBar--;
                }
                nextIndex = this.indexBar;
            } 
            
            this.$emit('to-left-bar', index, nextIndex);
        },
        nextBar() {
            let index = null;
            let nextIndex = null;

            if(this.indexBar < this.quantity) {
                if(this.indexBar+this.volume < this.quantity) {
                    index = this.indexBar+this.volume;
                } else {
                    index = this.indexBar+this.volume-this.quantity;
                }
            } else {
                this.indexBar = 0;
                index = this.indexBar+this.volume; 
            }
            this.indexBar++;

            if(this.double) {
                if(this.indexBar < this.quantity) {
                    if(this.indexBar+this.volume < this.quantity) {
                        nextIndex = this.indexBar+this.volume;
                    } else {
                        nextIndex = this.indexBar+this.volume-this.quantity;
                    }
                } else {
                    this.indexBar = 0;
                    nextIndex = this.indexBar+this.volume; 
                }
                this.indexBar++;
            }

            this.$emit('to-right-bar', index, nextIndex);
        },
        clickDelete(item, index) {
            this.$emit('delete-item', item, index)
        },
        volumePhotoBar(item, index, volume) {
            this.volume = volume;
            this.indexBar = index;
            this.$emit('open-photo-bar', item, index, volume);
        },
        openPhotoBar(item, index, volume) {
            if (this.quantity<4) {
                this.volume = this.quantity;
                volume = this.quantity;
            } else if (this.quantity<volume) {
                this.volume = 4;
                volume = 4;
            }

            this.photobar = !this.photobar;
            this.indexBar = index;
            this.$emit('open-photo-bar', item, index, volume);
        },
        doublePhotoBar() {
            this.double = !this.double
        },
        viewItem(barItem, indexBar) {
            this.$emit('view-item', barItem, indexBar)
        },
        openDescription() {
            this.showDescription = !this.showDescription;
        },
        setDescription(item) {
            this.write = false;
            let description = this.description;
            this.item.description = this.description;
            this.$emit('put-description', item, description);
            this.description = "";
        },
        changeDescription() {
            this.write = true;
            this.description = this.item.description;
        },
        // isActive(item) {
        //     this.item.isPublished = !this.item.isPublished;
        //     this.$emit('active-item', item, true);
        // },
    },  
    computed: {
        numberImage() {
            return '['+ (this.index+1) +'/'+ this.quantity+']';
        
        },
    },  
}
</script>

<style lang="scss" scoped>
    @import '../../../css/helper/_mixins';
    @import '../../../css/helper/_variables';

    .gallery-modal {
        &__wrapper {
            width: 100vw;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: rgba(00, 00, 00, .8);
            z-index: 16;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; 
        }

        &__control {
            &-panel {
                width: auto;
                position: fixed;
                top: 0;
                right: 0;
                padding: 10px;
                cursor: pointer;
                z-index: 16;
    
                @include atMedium {
                    padding: 10px 30px;
                    position: absolute;

                }
            }

            &-icon {
                padding: 0 5px;
                font-size: 1.2rem;
                transition: scale 1s ease-in;
                
            
                :hover {
                    transform: scale(1.05);
                }

                &--close {
                    color: red;
                }
            }
        }

        &__main {
            &-content {
                width: 85%;
                height: 85%;
            }

            &-title {
                position: absolute;
                bottom: -2em;
                left: 0;
                font-size: 1.2rem;
                color: #aaa;
            }

            &-photo {
                width: auto;
                height: 100%;
                position: relative;
                top: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column; 

                & img {
                    width: auto;
                    height: 100%;
                    background-repeat: no-repeat;
                    object-fit: cover;
                }
            }

            &_arrow {
                font-size: 2.5rem;
                align-self: center;
                position: relative;
                margin: 0 auto;

                &--left {
                    width: 100px;
                    position: absolute;
                    left: 0;
                    opacity: 1;
                    transition: opacity 1s ease-in;
                    color: rgba($mainDarkCyan, 0.7);

                    &:hover {  
                        opacity: 1;     
                        color: rgba($mainLithCyan, 1);
                        
                    }
                }

                &--right {
                    width: 100px;
                    position: absolute;
                    right: 0;
                    opacity: 1;
                    transition: opacity 1s ease-in;
                    color: rgba($mainDarkCyan, 0.7);

                    &:hover {
                        opacity: 1;  
                        color: rgba($mainLithCyan, 1);
                    }
                }   
            }
        }

        &__photo-bar {
            width: auto;
            height: 160px;
            position: absolute;
            top: 10px;
            display: none;
            justify-content: center;
            background-color: rgba($mainDark, 0.5);

            &__images {
                width: auto;
                height: 120px;
                position: relative;
                display: flex;
                justify-content: center;
                transform: translateY(30px);

                & img {
                    width: 150px;
                    height: 100%;
                    background-repeat: no-repeat;
                    object-fit: cover;
                    padding: 10px 5px;
                    cursor: pointer;

                    &:hover {
                        transform: scale(1.1);
                        border: 2px solid red;
                    }
                }
            }

            &__control {
                width: 50%;
                height: 20px;
                position: absolute;
                display: flex;
                justify-content: center;
                top: 0;
 

                &--left {
                    position: absolute;
                    left: 0;

                    & div {
                        display: inline-block;
                        border: 2px solid #888;
                        color: #888;
                        border-radius: 3px;
                        font-weight: 900;
                        padding: 0 4px;
                        margin: 5px;
                        cursor: pointer;
                        font-size: 0.7rem;

                        &:hover {
                            border: 2px solid #aaa;
                            background-color: rgba(#777, 0.4);
                            color: #aaa;
                        }
                    }
                }

                &--right {
                    position: absolute;
                    right: 0;
                    
                    & div {
                        display: inline-block;
                        border: 2px solid #888;
                        color: #888;
                        border-radius: 3px;
                        font-weight: 900;
                        padding: 0 0.5px;
                        margin: 5px;
                        cursor: pointer;
                         font-size: 0.7rem;

                        &:hover {
                            border: 2px solid #aaa;
                            background-color: rgba(#777, 0.4);
                            color: #aaa;
                        }
                    }
                }
            }

            &__arrow {
                font-size: 2rem;
                align-self: center;
                position: relative; 
                margin: 0 auto;
                color: rgba($mainWhite, 0.5);
                cursor: pointer;

                &:hover {       
                    transition: opacity 0.2s ease-in;
                    color: $mainLithCyan;
                }

                &--left {
                    left: 0;
                    width: 50px;
                    opacity: 1;
    
                    &:hover {       
                        transition: opacity 0.2s ease-in;
                        color: $mainLithCyan;
                        background-color: rgba($mainDarkCyan, 0.3);
                    }
            
                }

                &--right {
                    right: 0;
                    width: 50px;
                    opacity: 1;
                            
                    &:hover {
                        transition: opacity 0.2s ease-in;
                        color: $mainLithCyan;
                        background-color: rgba($mainDarkCyan, 0.3);
                    }
                }
            }
        }

        &__description {
            width: 500px;
            height: auto;
            position: absolute;
            display: none;
            justify-content: center;
            align-content: center;
            bottom: 20px;
            right: 20px;
            padding: 20px;
            background-color: rgba($mainDark, .6);
            color: $mainWhite;
            font-weight: 600;

            & p {
                // text-align: center;
            }

            &--textarea {
                background: none;
                color: #ccc;
                font-weight: 600;
                border: 1px solid blueviolet;
            }

            // & button {
            //     border: 1px solid blueviolet;
            //     color:blueviolet;
            //     font-weight: 400;

            //     &:hover {
            //         color: #fff;
            //         background-color: blueviolet;
            //     }
            // }
        }

        .arrow {
            height: 100%;
            cursor: pointer;
            top: 0;
            display: flex;
            background-color: none;
            overflow: hidden;
        }
    }
    
    .show {
        display: flex;
    }

    // .photobar {
    //     display: flex;
    // }

    .isActive {
        border: 2px solid #ccc !important;
        color: #ccc !important;
    }

    .isEdit {
        border: 2px solid blue !important;
    }
</style>