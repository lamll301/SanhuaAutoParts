import { defineStore } from 'pinia';

export const useReviewStore = defineStore('review', {
    state: () => ({
        review: {},
    }),
    actions: {
        setReview(review) {
            this.review = review;
        },
        clearReview() {
            this.review = {};
        }
    }
});
