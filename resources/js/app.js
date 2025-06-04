import './bootstrap';
import { createApp } from 'vue'
import ProductGrid from './components/ProductGrid.vue'

const el = document.querySelector('#product-grid')

if (el) {
    const app = createApp(ProductGrid)
    app.mount(el)
}
