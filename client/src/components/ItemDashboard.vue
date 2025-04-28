<template>
    <div class="admin-content__form-grid">
        <div v-if="isEmpty" class="text-center py-4">
            <img src="../assets/images/search-no-result.jpg" alt="" class="admin-content__form-grid-empty-img">
            <p class="text-muted mb-0 fs-14 mt-2">Chưa có dữ liệu nào</p>
        </div>
        <div v-else class="row g-3">
            <div v-for="item in combinedItems" :key="item.id" class="col-md-3 col-lg-3">
                <div class="premium-card">
                    <div class="premium-card__header">
                        <div class="premium-card__meta">
                            <i class="fas fa-info-circle"></i>
                            <span>ID: {{ item.id }}</span>
                        </div>
                        <button 
                            type="button" 
                            class="premium-card__delete-btn" 
                            @click="remove(item.id, item.isTemp)"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="premium-card__body">
                        <div class="premium-card__content">
                            <div class="premium-card__title">
                                {{ typeof display === 'function' ? display(item) : item[display] }}
                            </div>
                            <div v-for="(inputField, index) in inputFields" :key="index">
                                <div v-if="inputField.type === 'number'"
                                    class="d-flex align-items-center justify-content-between" 
                                    style="margin-top: 12px;font-size: 12px;"
                                >
                                    <div>{{ inputField.text }}:</div>
                                    <div class="d-flex align-items-center">
                                        <input type="number" :value="getCurrentValue(item, inputField.key)"
                                        @input="updateFieldValue(item, inputField.key, $event.target.value)"
                                        class="form-control form-control-sm mx-2" min="0" default="0"
                                        style="font-size: 12px;width: 48px;"
                                    />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="premium-card__body-corner"></div>
                    </div>
                    <div class="premium-card__shine"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-3 align-items-center mt-4">
        <select v-model="selectedOption" class="valid-elm form-select">
            <option disabled value="" selected>Vui lòng chọn</option>
            <option v-for="option in filteredOptions" :key="option.id" :value="option.id">
                {{ typeof display === 'function' ? display(option) : option[display] }}
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
        display: {
            type: [Function, String],
            default: 'name'
        },
        inputFields: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            selectedOption: '',
            deletedIds: [],
            tempItems: [],
            itemFieldList: [],
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
                this.itemFieldList = this.itemFieldList.filter(item => item.id !== id);
                return;
            }
            this.deletedIds.push(id);
            this.itemFieldList = this.itemFieldList.filter(item => item.id !== id);
            this.$emit('remove', id);
        },
        getCurrentValue(item, fieldKey) {
            const updatedItem = this.itemFieldList.find(i => i.id === item.id);
            if (updatedItem && updatedItem[fieldKey] !== undefined) {
                return updatedItem[fieldKey];
            }
            
            if (item.pivot && item.pivot[fieldKey] !== undefined) {
                return item.pivot[fieldKey];
            }

            return 0;
        },
        getFieldValue() {
            return {
                addedIdsWithAttributes: this.itemFieldList.filter(field =>
                    this.tempItems.some(item => item.id === field.id)
                ),
                updatedIdsWithAttributes: this.itemFieldList.filter(field =>
                    this.items.some(item => item.id === field.id)
                ),
            }
        },
        updateFieldValue(item, key, value) {
            const id = item.id;
            const parsedValue = key === 'quantity' ? Number(value) : value;
            const existIndex = this.itemFieldList.findIndex(i => i.id === id);

            if (existIndex >= 0) {
                this.itemFieldList[existIndex][key] = parsedValue;
            } else {
                this.itemFieldList.push({ id, [key]: parsedValue });
            }
        },
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

.premium-card {
    position: relative;
    border-radius: 8px;
    background: white;
    box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    border: 1px solid rgba(0,0,0,0.05);
    z-index: 1;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
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
    height: 2px;
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
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
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

@media (max-width: 768px) {
    .col-md-3 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}

@media (max-width: 576px) {
    .col-md-3 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

.premium-card__body {
    padding: 1.25rem;
    background: linear-gradient(135deg, #f8f9fc 0%, #ffffff 100%);
    border-radius: 0 0 7px 7px;
    display: flex;
    position: relative;
    overflow: hidden;
    height: 100%;
}

.premium-card__content {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    z-index: 2;
}

.premium-card__title {
    font-size: 14px;
    font-weight: 600;
    color: #2c3e50;
    line-height: 1.4;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 10px;
    text-align: left;
    transition: all 0.3s ease;
    position: relative;
    margin-bottom: 6px;
}

.premium-card__meta {
    font-size: 12px;
    color: #6c757d;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 8px;
}

.premium-card__meta i {
    font-size: 11px;
    color: #4158D0;
    margin-top: 2px;
}

.premium-card__body::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 60px;
    height: 60px;
    background: radial-gradient(circle, rgba(65,88,208,0.08) 0%, rgba(65,88,208,0) 70%);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.premium-card__body::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 60px;
    background: radial-gradient(circle, rgba(200,80,192,0.08) 0%, rgba(200,80,192,0) 70%);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.premium-card:hover .premium-card__body::before,
.premium-card:hover .premium-card__body::after {
    opacity: 1;
}

.premium-card__body-corner {
    position: absolute;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 0 15px 15px;
    border-color: transparent transparent #4158D0 transparent;
    bottom: 0;
    right: 0;
    opacity: 0.3;
    transition: all 0.3s ease;
}

.premium-card:hover .premium-card__body-corner {
    border-width: 0 0 20px 20px;
    opacity: 0.5;
}
</style>