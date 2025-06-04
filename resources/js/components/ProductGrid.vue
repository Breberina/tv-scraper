<template>
    <div class="row flex-column flex-md-row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="list-group">
                <div
                    v-for="cat in categories"
                    :key="cat.id"
                    class="list-group-item list-group-item-action text-center border-0"
                    @click="fetchProducts(1, cat.id)"
                    style="cursor: pointer;"
                >
                    <div class="d-flex flex-column align-items-center">
                        <div
                            class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px; overflow: hidden;"
                        >
                            <img
                                :src="`/storage/${cat.image}`"
                                :alt="cat.title"
                                style="width: 100%; height: auto;"
                            />
                        </div>
                        <span class="mt-2">{{ cat.title }}</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Products -->
        <div class="col">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                <div class="col" v-for="product in products" :key="product.id">
                    <div class="card h-100">
                        <img
                            :src="`/storage/${product.image_path}`"
                            class="card-img-top"
                            :alt="product.title"
                        />
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">{{ product.title }}</h6>
                            <p class="card-text fw-bold mt-auto">{{ product.price }}</p>
                            <a :href="`/products/${product.id}`" class="btn btn-primary w-100 mt-2">Detaljnije</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <nav v-if="meta" class="d-flex justify-content-between align-items-center flex-wrap mt-4">
                <!-- Mobile Pagination (Prev / Next only) -->
                <ul class="pagination d-sm-none w-100 justify-content-between mb-2">
                    <li class="page-item" :class="{ disabled: !meta.prev_page_url }">
                        <a class="page-link" href="#" @click.prevent="fetchProducts(currentPage - 1)">
                            &laquo; {{ $t ? $t('pagination.previous') : 'Prethodna' }}
                        </a>
                    </li>
                    <li class="page-item" :class="{ disabled: !meta.next_page_url }">
                        <a class="page-link" href="#" @click.prevent="fetchProducts(currentPage + 1)">
                            {{ $t ? $t('pagination.next') : 'Sledeća' }} &raquo;
                        </a>
                    </li>
                </ul>

                <!-- Pagination Info and Desktop Full Pagination -->
                <div class="d-none d-sm-flex w-100 justify-content-between align-items-center">
                    <!-- Showing X to Y of Z results -->
                    <p class="small text-muted m-0">
                        Showing
                        <span class="fw-semibold">{{ meta.from }}</span>
                        to
                        <span class="fw-semibold">{{ meta.to }}</span>
                        from
                        <span class="fw-semibold">{{ meta.total }}</span>
                        total
                    </p>

                    <!-- Page Numbers with Dots -->
                    <ul class="pagination mb-0">
                        <!-- Prev -->
                        <li class="page-item" :class="{ disabled: !meta.prev_page_url }">
                            <a class="page-link" href="#" @click.prevent="fetchProducts(currentPage - 1)">
                                &lsaquo;
                            </a>
                        </li>

                        <!-- Numbered Pages & Dots -->
                        <li
                            v-for="(link, index) in meta.links"
                            :key="index"
                            class="page-item"
                            :class="{
          active: link.active,
          disabled: link.url === null
        }"
                        >
                            <a
                                v-if="link.url"
                                class="page-link"
                                href="#"
                                @click.prevent="handlePageLinkClick(link)"
                                v-html="link.label"
                            ></a>
                            <span v-else class="page-link" v-html="link.label"></span>
                        </li>

                        <!-- Next -->
                        <li class="page-item" :class="{ disabled: !meta.next_page_url }">
                            <a class="page-link" href="#" @click.prevent="fetchProducts(currentPage + 1)">
                                &rsaquo;
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

        </div>
    </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const products = ref([])
const categories = ref([])
const meta = ref(null)
const currentPage = ref(1)
const selectedCategory = ref(null)

async function fetchProducts(page = 1, category = selectedCategory.value) {
    const url = category
        ? `/api/products/category/${category}?page=${page}`
        : `/api/products?page=${page}`

    try {
        const res = await axios.get(url)
        products.value = res.data.data
        meta.value = res.data
        currentPage.value = res.data.current_page
        selectedCategory.value = category
    } catch (error) {
        console.error('Error loading products:', error)
    }
}

async function fetchCategories() {
    try {
        const res = await axios.get('/api/categories')
        categories.value = res.data
    } catch (error) {
        console.error('Error fetching categories:', error)
    }
}

function handlePageLinkClick(link) {
    if (!link.url || link.active) return

    const url = new URL(link.url)
    const page = url.searchParams.get('page') ?? 1
    fetchProducts(Number(page))
}

onMounted(() => {
    fetchCategories()
    fetchProducts()
})
</script>

