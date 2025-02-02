
import { onMounted } from 'vue';

export default function useTab() {

    onMounted(() => {
        initTab('.tabs-item__online-store', '.online-store-container');
    });

    const initTab = (tabsClass, tabContentsClass) => {
        const tabs = document.querySelectorAll(tabsClass);
        const tabContents = document.querySelectorAll(tabContentsClass);
        if (tabs.length > 0) { tabs[0].classList.add('tabs-item-active'); }
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('tabs-item-active'));
                this.classList.add('tabs-item-active');
                tabContents.forEach(tabContent => {
                    if (tabContent.id === this.id) {
                        tabContent.classList.remove('hidden');
                    } else {
                        tabContent.classList.add('hidden');
                    }
                });
            });
        });
    };
}
