<template>
    <div class="rich-text-editor">
        <div class="editor-toolbar">
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
        <button type="button" class="tool-btn" title="Danh sách" @click="execCommand('insertUnorderedList')">
            <i class="fas fa-list-ul"></i>
        </button>
        <button type="button" class="tool-btn" title="Đánh số" @click="execCommand('insertOrderedList')">
            <i class="fas fa-list-ol"></i>
        </button>
        <div class="tool-separator"></div>
        <button type="button" class="tool-btn" title="Liên kết" @click="insertLink()">
            <i class="fas fa-link"></i>
        </button>
        <button type="button" class="tool-btn" title="Hình ảnh" @click="triggerImageUpload()">
            <i class="fas fa-image"></i>
        </button>
        <input 
            type="file" 
            ref="fileInput" 
            accept="image/*" 
            style="display: none" 
            @change="handleImageUpload"
        >
        </div>
        
        <div class="editor-content" 
            ref="editorContent" 
            contenteditable="true" 
            @input="updateContent"
            placeholder="Nhập nội dung tại đây...">
        </div>
    </div>
</template>

<script>
export default {
    name: 'RichTextEditor',
    props: {
        value: {
            type: String,
            default: ''
        },
    },
    data() {
        return {
            tempImages: [], imageUploadQueue: []
        }
    },
    mounted() {
        
    },
    methods: {
        execCommand(command, value = null) {
            document.execCommand(command, false, value);
            this.updateContent();
        },
        triggerImageUpload() {
            this.$refs.fileInput.click();
        },
        handleImageUpload(event) {
            const file = event.target.files[0];
            if (!file || !file.type.match('image.*')) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                this.insertImage(e.target.result);
            };
            reader.readAsDataURL(file);
            event.target.value = '';
        },
        insertImage(url) {
            const editor = this.$refs.editorContent;
            const img = document.createElement('img');
            img.src = url;
            img.style.maxWidth = '300px';
            editor.appendChild(img);
            editor.scrollTop = editor.scrollHeight;
            this.updateContent();
        },
        updateContent() {
            const content = this.$refs.editorContent.innerHTML;
            this.$emit('input', content);
        },
    },
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
</style>