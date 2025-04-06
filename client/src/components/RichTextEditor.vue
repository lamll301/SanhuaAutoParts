<template>
    <div class="editor-notice" v-if="hasImages">
        <i class="fas fa-exclamation-circle"></i> 
        <span>Lưu ý: Không chỉnh sửa trực tiếp dòng chứa nội dung ảnh</span>
    </div>
    <div class="rich-text-editor">
        <div class="editor-toolbar">
            <button type="button" class="tool-btn" title="Reset nội dung" @click="resetContent">
                <i class="fas fa-undo"></i>
            </button>
            <div class="tool-separator"></div>
            <button type="button" class="tool-btn" title="In đậm" @click="execCommand('bold')">
                <i class="fas fa-bold"></i>
            </button>
            <button type="button" class="tool-btn" title="In nghiêng" @click="execCommand('italic')">
                <i class="fas fa-italic"></i>
            </button>
            <button type="button" class="tool-btn" title="Gạch chân" @click="execCommand('underline')">
                <i class="fas fa-underline"></i>
            </button>
            <div class="tool-separator"></div>
            <button type="button" class="tool-btn" title="Màu chữ">
                <i class="fas fa-palette"></i>
                <input type="color" @input="changeTextColor($event)" class="color-picker">
            </button>
            <button type="button" class="tool-btn" title="Danh sách" @click="execCommand('insertUnorderedList')">
                <i class="fas fa-list-ul"></i>
            </button>
            <button type="button" class="tool-btn" title="Đánh số" @click="execCommand('insertOrderedList')">
                <i class="fas fa-list-ol"></i>
            </button>
            <div class="tool-separator"></div>
            <button type="button" class="tool-btn" title="Căn trái" @click="execCommand('justifyLeft')">
                <i class="fas fa-align-left"></i>
            </button>
            <button type="button" class="tool-btn" title="Căn giữa" @click="execCommand('justifyCenter')">
                <i class="fas fa-align-center"></i>
            </button>
            <button type="button" class="tool-btn" title="Căn phải" @click="execCommand('justifyRight')">
                <i class="fas fa-align-right"></i>
            </button>
            <div class="tool-separator"></div>
            <button type="button" class="tool-btn" title="Liên kết" @click="insertLink">
                <i class="fas fa-link"></i>
            </button>
            <button type="button" class="tool-btn" title="Hình ảnh" @click="$refs.fileInput.click()">
                <i class="fas fa-image"></i>
            </button>
            <input type="file" ref="fileInput" accept="image/*" style="display: none" 
                @change="handleImageChange"
            >
        </div>
        
        <div class="editor-content" ref="editorContent" contenteditable="true" placeholder="Nhập nội dung tại đây..."
            v-html="content" @keydown="handleKeydown"
        >
        </div>
    </div>
    <ImagePreview 
        v-if="hasImages"
        :images="images"
        :tempImages="tempImages"
        @remove-image="handleRemoveImage"
        @remove-temp-image="handleRemoveTempImage"
        @set-thumbnail="setThumbnail"
    />
    
</template>

<script>
import ImagePreview from './ImagePreview.vue';

export default {
    name: 'RichTextEditor',
    components: {
        ImagePreview
    },
    props: {
        content: {
            type: String,
            default: ''
        },
        images: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            tempImages: {},
            selectedThumbnail: null
        }
    },
    computed: {
        hasImages() {
            return Object.keys(this.tempImages).length > 0 || this.images.length > 0;
        }
    },
    mounted() {
        if (this.content) {
            this.$refs.editorContent.innerHTML = this.content;
        }
    },
    methods: {
        getContent() {
            return this.$refs.editorContent.innerHTML;
        },
        getImages() {
            return this.tempImages;
        },
        setThumbnail(selectedThumbnail) {
            this.selectedThumbnail = selectedThumbnail;
        },
        resetContent() {
            this.$refs.editorContent.innerHTML = '';
            this.tempImages = {};
        },
        execCommand(command, value = null) {
            document.execCommand(command, false, value);
            this.$refs.editorContent.focus();
        },
        changeTextColor(event) {
            const color = event.target.value;
            this.execCommand('foreColor', color);
        },
        insertLink() {
            const url = prompt('Nhập URL liên kết:', 'http://');
            if (url) {
                this.execCommand('createLink', url);
            }
        },
        handleImageChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            const imageName = `${file.name.split('.')[0]}-${Date.now()}.${file.name.split('.').pop()}`;
            this.tempImages[imageName] = file;
            this.insertImagePlaceholder(imageName);
            event.target.value = '';
        },
        insertImagePlaceholder(image) {
            const placeholder = document.createElement('div');
            placeholder.dataset.image = image;
            placeholder.style.cssText = 'color: red; font-weight: bold;';
            placeholder.textContent = `[image: ${image}]`;
            
            this.$refs.editorContent.appendChild(placeholder);
            this.$refs.editorContent.appendChild(document.createElement('br'));
        },
        handleKeydown(event) {
            if (event.key === 'Delete' || event.key === 'Backspace') {
                const selection = window.getSelection();
                const range = selection.getRangeAt(0);
                const container = range.startContainer.parentNode;
                if (container.hasAttribute('data-image')) {
                    const imageId = container.getAttribute('data-image');
                    delete this.tempImages[imageId];
                }
            }
        },
        handleRemoveTempImage(imageId) {
            if (!this.tempImages[imageId]) return;
            URL.revokeObjectURL(URL.createObjectURL(this.tempImages[imageId]));
            delete this.tempImages[imageId];
            this.removeImagePlaceholder(imageId);
        },
        handleRemoveImage(imageId, imageName) {
            this.$emit('remove-image', imageId);
            this.removeImagePlaceholder(imageName);
        },
        removeImagePlaceholder(image) {
            const editor = this.$refs.editorContent;
            const placeholder = editor.querySelector(`[data-image="${image}"]`);
            if (placeholder) {
                placeholder.remove();
            }
        }
    }
}
</script>

<style scoped>
.rich-text-editor {
    border-radius: 4px;
    border: 1px solid #e0e0e0;
    overflow: hidden;
    background: #fff;
}
.editor-toolbar {
    display: flex;
    gap: 2px;
    padding: 8px;
    background: #f5f5f5;
    border-bottom: 1px solid #e0e0e0;
    flex-wrap: wrap;
}
.tool-btn {
    background: white;
    border: 1px solid #e0e0e0;
    color: #555;
    width: 32px;
    height: 32px;
    border-radius: 3px;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.tool-btn:hover {
    background: #f0f0f0;
    color: #333;
}
.tool-separator {
    width: 1px;
    background: #e0e0e0;
    margin: 0 4px;
}
.editor-content {
    min-height: 300px;
    padding: 15px;
    outline: none;
    font-size: 15px;
    line-height: 1.6;
    color: #333;
}
.editor-content:empty:before {
    content: attr(placeholder);
    color: #aaa;
    display: block;
    cursor: text;
}
.editor-content img {
    max-width: 100%;
    height: auto;
}
.editor-content a {
    color: #0066cc;
    text-decoration: underline;
}
.editor-content ul, .editor-content ol {
    padding-left: 20px;
    margin: 10px 0;
}
.editor-notice {
    background-color: #fff8e6;
    color: #ff9800;
    padding: 8px 12px;
    border-radius: 4px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    font-size: 14px;
    border-left: 4px solid #ff9800;
}
.editor-notice i {
    margin-right: 8px;
}
.color-picker {
    position: absolute;
    opacity: 0;
    width: 32px;
    height: 32px;
    cursor: pointer;
}
.tool-btn:hover .color-picker {
    opacity: 1;
}
</style>