<template>
    <div class="item-details-container">
        <table class="item-details-table">
            <thead>
                <tr>
                    <th v-for="(header, index) in headers" :key="index" :class="header.class">
                        {{ header.text }}
                    </th>
                    <th v-if="showTotalColumn" class="amount-header">Thành tiền</th>
                    <th class="action-column"></th>
                </tr>
            </thead>
            
            <tbody>
                <tr v-for="(item, rowIndex) in items" :key="rowIndex">
                    <td v-for="(header, colIndex) in headers" :key="colIndex" :class="header.class">
                        <div v-if="header.type === 'select'" class="select-wrapper">
                            <select 
                                class="custom-select" 
                                v-model="item[header.key]"
                                @change="handleSelectChange(item, rowIndex, header)"
                            >
                                <option disabled value="">-- {{ header.placeholder || 'Chọn' }} --</option>
                                <option 
                                    v-for="option in header.options"
                                    :key="option[header.optionValue || 'id']"
                                    :value="option[header.optionValue || 'id']"
                                    :title="option[header.optionText || 'name']"
                                >
                                    {{ option[header.optionText || 'name'] }}
                                </option>
                            </select>
                            <div class="select-value-preview" :title="getSelectedOptionText(item[header.key], header)">
                                {{ getSelectedOptionText(item[header.key], header) || `-- ${header.placeholder || 'Chọn'} --` }}
                            </div>
                        </div>
            
                        <input
                            type="number" class="form-input" :min="header.min || 0"
                            v-else-if="header.type === 'number'"
                            v-model.number="item[header.key]"
                            @input="handleInputChange(item, rowIndex, header)"
                        />
            
                        <input
                            type="text" class="form-input"
                            v-else-if="header.type === 'text'"
                            v-model="item[header.key]"
                            @input="handleInputChange(item, rowIndex, header)"
                        />
            
                        <span v-else>
                            {{ formatFieldValue(item[header.key], header) }}
                        </span>
                    </td>

                    <td class="amount" v-if="showTotalColumn">
                        <div class="amount-value">
                            {{ formatPrice(calculateRowTotal(item)) }}
                        </div>
                    </td>
            
                    <td class="action-column">
                        <button type="button" class="delete-btn" @click="removeItem(rowIndex)" title="Xóa">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 7L18.1327 19.1425C18.0579 20.1891 17.187 21 16.1378 21H7.86224C6.81296 21 5.94208 20.1891 5.86732 19.1425L5 7M10 11V17M14 11V17M15 7V4C15 3.44772 14.5523 3 14 3H10C9.44772 3 9 3.44772 9 4V7M4 7H20" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </td>
                </tr>
                
                <tr v-if="!items || items.length === 0">
                    <td :colspan="headers.length + (showTotalColumn ? 2 : 1)" class="text-center py-4">
                        <img src="../assets/images/search-no-result.jpg" alt="" class="empty-img">
                        <p class="text-muted mb-0 fs-14 mt-2">Chưa có dữ liệu nào</p>
                    </td>
                </tr>
            </tbody>
        </table>
        
        <div class="table-footer">
            <div class="footer-actions">
                <button type="button" class="add-row-btn" @click="addItem">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 4V20M4 12H20" stroke="#4F46E5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Thêm dòng
                </button>
            </div>
            
            <div v-if="showTotalColumn" class="total-amount-container">
                <span class="total-label">Tổng cộng:</span>
                <span class="total-value">{{ formatPrice(calculateGrandTotal()) }}</span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        headers: {
            type: Array,
            required: true
        },
        items: {
            type: Array,
            required: true
        },
        quantityField: {
            type: String,
            default: 'quantity'
        },
        priceField: {
            type: String,
            default: 'price'
        },
        showTotalColumn: {
            type: Boolean,
            default: true
        },
        calculateRowTotalFn: {
            type: Function,
            default: null
        },
        valueFieldMappings: {
            type: Object,
            default: () => ({})
        }
    },
    methods: {
        formatPrice(price) {
            if (!price && price !== 0) return '0 ₫';
            return new Intl.NumberFormat('vi-VN', { 
                style: 'currency', 
                currency: 'VND',
                minimumFractionDigits: 0
            }).format(price);
        },
        
        formatFieldValue(value, header) {
            if (header.type === 'number' && header.format === 'currency') {
                return this.formatPrice(value);
            }
            if (header.format === 'date' && value) {
                return new Date(value).toLocaleDateString('vi-VN');
            }
            return value;
        },
        
        getSelectedOptionText(value, header) {
            if (!value) return '';
            const selectedOption = this.findOptionByValue(header, value);
            return selectedOption ? selectedOption[header.optionText || 'name'] : '';
        },
        
        addItem() {
            const newItem = {};
            this.headers.forEach(header => {
                newItem[header.key] = header.default !== undefined ? header.default : 
                                        (header.type === 'number' ? 0 : '');
            });
            this.$emit('add', newItem);
        },
        
        removeItem(index) {
            this.$emit('remove', index);
        },
        
        calculateRowTotal(item) {
            if (this.calculateRowTotalFn) {
                return this.calculateRowTotalFn(item);
            }
            
            const quantity = Number(item[this.quantityField]) || 0;
            const price = Number(item[this.priceField]) || 0;
            return quantity * price;
        },
        
        calculateGrandTotal() {
            if (!this.items || this.items.length === 0) return 0;
            return this.items.reduce((total, item) => {
                return total + this.calculateRowTotal(item);
            }, 0);
        },
        
        handleInputChange(item, rowIndex, header) {
            this.$emit('update:items', [...this.items]);
            this.$emit('item-changed', { 
                item, 
                index: rowIndex, 
                field: header.key, 
                total: this.showTotalColumn ? this.calculateRowTotal(item) : null 
            });
        },
        
        handleSelectChange(item, rowIndex, header) {
            const selectKey = header.key;
            const selectValue = item[selectKey];
            
            if (selectValue && this.valueFieldMappings[selectKey]) {
                const selectedOption = this.findOptionByValue(header, selectValue);
                if (selectedOption) {
                    const mappings = this.valueFieldMappings[selectKey];
                    Object.keys(mappings).forEach(targetField => {
                        const sourceField = mappings[targetField];
                        if (selectedOption[sourceField] !== undefined) {
                            item[targetField] = selectedOption[sourceField];
                        }
                    });
                }
            }
            
            this.$emit('update:items', [...this.items]);
            this.$emit('item-changed', { 
                item, 
                index: rowIndex, 
                field: header.key, 
                total: this.showTotalColumn ? this.calculateRowTotal(item) : null,
                isSelect: true
            });
        },
        
        findOptionByValue(header, value) {
            if (header.options) {
                return header.options.find(opt => 
                    opt[header.optionValue || 'id'] === value
                );
            }
            return null;
        }
    }
}
</script>

<style scoped>
.empty-img {
    width: 140px;
}
.item-details-container {
    margin-top: 16px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.item-details-table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

thead tr {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
}

th {
    padding: 12px 16px;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-align: left;
}

.amount-header {
    width: 100px;
    text-align: right;
}

tbody tr {
    border-bottom: 1px solid #f1f5f9;
}

tbody tr:last-child {
    border-bottom: none;
}

td {
    padding: 12px 16px;
    min-height: 40px;
    vertical-align: middle;
}

.action-column {
    width: 70px;
    text-align: center;
}

.amount {
    text-align: right;
    width: 100px;
}

.amount-value {
    color: #1e293b;
    font-weight: 500;
    font-size: 14px;
}

.table-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 12px 16px;
}

.footer-actions {
    flex: 1;
}

.select-wrapper {
    position: relative;
    width: 100%;
}

.select-wrapper .custom-select {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
    z-index: 2;
}

.select-value-preview {
    width: 100%;
    padding: 8px 12px;
    background-color: white;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
    color: #334155;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 16px;
    cursor: pointer;
    transition: all 0.2s;
}

.custom-select:focus + .select-value-preview {
    border-color: #818cf8;
    box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2);
}

.form-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 14px;
    color: #334155;
    transition: all 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #818cf8;
    box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2);
}

.delete-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: none;
    background: #fee2e2;
    cursor: pointer;
    border-radius: 6px;
    transition: all 0.2s;
    margin: 0 0 0 auto;
}

.delete-btn:hover {
    background: #fecaca;
}

.add-row-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    font-size: 14px;
    color: #4f46e5;
    background: #fff;
    border: 1px solid #e0e7ff;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.add-row-btn:hover {
    background-color: #f1f5f9;
    border-color: #c7d2fe;
}

.total-amount-container {
    display: flex;
    align-items: center;
    min-width: 180px;
    justify-content: flex-end;
}

.total-label {
    font-size: 14px;
    color: #64748b;
    margin-right: 8px;
}

.total-value {
    font-size: 15px;
    font-weight: 600;
    color: #1e40af;
}

.text-center {
    text-align: center;
}

.py-4 {
    padding-top: 1rem;
    padding-bottom: 1rem;
}

.mb-0 {
    margin-bottom: 0;
}

.mt-2 {
    margin-top: 0.5rem;
}

.fs-14 {
    font-size: 14px;
}

.text-muted {
    color: #64748b;
}

@media (max-width: 768px) {
    .item-details-table {
        display: block;
    }
    
    thead {
        display: none;
    }
    
    tbody, tr, td {
        display: block;
        width: 100%;
    }
    
    tbody tr {
        padding: 16px;
        position: relative;
        margin-bottom: 8px;
    }
    
    td {
        text-align: left;
        margin-bottom: 8px;
        padding: 8px 0;
        position: relative;
        padding-left: 50%;
    }
    
    td:before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 45%;
        font-weight: 600;
        font-size: 12px;
        color: #475569;
    }
    
    .action-column {
        position: absolute;
        top: 16px;
        right: 16px;
        width: auto;
        padding-left: 0;
    }
    
    .table-footer {
        flex-direction: column;
    }
    
    .footer-actions {
        width: 100%;
        margin-bottom: 12px;
    }
    
    .add-row-btn {
        width: 100%;
        justify-content: center;
    }
    
    .total-amount-container {
        width: 100%;
        justify-content: space-between;
    }
}
</style>