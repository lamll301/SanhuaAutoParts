<template>
    <router-link :to="getLink">
        <i :class="getIcon"></i>
    </router-link>
</template>

<script>
export default {
    props: {
        field: String,
        sort: Object
    },
    computed: {
        getLink() {
            return this.sortable().link
        },
        getIcon() {
            return this.sortable().icon
        }
    },
    methods: {
        sortable() {
            const sortType = this.field === this.sort.column ? this.sort.type : 'default'
            const icons = {
                default: 'fa-solid fa-sort',
                asc: 'fa-solid fa-sort-up',
                desc: 'fa-solid fa-sort-down'
            }
            const types = {
                default: 'desc',
                asc: 'desc',
                desc: 'asc'
            }
            const icon = icons[sortType]
            const type = types[sortType]
            
            const query = {
                ...this.$route.query,
                _sort: 'true',
                column: this.field,
                type: type
            }
            
            return { 
                link: { query },
                icon 
            }
        }
    }
}
</script>