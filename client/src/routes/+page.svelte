<script lang="ts">
    import { onMount } from 'svelte';
    import { getAllProducts } from "../api/products";
    import ProductCard from "../components/ProductCard.svelte";
    import type Product from '../interfaces/products';

    let products: Product[] = [];
    let filteredProducts: { category: string, products: Product[] }[] = [];

    onMount(async () => {
        try {
            const response = await getAllProducts();
            
            if (response === undefined) {
                throw new Error("Error at fetching all products");
            }

            products = response.products;

            // Get unique categories
            const categories = [...new Set(products.map(product => product.category))];

            // Filter products to get first 5 products from each category if there are 5 or more products in that category
            filteredProducts = categories
                .map(category => {
                    const categoryProducts = products.filter(product => product.category === category);
                    return {
                        category,
                        products: categoryProducts.length >= 4 ? categoryProducts.slice(0, 4) : []
                    };
                })
                .filter(categoryData => categoryData.products.length > 0);

            console.log(filteredProducts);
        } catch (error) {
            console.error("Error at fetching products: " + error);
        }
    });

    const firstCharToUpper = (text: string) => {
        return text.charAt(0).toUpperCase() + text.substring(1, text.length);
    }
</script>

<div class="container p-4">
    
    {#each filteredProducts as categoryData}
        <div class="mt-20 mb-4">
            <h2 class="text-xl font-semibold mb-2">{firstCharToUpper(categoryData.category)}</h2>
            <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                {#each categoryData.products as product}
                    <ProductCard {product} />
                {/each}
            </div>
        </div>
    {/each}
</div>
