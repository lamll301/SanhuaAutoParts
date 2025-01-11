
export default function usePagination(currentPage, totalPages, route) {
    const paginationRange = () => {
        let page = parseInt(route.query.page) || 1;
        if (!totalPages) return [1];
        page = page > totalPages ? totalPages : page;
        page = page < 1 ? 1 : page;
        let range = [];
        if (totalPages <= 5) {
            for (let i = 1; i <= totalPages; i++) {
                range.push(i);
            }
            return range;
        }
        if (page <= 3) {
            range = [1, 2, 3, 4, '...', totalPages];
        } else if (page >= totalPages - 2) {
            range = [1, '...', totalPages - 3, totalPages - 2, totalPages - 1, totalPages];
        } else {
            range = [1, '...', page - 1, page, page + 1, '...', totalPages];
        }
        return range;
    };

    const previous = () => {
        let page = currentPage > totalPages ? totalPages - 1 : currentPage - 1;
        let query = { ...route.query };
        query.page = page;
        return { path: route.path, query };
    };

    const next = () => {
        let page = currentPage < 1 ? 2 : currentPage + 1;
        let query = { ...route.query };
        query.page = page;
        return { path: route.path, query };
    };

    const selectPage = (page) => {
        const query = { ...route.query, page };
        return { path: route.path, query };
    };

    return {
        paginationRange: paginationRange(),
        previous: previous(),
        next: next(),
        selectPage,
    };
}