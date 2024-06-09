<script lang="ts">
    import { page } from "$app/stores";
    import { filterProductsByCategory } from "../../../api/products";
    import type Product from "../../../interfaces/products";
    import ProductCard from "../../../components/ProductCard.svelte";

    let category: string;
    let products: Product[] = [];
    let loading = true;
    let error = null;

    // Reactive statement to update category when the URL parameter changes
    $: category = $page.params.category;

    // Function to fetch products by category
    async function fetchProductsByCategory(category: string) {
        loading = true;
        error = null;

        try {
            const response = await filterProductsByCategory(category);
            if (!response || !response.products) {
                throw new Error("Failed to fetch products");
            }
            products = response.products;
        } catch (err) {
            error = err.message;
            products = [];
        } finally {
            loading = false;
        }
    }

    // Call the fetch function whenever the category changes
    $: if (category) {
        fetchProductsByCategory(category);
    }
</script>

<div class="container p-4">
    <h6 class="text-blue-400 mt-20 mb-4"><a href="/">products </a>/ <span class="text-gray-400">{category}</span></h6>
    <h1 class="text-2xl font-bold mb-4">Products: </h1>
    
    {#if loading}
        <p>Loading...</p>
    {:else if error}
        <p>Error: {error}</p>
    {:else}
        <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            {#each products as product}
                <ProductCard {product} />
            {/each}
        </div>
    {/if}
</div>
