<template>
    <div class="admin-content__form-grid">
        <div v-if="isEmpty" class="text-center py-4">
            <img src="../assets/images/search-no-result.jpg" alt="" class="admin-content__form-grid-empty-img">
            <p class="text-muted mb-0 fs-14 mt-2">Chưa có dữ liệu nào</p>
        </div>
        <div v-else class="row g-3">
            <div v-for="item in combinedItems" :key="item.id" class="col-md-6 col-lg-4">
                <div class="premium-card">
                    <div class="premium-card__header">
                        <div class="premium-card__title">{{ item.name || 'Item' }}</div>
                        <button 
                            type="button" 
                            class="premium-card__delete-btn" 
                            @click="remove(item.id, item.isTemp)"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="premium-card__body">
                        <div class="premium-card__badge">
                            <span class="premium-card__badge-text">ID: {{ item.id }}</span>
                        </div>
                    </div>
                    <div class="premium-card__shine"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-3 align-items-center mt-4">
        <select v-model="selectedOption" class="valid-elm form-select">
            <option disabled value="" selected>Chọn giá trị</option>
            <option v-for="option in filteredOptions" :key="option.id" :value="option.id">
                {{ option.name }}
            </option>
        </select>
        <button type="button" 
            class="btn btn-success px-4 shadow-sm fs-16 btn-enhanced"
            :disabled="!selectedOption"
            @click="add()"
        >
            Thêm
        </button>
    </div>
</template>

<script>
export default {
    name: 'ItemDashboard',
    emits: ['remove'],
    props: {
        items: {
            type: Array,
            default: () => []
        },
        options: {
            type: Array,
            default: () => []
        },
    },
    data() {
        return {
            selectedOption: '',
            deletedIds: [],
            tempItems: [],
        }
    },
    computed: {
        filteredOptions() {
            const usedIds = new Set([...this.items, ...this.tempItems].map(item => item.id));
            return this.options.filter(option => !usedIds.has(option.id));
        },
        combinedItems() {
            return [
                ...this.items.map(item => ({ ...item, isTemp: false })),
                ...this.tempItems.map(item => ({ ...item, isTemp: true }))
            ];
        },
        isEmpty() {
            return this.items.length === 0 && this.tempItems.length === 0;
        }
    },
    methods: {
        getIds() {
            let addedIds = this.tempItems.map(item => item.id);
            let deletedIds = [...this.deletedIds];
            const setA = new Set(addedIds);
            const setB = new Set(deletedIds);

            const duplicates = new Set([...setA].filter(x => setB.has(x)));
            addedIds = addedIds.filter(x => !duplicates.has(x));
            deletedIds = deletedIds.filter(x => !duplicates.has(x));

            return {
                addedIds: addedIds,
                deletedIds: deletedIds
            };
        },
        add() {
            if (!this.selectedOption || !this.options) return;
  
            const selectedItem = this.options.find(option => option.id === this.selectedOption);
            if (selectedItem) {
                this.tempItems = [...this.tempItems, selectedItem];
            }
            this.selectedOption = '';
        },
        remove(id, isTemp = false) {
            if (isTemp) {
                this.tempItems = this.tempItems.filter(item => item && item.id !== id);
                return;
            }
            this.deletedIds.push(id);
            this.$emit('remove', id);
        }
    },
}
</script>

<style>
.admin-content__form-grid {
    padding: 0;
}

.admin-content__form-grid-empty-img {
    max-width: 140px;
}

@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-8px); }
    100% { transform: translateY(0px); }
}

.admin-content__form-grid-empty:hover .admin-content__form-grid-empty-img {
    transform: scale(1.1);
}

.premium-card {
    position: relative;
    height: 100%;
    border-radius: 8px;
    background: white;
    overflow: hidden;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    border: 1px solid rgba(0,0,0,0.05);
}

.premium-card:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
    z-index: 1;
}

.premium-card:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #4158D0, #C850C0, #FFCC70);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.premium-card:hover:before {
    opacity: 1;
}

.premium-card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    background: radial-gradient(circle at top left, rgba(248,249,255,1) 0%, rgba(255,255,255,1) 100%);
}

.premium-card__title {
    font-size: 14px;
    font-weight: 600;
    color: #333;
}

.premium-card__delete-btn {
    flex-shrink: 0;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    border: 1px solid rgba(220,53,69,0.2);
    color: #dc3545;
    transition: all 0.3s ease;
    overflow: hidden;
    position: relative;
}

.premium-card__delete-btn:after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background: radial-gradient(circle, rgba(220,53,69,1) 0%, rgba(220,53,69,0) 70%);
    opacity: 0;
    transition: all 0.3s ease;
}

.premium-card__delete-btn:hover {
    transform: scale(1.1);
    background-color: #dc3545;
    color: white;
    border-color: #dc3545;
    box-shadow: 0 2px 8px rgba(220,53,69,0.2);
}

.premium-card__delete-btn:hover:after {
    opacity: 0.2;
}

.premium-card__delete-btn i {
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
    font-size: 12px;
}

.premium-card__body {
    padding: 1rem;
    position: relative;
    background-color: #f8f9fc;
}

.premium-card__badge {
    display: inline-flex;
    padding: 0.3rem 0.7rem;
    background: linear-gradient(135deg, rgba(57,106,252,0.1) 0%, rgba(41,72,255,0.05) 100%);
    border-radius: 16px;
    border: 1px solid rgba(57,106,252,0.2);
}

.premium-card__badge-text {
    font-size: 12px;
    font-weight: 500;
    color: #396afc;
}

.premium-card__shine {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(120deg, rgba(255,255,255,0) 30%, rgba(255,255,255,0.5) 38%, rgba(255,255,255,0) 48%);
    background-size: 200% 200%;
    opacity: 0;
    transition: all 0.3s ease;
    pointer-events: none;
}

.premium-card:hover .premium-card__shine {
    opacity: 1;
    animation: shine 1.5s infinite;
}

@keyframes shine {
    to {
        background-position: 200% 0;
    }
}

.btn-enhanced {
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.2) !important;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-enhanced:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 15px rgba(40, 167, 69, 0.3) !important;
}

.btn-enhanced:before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.2) 50%, rgba(255,255,255,0) 100%);
    transition: all 0.6s ease;
}

.btn-enhanced:hover:before {
    left: 100%;
}
</style>