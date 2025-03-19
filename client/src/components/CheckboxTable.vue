<template>
    <table class="table admin-content__table-main">
        <thead class="admin-content__table-main-head">
            <tr class="admin-content__table-first-row">
                <th scope="col">
                    <input class="form-check-input admin-content__checkbox" type="checkbox" ref="checkboxAll" 
                        @change="onCheckboxAllChange($event)"
                    >
                </th>
                <slot name="header"></slot>
            </tr>
        </thead>
        <tbody class="admin-content__table-main-body">
            <template v-if="items.length > 0">
                <tr class="admin-content__table-row" v-for="item in items" :key="item.id">
                    <th>
                        <input class="form-check-input" type="checkbox" ref="checkboxes" :value="item.id" 
                            @change="onCheckboxChange(item.id, $event)"
                        >
                    </th>
                    <slot name="body" :item="item"></slot>
                </tr>
            </template>
            <template v-else>
                <slot name="empty"></slot>
            </template>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        items: {
            type: Array, required: true,
        }
    },
    data() {
        return {
            ids: [],
        }
    },
    methods: {
        onCheckboxAllChange(event) {
            const isChecked = event.target.checked;
            const checkboxes = this.$refs.checkboxes;

            checkboxes.forEach((checkbox) => {
                checkbox.checked = isChecked;
            });

            this.ids = isChecked ? this.items.map((item) => item.id) : [];
            this.$emit('update:ids', this.ids);
        },
        onCheckboxChange(itemId, event) {
            const checkboxAll = this.$refs.checkboxAll;
            const checkboxes = this.$refs.checkboxes;
            const isChecked = event.target.checked;

            const allChecked = checkboxes.every((checkbox) => checkbox.checked);
            checkboxAll.checked = allChecked;

            if (isChecked) {
                this.ids.push(itemId);
            } else {
                this.ids = this.ids.filter((id) => id !== itemId);
            }
            this.$emit('update:ids', this.ids);
        },
        resetCheckboxAll() {
            this.$refs.checkboxAll.checked = false;
            this.ids = [];
            this.$emit('update:ids', this.ids);
        }
    }
}
</script>