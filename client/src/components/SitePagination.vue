<template>
    <nav>
        <ul class="pagination category-pagination">
            <li class="page-item category-page-item" :class="{ disabled: currentPage <= 1 || totalPages <= 1 }">
                <router-link :to="pagination.previous" class="page-link category-page-link">
                    <span aria-hidden="true">&laquo;</span>
                </router-link>
            </li>
            <li v-for="page in pagination.paginationRange" :key="page" class="page-item category-page-item">
                <span v-if="page === '...'" class="page-link category-page-link">
                    {{ page }}
                </span>
                <router-link
                    v-else
                    class="page-link category-page-link"
                    :to="pagination.selectPage(page)"
                    :class="{ active: page === currentPage }"
                >
                    {{ page }}
                </router-link>
            </li>
            <li class="page-item category-page-item" :class="{ disabled: currentPage >= totalPages || totalPages <= 1 }">
                <router-link :to="pagination.next" class="page-link category-page-link">
                    <span aria-hidden="true">&raquo;</span>
                </router-link>
            </li>
        </ul>
    </nav>
</template>

<script>
import { useRoute } from 'vue-router';
import usePagination from '@/composables/usePagination.js';

export default {
    props: {
        totalPages: Number,
        currentPage: Number,
    },
    setup(props) {
        const route = useRoute();
        const pagination = usePagination(props.currentPage, props.totalPages, route);

        return {
            pagination,
        };
    },
};
</script>
