<template>
    <input type="file" ref="fileInput" accept="image/*" style="display: none" 
        @change="handleImageChange"
    >
    <div class="temp-images-preview" v-if="hasImages">
        <h4>Ảnh đã tải lên:</h4>
        <div class="image-list">
            <div v-for="image in images" :key="image.id" class="image-item" 
                :class="{ 'selected-thumbnail': image.filename === selectedThumbnail }"
                @click="setThumbnail(image.filename)"
            >
                <img :src="getImageUrl(image.path)" class="image-thumbnail">
                <div class="image-info">
                    <span class="image-name">{{ image.filename }}</span>
                    <span class="image-size">{{ formatSize(image.size) }}</span>
                </div>
                <button type="button" @click.stop="removeImage(image.id, image.filename)" class="remove-image-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div v-for="(file, imageName) in tempImages" :key="imageName" class="image-item" 
                :class="{ 'selected-thumbnail': imageName === selectedThumbnail }"
                @click="setThumbnail(imageName)"
            >
                <img :src="getTempImageUrl(file, imageName)" class="image-thumbnail">
                <div class="image-info">
                    <span class="image-name">{{ imageName }}</span>
                    <span class="image-size">{{ formatSize(file.size) }}</span>
                </div>
                <button type="button" @click.stop="removeTempImage(imageName)" class="remove-image-btn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ImagePreview',
    emits: [
        'removeImage',
        'addImage'
    ],
    props: {
        images: {
            type: Array,
            required: true,
            default: () => []
        },
    },
    data() {
        return {
            selectedThumbnail: null,
            tempImages: {}, objectUrls: {},
            deletedImageIds: [],
        }
    },
    computed: {
        hasImages() {
            return Object.keys(this.tempImages).length > 0 || this.images.length > 0;
        }
    },
    watch: {
        images: {
            handler(newImages) {
                if (newImages.length > 0) {
                    const thumbnailImage = newImages.find(img => img.is_thumbnail);
                    if (thumbnailImage) {
                        this.selectedThumbnail = thumbnailImage.filename;
                    }
                } else {
                    this.selectedThumbnail = null;
                }
            },
            immediate: true
        }
    },
    methods: {
        getImageUrl(path) {
            return path.startsWith('http') ? path : `${process.env.VUE_APP_API_BASE_URL || ''}${path}`;
        },
        getTempImageUrl(file, imageName) {
            if (!this.objectUrls[imageName]) {
                this.objectUrls[imageName] = URL.createObjectURL(file);
            }
            return this.objectUrls[imageName];
        },
        removeTempImage(imageName) {
            const url = this.objectUrls[imageName];
            if (url) {
                URL.revokeObjectURL(url);
                delete this.objectUrls[imageName];
            }
            delete this.tempImages[imageName];
            this.checkAndSetThumbnail(imageName);
            this.$emit('removeImage', null, imageName);
        },
        removeImage(id, imageName) {
            this.deletedImageIds.push(id);
            this.checkAndSetThumbnail(imageName);
            this.$emit('removeImage', id, imageName);
        },
        formatSize(bytes) {
            if (!bytes) return '0 Bytes';
            const units = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            return `${(bytes / Math.pow(1024, i)).toFixed(2)} ${units[i]}`;
        },
        handleImageChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            const imageName = `${file.name.split('.')[0]}-${Date.now()}.${file.name.split('.').pop()}`;
            this.tempImages[imageName] = file;
            event.target.value = '';
            this.$emit('addImage', imageName);
        },
        setThumbnail(imageName) {
            this.selectedThumbnail = imageName;
        },
        checkAndSetThumbnail(imageNameDeleted) {
            if (this.selectedThumbnail === imageNameDeleted) {
                this.selectedThumbnail = null;
            }
        }
        
    }
}
</script>

<style>
.temp-images-preview {
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    padding: 15px;
    background: #f9f9f9;
    margin-top: 8px;
}
.temp-images-preview h4 {
    margin-top: 0;
    margin-bottom: 10px;
    color: #555;
}
.image-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
}
.image-item {
    position: relative;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 10px;
    background: white;
    cursor: pointer;
}
.image-thumbnail {
    width: 100%;
    height: 120px;
    object-fit: contain;
    margin-bottom: 8px;
    background: #f5f5f5;
}
.image-info {
    font-size: 12px;
    color: #666;
}
.image-name {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.image-size {
    color: #999;
}
.remove-image-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(255, 0, 0, 0.7);
    color: white;
    border: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}
.remove-image-btn:hover {
    background: rgba(255, 0, 0, 0.9);
}
.image-item.selected-thumbnail {
    position: relative;
    border: 2px solid #4CAF50;
    box-shadow: 0 0 10px rgba(76, 175, 80, 0.3);
    transform: translateY(-2px);
    transition: all 0.15s ease;
}
.image-item.selected-thumbnail::after {
    content: "✓ Thumbnail";
    position: absolute;
    top: -10px;
    left: 10px;
    background: #4CAF50;
    color: white;
    font-size: 10px;
    font-weight: bold;
    padding: 2px 8px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
}
.image-item.selected-thumbnail {
    animation: pulse 0.15s ease;
}
</style>