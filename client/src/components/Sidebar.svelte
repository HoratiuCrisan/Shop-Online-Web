<script lang="ts">
    import type Product from "../interfaces/products";
    import { getAllProducts } from "../api/products";
    import { onMount } from "svelte";
    import {goto} from "$app/navigation"

    let categories = [];

    onMount(async () => {
        try {
            const response = await getAllProducts();

            if (!response) {
                throw new Error("Error at fetching all categories");
            }

            const products: Product[] = response.products;

            const cat = products.map(product => product.category);

            categories = [...new Set(cat)];
        } catch (error) {
            console.error("Error at fetching categories: " + error);
        }
    })

    const handleNavigation = (category: string) => {
            goto(`/filter/${category}`);
    }

    const handleKey = () => {}
</script>

<aside class="block w-3/12 md:w-2/12 bg-white h-screen">
    <ul class="block w-full text-blue-800 font-semibold px-2 pt-24 md:pt-20">
        {#each categories as category}
            <div
                on:click={() => handleNavigation(category)} 
                on:keydown={handleKey}
                role="button"
                tabindex=0
                class="px-4 py-2 hover:bg-gray-200 hover:rounded-md cursor-pointer"
            >
                {category}
            </div>
        {/each}
    </ul>
</aside>